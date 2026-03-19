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
