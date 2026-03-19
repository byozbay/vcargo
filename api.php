<?php
/**
 * API Router — handles AJAX endpoints.
 * URL: /vcargo/api.php?action=shipments.create
 */
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

// Auth check
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Oturum açmanız gerekiyor.']);
    exit;
}

require_once __DIR__ . '/core/autoload.php';

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// Parse JSON body for POST/PUT
$input = [];
if (in_array($method, ['POST', 'PUT'])) {
    $raw = file_get_contents('php://input');
    $input = json_decode($raw, true) ?: $_POST;
}

$branchId = (int) ($_SESSION['branch_id'] ?? 0);
$userId   = (int) ($_SESSION['user_id'] ?? 0);

try {
    $response = match ($action) {

        // ── SHIPMENTS ────────────────────────────────────────
        'shipments.list' => (function () use ($branchId) {
            $m      = new ShipmentModel();
            $status = $_GET['status'] ?? '';
            $search = $_GET['search'] ?? '';
            $page   = max(1, (int) ($_GET['page'] ?? 1));
            $limit  = 20;
            $offset = ($page - 1) * $limit;
            $role   = $_SESSION['user_role'] ?? '';
            $bid    = ($role === 'admin') ? 0 : $branchId;

            return [
                'data'  => $m->getList($bid, $status, $search, $limit, $offset),
                'total' => $m->countList($bid, $status, $search),
                'page'  => $page,
            ];
        })(),

        'shipments.create' => (function () use ($input, $branchId, $userId) {
            $m = new ShipmentModel();
            $input['branch_id']        = $branchId;
            $input['origin_branch_id'] = $branchId;
            $input['created_by']       = $userId;
            $id = $m->createShipment($input);
            $shipment = $m->find($id);

            // Record financial transaction if sender pays
            if (($input['payment_type'] ?? '') === 'SENDER_PAYS' && ($input['payment_method'] ?? '') !== 'ACCOUNT') {
                $tx = new TransactionModel();
                $tx->record([
                    'type'        => 'IN',
                    'category'    => 'cargo_fee',
                    'method'      => $input['payment_method'] ?? 'CASH',
                    'amount'      => (float) ($input['total_fee'] ?? 0),
                    'description' => 'Kargo Ücreti – ' . $shipment['tracking_no'],
                    'shipment_id' => $id,
                    'branch_id'   => $branchId,
                    'created_by'  => $userId,
                ]);
            }

            AuditModel::log('shipment.create', 'shipment', $id, null, $shipment);

            return ['success' => true, 'shipment' => $shipment];
        })(),

        'shipments.updateStatus' => (function () use ($input) {
            $m  = new ShipmentModel();
            $id = (int) ($input['shipment_id'] ?? 0);
            $m->updateStatus($id, $input['status']);
            AuditModel::log('shipment.status', 'shipment', $id, null, ['status' => $input['status']]);
            return ['success' => true];
        })(),

        'shipments.stats' => (function () use ($branchId) {
            $m    = new ShipmentModel();
            $role = $_SESSION['user_role'] ?? '';
            return $m->getDashboardStats($role === 'admin' ? 0 : $branchId);
        })(),

        // ── TRIPS ────────────────────────────────────────────
        'trips.list' => (function () use ($branchId) {
            $m      = new TripModel();
            $status = $_GET['status'] ?? '';
            $role   = $_SESSION['user_role'] ?? '';
            return $m->getList($role === 'admin' ? 0 : $branchId, $status);
        })(),

        'trips.create' => (function () use ($input, $branchId, $userId) {
            $m = new TripModel();
            $input['branch_id']  = $branchId;
            $input['created_by'] = $userId;
            $id = $m->createTrip($input);
            AuditModel::log('trip.create', 'trip', $id);
            return ['success' => true, 'trip_id' => $id];
        })(),

        // ── STORAGE ──────────────────────────────────────────
        'storage.list' => (function () use ($branchId) {
            $m      = new StorageModel();
            $type   = $_GET['type'] ?? '';
            $search = $_GET['search'] ?? '';
            $role   = $_SESSION['user_role'] ?? '';
            return $m->getActive($role === 'admin' ? 0 : $branchId, $type, $search);
        })(),

        'storage.create' => (function () use ($input, $branchId, $userId) {
            $m = new StorageModel();
            $input['record_no']  = $m->generateRecordNo();
            $input['branch_id']  = $branchId;
            $input['created_by'] = $userId;
            $input['check_in']   = $input['check_in'] ?? date('Y-m-d H:i:s');

            // Get branch rates
            $bm     = new BranchModel();
            $branch = $bm->find($branchId);
            $input['free_hours']  = $input['free_hours'] ?? ($branch['free_storage_hours'] ?? 4);
            $input['hourly_rate'] = $input['hourly_rate'] ?? (
                $input['type'] === 'baggage'
                    ? ($branch['baggage_hourly_rate'] ?? 3)
                    : ($branch['storage_hourly_rate'] ?? 2)
            );

            $id = $m->create($input);
            AuditModel::log('storage.create', 'storage', $id);
            return ['success' => true, 'storage_id' => $id, 'record_no' => $input['record_no']];
        })(),

        'storage.deliver' => (function () use ($input, $branchId, $userId) {
            $m  = new StorageModel();
            $id = (int) ($input['storage_id'] ?? 0);

            $feeInfo = $m->calculateFee($id);
            $discount = (float) ($input['discount'] ?? 0);
            $approvedBy = $discount > 0 ? ($input['approved_by'] ?? $userId) : null;
            $finalFee  = max(0, $feeInfo['fee'] - $discount);
            $method    = $input['payment_method'] ?? 'CASH';

            $m->deliver($id, $finalFee, $discount, $approvedBy, $method);

            // Record transaction if fee > 0
            if ($finalFee > 0) {
                $record = $m->find($id);
                $tx = new TransactionModel();
                $tx->record([
                    'type'        => 'IN',
                    'category'    => 'storage_fee',
                    'method'      => $method,
                    'amount'      => $finalFee,
                    'description' => 'Emanet Ücreti – ' . ($record['record_no'] ?? ''),
                    'storage_id'  => $id,
                    'branch_id'   => $branchId,
                    'created_by'  => $userId,
                ]);
            }

            AuditModel::log('storage.deliver', 'storage', $id);
            return ['success' => true, 'fee' => $finalFee];
        })(),

        'storage.stats' => (function () use ($branchId) {
            $m    = new StorageModel();
            $role = $_SESSION['user_role'] ?? '';
            return $m->getStats($role === 'admin' ? 0 : $branchId);
        })(),

        // ── VAULT ────────────────────────────────────────────
        'vault.list' => (function () use ($branchId) {
            $m = new TransactionModel();
            return $m->getVaultTransactions(
                $branchId,
                $_GET['from'] ?? '',
                $_GET['to'] ?? '',
                $_GET['type'] ?? '',
                $_GET['method'] ?? ''
            );
        })(),

        'vault.summary' => (function () use ($branchId) {
            $m = new TransactionModel();
            return $m->getDailySummary($branchId, $_GET['date'] ?? '');
        })(),

        'vault.addExpense' => (function () use ($input, $branchId, $userId) {
            $tx = new TransactionModel();
            $txId = $tx->record([
                'type'        => 'OUT',
                'category'    => $input['category'] ?? 'expense',
                'method'      => $input['method'] ?? 'CASH',
                'amount'      => (float) ($input['amount'] ?? 0),
                'description' => $input['description'] ?? '',
                'branch_id'   => $branchId,
                'created_by'  => $userId,
            ]);
            AuditModel::log('vault.expense', 'transaction', $txId);
            return ['success' => true];
        })(),

        // ── BRANCHES ─────────────────────────────────────────
        'branches.list' => (function () {
            $m = new BranchModel();
            return $m->getAllWithCity();
        })(),

        // ── ACCOUNTS ─────────────────────────────────────────
        'accounts.list' => (function () use ($branchId) {
            $m      = new AccountModel();
            $role   = $_SESSION['user_role'] ?? '';
            $search = $_GET['search'] ?? '';
            $type   = $_GET['type'] ?? '';
            return $m->getList($role === 'admin' ? 0 : $branchId, $type, $search);
        })(),

        'accounts.create' => (function () use ($input, $branchId, $userId) {
            $m = new AccountModel();
            $input['branch_id'] = $input['branch_id'] ?? $branchId;
            $id = $m->create($input);
            AuditModel::log('account.create', 'account', $id);
            return ['success' => true, 'account_id' => $id];
        })(),

        // ── USERS ────────────────────────────────────────────
        'users.list' => (function () {
            $m = new UserModel();
            return $m->getList($_GET['role'] ?? '', $_GET['search'] ?? '');
        })(),

        'users.create' => (function () use ($input) {
            $m  = new UserModel();
            $id = $m->createUser($input);
            AuditModel::log('user.create', 'user', $id);
            return ['success' => true, 'user_id' => $id];
        })(),

        // ── CITIES & BUS COMPANIES ───────────────────────────
        'cities.list' => (function () {
            $m = new BaseModel();
            return $m->query("SELECT * FROM cities WHERE is_active = 1 ORDER BY name");
        })(),

        'busCompanies.list' => (function () {
            $m = new BaseModel();
            return $m->query("SELECT * FROM bus_companies WHERE is_active = 1 ORDER BY name");
        })(),

        // ── DASHBOARD ────────────────────────────────────────
        'dashboard.stats' => (function () use ($branchId) {
            $role = $_SESSION['user_role'] ?? '';
            $bid  = ($role === 'admin') ? 0 : $branchId;

            $bm = new BranchModel();
            $sm = new ShipmentModel();
            $st = new StorageModel();
            $tm = new TransactionModel();

            return [
                'branches'    => $bm->countByStatus(),
                'shipments'   => $sm->getDashboardStats($bid),
                'storage'     => $st->getStats($bid),
                'vault'       => $tm->getDailySummary($branchId),
            ];
        })(),


        /**
         * trips.dispatch — create trip + assign shipments + vault OUT for driver payment
         */
        'trips.dispatch' => (function () use ($input, $branchId, $userId) {
            $tm  = new TripModel();
            $sm  = new ShipmentModel();
            $tx  = new TransactionModel();
            $dbh = new Database();
            $pdo = $dbh->connect();
            $pdo->beginTransaction();
            try {
                $tripData = [
                    'branch_id'       => $branchId,
                    'created_by'      => $userId,
                    'company_id'      => (int)   ($input['company_id'] ?? 0),
                    'plate_no'        => $input['plate_no'] ?? '',
                    'driver_name'     => $input['driver_name'] ?? '',
                    'driver_phone'    => $input['driver_phone'] ?? '',
                    'departure_time'  => $input['departure_time'] ?? date('Y-m-d H:i:s'),
                    'commission_rate' => (float) ($input['commission_rate'] ?? 0),
                    'total_cargo_fee' => (float) ($input['total_cargo_fee'] ?? 0),
                    'net_payment'     => (float) ($input['net_payment'] ?? 0),
                    'status'          => 'departed',
                ];
                $tripId = $tm->createTrip($tripData);
                foreach (($input['shipment_ids'] ?? []) as $sid) {
                    $sid = (int) $sid;
                    if ($sid > 0) $sm->update($sid, ['trip_id' => $tripId, 'status' => 'in_transit']);
                }
                if ($tripData['net_payment'] > 0) {
                    $tx->record([
                        'type' => 'OUT', 'category' => 'driver_payment',
                        'method' => $input['pay_method'] ?? 'CASH',
                        'amount' => $tripData['net_payment'],
                        'description' => 'Şoför Ödemesi – ' . $tripData['plate_no'],
                        'trip_id' => $tripId, 'branch_id' => $branchId, 'created_by' => $userId,
                    ]);
                }
                AuditModel::log('trip.dispatch', 'trip', $tripId);
                $pdo->commit();
                return ['success' => true, 'trip_id' => $tripId, 'dispatched' => count($input['shipment_ids'] ?? [])];
            } catch (\Exception $e) { $pdo->rollBack(); throw $e; }
        })(),

        /**
         * shipments.deliver — mark delivered + vault IN for C.O.D at delivery + storage fee
         * C.O.D muhasebe kuralı: kasa kaydı yalnızca teslim anında açılır!
         */
        'shipments.deliver' => (function () use ($input, $branchId, $userId) {
            $sm  = new ShipmentModel();
            $tx  = new TransactionModel();
            $dbh = new Database();
            $pdo = $dbh->connect();
            $shipmentId = (int)   ($input['shipment_id'] ?? 0);
            $storageFee = (float) ($input['storage_fee'] ?? 0);
            $method     = $input['payment_method'] ?? 'CASH';
            $pdo->beginTransaction();
            try {
                $shipment = $sm->find($shipmentId);
                if (!$shipment) throw new \Exception('Kargo bulunamadı.');
                $sm->update($shipmentId, [
                    'status' => 'delivered', 'payment_status' => 'paid',
                    'delivered_at' => date('Y-m-d H:i:s'),
                    'delivery_note' => $input['delivery_note'] ?? null,
                ]);
                if ($shipment['payment_type'] === 'RECEIVER_PAYS' && (float)$shipment['total_fee'] > 0) {
                    $tx->record([
                        'type' => 'IN', 'category' => 'cargo_fee_cod', 'method' => $method,
                        'amount' => (float) $shipment['total_fee'],
                        'description' => 'C.O.D Tahsilat – ' . $shipment['tracking_no'],
                        'shipment_id' => $shipmentId, 'branch_id' => $branchId, 'created_by' => $userId,
                    ]);
                }
                if ($storageFee > 0) {
                    $tx->record([
                        'type' => 'IN', 'category' => 'storage_fee', 'method' => $method,
                        'amount' => $storageFee,
                        'description' => 'Emanet Ücreti – ' . $shipment['tracking_no'],
                        'shipment_id' => $shipmentId, 'branch_id' => $branchId, 'created_by' => $userId,
                    ]);
                }
                AuditModel::log('shipment.deliver', 'shipment', $shipmentId);
                $pdo->commit();
                return ['success' => true, 'shipment_id' => $shipmentId];
            } catch (\Exception $e) { $pdo->rollBack(); throw $e; }
        })(),

        /**
         * vault.close — Daily vault closing.
         * Records counted cash vs expected, logs a close event.
         */
        'vault.close' => (function () use ($input, $branchId, $userId) {
            $dbh = new Database();
            $pdo = $dbh->connect();
            $date        = $input['date'] ?? date('Y-m-d');
            $countedCash = (float) ($input['counted_cash'] ?? 0);
            $note        = $input['note'] ?? '';

            // Get expected cash
            $tm   = new TransactionModel();
            $summ = $tm->getDailySummary($branchId, $date);
            $cashIn  = (float) ($summ['cash_in']  ?? 0);
            $totalOut = (float) ($summ['total_out'] ?? 0);

            $diff = $countedCash - ($cashIn - $totalOut);

            AuditModel::log('vault.close', 'branch', $branchId, null, [
                'date' => $date, 'counted_cash' => $countedCash,
                'expected' => round($cashIn - $totalOut, 2),
                'diff' => round($diff, 2),
                'note' => $note,
            ]);
            return ['success' => true, 'counted_cash' => $countedCash, 'diff' => round($diff, 2)];
        })(),

        /**
         * pricing.save — Save branch storage rates.
         * Updates free_storage_hours and storage_hourly_rate per branch.
         */
        'pricing.save' => (function () use ($input, $userId) {
            if ($_SESSION['user_role'] ?? '' !== 'admin') {
                throw new \Exception('Yetkisiz.');
            }
            $bm = new BranchModel();
            $updated = 0;
            $branches = $input['branches'] ?? [];
            foreach ($branches as $bid => $vals) {
                $bid = (int) $bid;
                if ($bid <= 0) continue;
                $bm->update($bid, [
                    'free_storage_hours'  => (int)   ($vals['free_hours']    ?? 4),
                    'storage_hourly_rate' => (float) ($vals['storage_rate']  ?? 2.00),
                    'baggage_hourly_rate' => (float) ($vals['baggage_rate']  ?? 3.00),
                ]);
                $updated++;
            }
            AuditModel::log('pricing.save', 'branch', null, null, ['branches_updated' => $updated]);
            return ['success' => true, 'updated' => $updated];
        })(),

        /**
         * trips.list — List trips for branch with cargo count and financials
         */
        'trips.list' => (function () use ($input, $branchId) {
            $base = new BaseModel();
            $date = $input['date'] ?? date('Y-m-d');
            $rows = $base->query(
                "SELECT t.trip_id, t.plate_no, bc.name AS company_name,
                        CONCAT(COALESCE(oc.name,'?'),' → ',COALESCE(dc.name,'?')) AS route,
                        t.driver_name, t.driver_phone,
                        DATE_FORMAT(t.departure_time,'%H:%i') AS time,
                        t.commission_rate,
                        t.total_cargo_fee AS gross,
                        t.net_payment AS net,
                        t.status,
                        COUNT(s.shipment_id) AS cargo_count
                 FROM trips t
                 LEFT JOIN bus_companies bc ON bc.company_id = t.company_id
                 LEFT JOIN cities oc ON oc.city_id = t.origin_city_id
                 LEFT JOIN cities dc ON dc.city_id = t.destination_city_id
                 LEFT JOIN shipments s ON s.trip_id = t.trip_id AND s.is_active = 1
                 WHERE t.branch_id = ? AND DATE(t.departure_time) = ?
                 GROUP BY t.trip_id, bc.name, oc.name, dc.name
                 ORDER BY t.departure_time",
                [$branchId, $date]
            );
            /* KPI summary */
            $kpi = $base->query(
                "SELECT COUNT(*) AS total,
                        SUM(status='in_transit') AS active,
                        SUM(status='completed') AS completed
                 FROM trips WHERE branch_id=? AND DATE(departure_time)=?",
                [$branchId, $date]
            )[0] ?? [];
            $net = $base->query(
                "SELECT COALESCE(SUM(amount),0) AS v FROM transactions
                 WHERE category='driver_payment' AND branch_id=? AND DATE(created_at)=? AND is_active=1",
                [$branchId, $date]
            )[0]['v'] ?? 0;
            $cargoToday = $base->query(
                "SELECT COUNT(*) AS v FROM shipments s
                 JOIN trips t ON t.trip_id=s.trip_id
                 WHERE t.branch_id=? AND DATE(t.departure_time)=? AND s.is_active=1",
                [$branchId, $date]
            )[0]['v'] ?? 0;
            return ['success'=>true,'trips'=>$rows,'kpi'=>[
                'total'=>(int)($kpi['total']??0),
                'active'=>(int)($kpi['active']??0),
                'cargo_today'=>(int)$cargoToday,
                'net_paid'=>(float)$net,
            ]];
        })(),

        /**
         * storage.list — Active storage records for branch
         */
        'storage.list' => (function () use ($input, $branchId) {
            $base = new BaseModel();
            $branchRow = $base->query(
                "SELECT free_storage_hours, storage_hourly_rate, baggage_hourly_rate
                 FROM branches WHERE branch_id=? LIMIT 1", [$branchId]
            )[0] ?? ['free_storage_hours'=>4,'storage_hourly_rate'=>2,'baggage_hourly_rate'=>3];
            $rows = $base->query(
                "SELECT sr.storage_id, sr.reference_code, sr.type,
                        sr.owner_name, sr.owner_phone, sr.location,
                        sr.checked_in_at,
                        TIMESTAMPDIFF(MINUTE, sr.checked_in_at, NOW()) AS minutes_elapsed
                 FROM storage_records sr
                 WHERE sr.branch_id=? AND sr.status='active' AND sr.is_active=1
                 ORDER BY sr.checked_in_at",
                [$branchId]
            );
            $freeH  = (int)$branchRow['free_storage_hours'];
            $rateH  = (float)$branchRow['storage_hourly_rate'];
            $bagH   = (float)$branchRow['baggage_hourly_rate'];
            /* Enrich with computed fee */
            foreach ($rows as &$r) {
                $totalH = $r['minutes_elapsed'] / 60;
                $rate   = $r['type'] === 'baggage' ? $bagH : $rateH;
                $paidH  = max(0, $totalH - $freeH);
                $r['total_hours'] = round($totalH, 2);
                $r['paid_hours']  = round($paidH, 2);
                $r['fee']         = round($paidH * $rate, 2);
                $r['urgency']     = $totalH >= 24 ? 'critical' : ($paidH > 0 ? 'paid' : 'free');
                $r['free_hours']  = $freeH;
                $r['rate_per_h']  = $rate;
            }
            unset($r);

            /* KPI */
            $total   = count($rows);
            $paid    = count(array_filter($rows, fn($r)=>$r['urgency']!=='free'));
            $pending = array_sum(array_column($rows, 'fee'));
            $today   = $base->query(
                "SELECT COUNT(*) AS v FROM storage_records
                 WHERE branch_id=? AND status='delivered' AND DATE(checked_out_at)=CURDATE() AND is_active=1",
                [$branchId]
            )[0]['v'] ?? 0;
            return ['success'=>true,'records'=>$rows,'kpi'=>[
                'total'=>$total,'paid'=>$paid,'pending_fee'=>round($pending,2),'today_delivered'=>(int)$today
            ]];
        })(),

        /**
         * accounts.list — List current accounts for branch
         */
        'accounts.list' => (function () use ($input, $branchId) {
            $base = new BaseModel();
            $rows = $base->query(
                "SELECT a.account_id AS id,
                        COALESCE(a.company_name, CONCAT(a.first_name,' ',a.last_name)) AS name,
                        a.account_type AS type,
                        a.contact_person AS contact,
                        a.phone,
                        a.balance AS debt,
                        a.credit_limit AS `limit`,
                        a.is_active,
                        DATE_FORMAT(a.updated_at,'%d.%m.%Y') AS lastTx
                 FROM accounts a
                 WHERE a.branch_id = ? AND a.is_active = 1
                 ORDER BY a.balance DESC",
                [$branchId]
            );
            /* KPI */
            $kpi = $base->query(
                "SELECT COUNT(*) AS total,
                        COALESCE(SUM(balance),0) AS total_debt,
                        SUM(balance > credit_limit) AS over_limit
                 FROM accounts WHERE branch_id=? AND is_active=1",
                [$branchId]
            )[0] ?? [];
            $monthlyPaid = $base->query(
                "SELECT COALESCE(SUM(amount),0) AS v FROM transactions
                 WHERE type='IN' AND method='ACCOUNT' AND branch_id=?
                   AND MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())
                   AND is_active=1",
                [$branchId]
            )[0]['v'] ?? 0;
            return ['success'=>true,'accounts'=>$rows,'kpi'=>[
                'total'        => (int)($kpi['total']      ?? 0),
                'total_debt'   => (float)($kpi['total_debt'] ?? 0),
                'over_limit'   => (int)($kpi['over_limit']  ?? 0),
                'monthly_paid' => (float)$monthlyPaid,
            ]];
        })(),

        /**
         * accounts.create — Create a new current account
         */
        'accounts.create' => (function () use ($input, $branchId, $userId) {
            $base = new BaseModel();
            $type     = $input['type'] ?? 'CORPORATE';
            $name     = trim($input['company_name'] ?? $input['full_name'] ?? '');
            $contact  = trim($input['contact']  ?? '');
            $phone    = trim($input['phone']    ?? '');
            $email    = trim($input['email']    ?? '');
            $address  = trim($input['address']  ?? '');
            $taxNo    = trim($input['tax_no']   ?? '');
            $taxOff   = trim($input['tax_office']?? '');
            $tcNo     = trim($input['tc_no']    ?? '');
            $limit    = (float)($input['credit_limit']  ?? 0);
            $days     = (int)($input['payment_days']    ?? 30);
            $notes    = trim($input['notes']    ?? '');
            if (!$name || !$phone) throw new \Exception('Ad ve telefon zorunludur.');

            $db = new Database();
            $pdo = $db->connect();

            // Check duplicate phone
            $dup = (new BaseModel())->query(
                "SELECT account_id FROM accounts WHERE phone=? AND branch_id=? AND is_active=1 LIMIT 1",
                [$phone, $branchId]
            );
            if ($dup) throw new \Exception('Bu telefon numarasıyla kayıtlı hesap zaten var.');

            $now = date('Y-m-d H:i:s');
            $stmt = $pdo->prepare(
                "INSERT INTO accounts (branch_id, account_type, company_name, first_name, last_name,
                    contact_person, phone, email, address, tax_number, tax_office, id_number,
                    credit_limit, payment_days, notes, balance, is_active, created_by, created_at, updated_at)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,0,1,?,?,?)"
            );
            $isCorp    = in_array(strtoupper($type), ['CORPORATE','kurumsal']);
            $typeConst = $isCorp ? 'CORPORATE' : 'INDIVIDUAL';
            $compNm    = $isCorp ? $name : '';
            $fn        = $isCorp ? '' : ($input['first_name'] ?? $name);
            $ln        = $isCorp ? '' : ($input['last_name']  ?? '');
            $stmt->execute([$branchId,$typeConst,$compNm,$fn,$ln,$contact,$phone,$email,$address,$taxNo,$taxOff,$tcNo,$limit,$days,$notes,$userId,$now,$now]);
            $newId = $pdo->lastInsertId();
            AuditModel::log('accounts.create','account',$newId,null,['name'=>$name,'branch_id'=>$branchId]);
            return ['success'=>true,'account_id'=>$newId];
        })(),

        /**
         * settings.save — Save system settings (stored in a settings table or flat config)
         */
        'settings.save' => (function () use ($input, $userId) {
            if (empty($input)) throw new \Exception('Ayar verisi gönderilmedi.');
            $base = new BaseModel();
            $db   = new Database();
            $pdo  = $db->connect();
            $now  = date('Y-m-d H:i:s');
            $saved = 0;
            foreach ($input as $key => $value) {
                $key   = preg_replace('/[^a-z0-9_]/', '', strtolower($key));
                if (!$key) continue;
                $value = is_bool($value) ? ($value ? '1' : '0') : (string)$value;
                // Upsert into settings table
                $stmt = $pdo->prepare(
                    "INSERT INTO settings (setting_key, setting_value, updated_by, updated_at)
                     VALUES (?, ?, ?, ?)
                     ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value), updated_by=VALUES(updated_by), updated_at=VALUES(updated_at)"
                );
                $stmt->execute([$key, $value, $userId, $now]);
                $saved++;
            }
            AuditModel::log('settings.save', 'settings', null, null, ['count' => $saved]);
            return ['success' => true, 'saved' => $saved];
        })(),
        default => throw new \Exception("Bilinmeyen işlem: {$action}"),
    };

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (\Exception $e) {
    http_response_code(400);
    echo json_encode([
        'error'   => $e->getMessage(),
        'success' => false,
    ], JSON_UNESCAPED_UNICODE);
}
