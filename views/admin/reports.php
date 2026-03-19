<?php
/* ── Reports — Real DB Queries ── */
require_once __DIR__ . '/../../core/autoload.php';
$base = new BaseModel();
$days = intval($_GET['range'] ?? 90);

/* ── KPI Totals ── */
$kpi = $base->query(
    "SELECT
       COALESCE(SUM(CASE WHEN t.type='IN' THEN t.amount ELSE 0 END), 0)                AS total_revenue,
       COALESCE(SUM(CASE WHEN t.category='storage_fee' THEN t.amount ELSE 0 END), 0)   AS total_storage_rev,
       COALESCE(SUM(CASE WHEN t.category='cargo_fee_cod' THEN t.amount ELSE 0 END), 0) AS cod_collected
     FROM transactions t
     WHERE t.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) AND t.is_active = 1",
    [$days]
)[0] ?? [];

$shipKpi = $base->query(
    "SELECT COUNT(*) AS total_shipments, AVG(total_fee) AS avg_shipment_val
     FROM shipments
     WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) AND is_active = 1",
    [$days]
)[0] ?? [];

$storageKpi = $base->query(
    "SELECT COUNT(*) AS total_storage
     FROM storage_records
     WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) AND is_active = 1",
    [$days]
)[0] ?? [];

/* ── Monthly Revenue last 7 months ── */
$monthlyRevenue = $base->query(
    "SELECT DATE_FORMAT(t.created_at, '%b') AS ay,
            SUM(CASE WHEN t.type='IN' THEN t.amount ELSE 0 END) AS ciro,
            COUNT(DISTINCT t.shipment_id) AS kargo
     FROM transactions t
     WHERE t.created_at >= DATE_SUB(NOW(), INTERVAL 7 MONTH)
       AND t.type = 'IN' AND t.is_active = 1
     GROUP BY YEAR(t.created_at), MONTH(t.created_at)
     ORDER BY YEAR(t.created_at), MONTH(t.created_at)"
);
$maxRevenue = count($monthlyRevenue) ? max(array_column($monthlyRevenue, 'ciro')) : 1;
if (!$maxRevenue) $maxRevenue = 1;

/* ── Top Branches ── */
$topBranches = $base->query(
    "SELECT b.name, c.name AS city,
            COUNT(s.shipment_id) AS shipments,
            COALESCE(SUM(s.total_fee), 0) AS revenue
     FROM shipments s
     JOIN branches b ON b.branch_id = s.branch_id
     LEFT JOIN cities c ON c.city_id = b.city_id
     WHERE s.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) AND s.is_active = 1
     GROUP BY b.branch_id ORDER BY revenue DESC LIMIT 5",
    [$days]
);
$maxBranch = count($topBranches) ? max(array_column($topBranches, 'revenue')) : 1;
if (!$maxBranch) $maxBranch = 1;
foreach ($topBranches as &$tb) { $tb['pct'] = round($tb['revenue'] / $maxBranch * 100); }
unset($tb);

/* ── Top Bus Companies ── */
$topCompanies = $base->query(
    "SELECT bc.name,
            COUNT(DISTINCT tr.trip_id) AS trips,
            COUNT(s.shipment_id) AS cargo,
            COALESCE(SUM(s.total_fee * tr.commission_rate / 100), 0) AS commission
     FROM trips tr
     JOIN bus_companies bc ON bc.company_id = tr.company_id
     LEFT JOIN shipments s ON s.trip_id = tr.trip_id AND s.is_active = 1
     WHERE tr.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
     GROUP BY bc.company_id ORDER BY commission DESC LIMIT 5",
    [$days]
);
$maxTrips = count($topCompanies) ? max(array_column($topCompanies, 'trips')) : 1;
if (!$maxTrips) $maxTrips = 1;

/* ── Payment Method Breakdown ── */
$payRows = $base->query(
    "SELECT method, COUNT(*) AS cnt, SUM(amount) AS amount
     FROM transactions
     WHERE type='IN' AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) AND is_active=1
     GROUP BY method",
    [$days]
);
$totalPay = array_sum(array_column($payRows, 'amount')) ?: 1;
$mColors = ['CASH'=>'#1b84ff','CARD'=>'#0e8045','ACCOUNT'=>'#e08b00'];
$mLabels = ['CASH'=>'Nakit','CARD'=>'Kredi Kartı','ACCOUNT'=>'Cari Hesap'];
$paymentBreakdown = array_map(fn($p) => [
    'type'  => $mLabels[$p['method']] ?? $p['method'],
    'pct'   => round($p['amount'] / $totalPay * 100),
    'amount'=> (float)$p['amount'],
    'cnt'   => (int)$p['cnt'],
    'color' => $mColors[$p['method']] ?? '#64748b',
], $payRows);

/* ── Cargo Status ── */
$cargoStatus = $base->query(
    "SELECT status, COUNT(*) AS cnt FROM shipments
     WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) AND is_active=1 GROUP BY status",
    [$days]
);
$statusMap = [];
foreach ($cargoStatus as $cs) { $statusMap[$cs['status']] = (int)$cs['cnt']; }
$delivered  = $statusMap['delivered'] ?? 0;
$inStorage  = ($statusMap['in_storage'] ?? 0) + ($statusMap['at_branch'] ?? 0);
$cancelled  = ($statusMap['cancelled'] ?? 0) + ($statusMap['returned'] ?? 0);

$totalRevenue    = (float)($kpi['total_revenue']    ?? 0);
$codCollected    = (float)($kpi['cod_collected']     ?? 0);
$totalStorageRev = (float)($kpi['total_storage_rev'] ?? 0);
$totalShipments  = (int)($shipKpi['total_shipments'] ?? 0);
$avgVal          = (float)($shipKpi['avg_shipment_val'] ?? 0);
$totalStorage    = (int)($storageKpi['total_storage'] ?? 0);

/* Driver/bus payments OUT */
$busPaid = $base->query(
    "SELECT COALESCE(SUM(amount),0) AS v FROM transactions
     WHERE type='OUT' AND category='driver_payment'
       AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) AND is_active=1",
    [$days]
)[0]['v'] ?? 0;
$netKasa = $totalRevenue - (float)$busPaid;
?>
<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Raporlar & Analizler</div>
            <div class="breadcrumb">
                <a href="?page=dashboard" style="color:var(--text-muted);">Dashboard</a>
                <span class="sep">·</span>
                <span style="color:var(--text-muted);">Raporlar</span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <!-- Date Range -->
            <div class="d-flex gap-1 align-items-center"
                style="background:var(--body-bg);border:1px solid var(--border-color);border-radius:6px;padding:5px 10px;font-size:.78rem;">
                <i class="bi bi-calendar3" style="color:var(--text-muted);"></i>
                <select class="form-input" id="reportRange"
                    style="border:none;background:transparent;padding:0;font-size:.78rem;font-weight:600;"
                    id="reportRange" onchange="updateRange()">
                    <option value="7"<?= $days==7?" selected":""?>>Son 7 Gün</option>
                    <option value="30"<?= $days==30?" selected":""?>>Son 30 Gün</option>
                    <option value="90"<?= $days==90?" selected":""?>>Son 3 Ay</option>
                    <option value="180"<?= $days==180?" selected":""?>>Son 6 Ay</option>
                    <option value="365"<?= $days==365?" selected":""?>>Bu Yıl</option>
                </select>
            </div>
            <button class="btn-outline-secondary-sm d-flex align-items-center gap-1" onclick="exportReport()">
                <i class="bi bi-download"></i> Dışa Aktar
            </button>
            <button class="btn-outline-secondary-sm d-flex align-items-center gap-1" onclick="window.print()">
                <i class="bi bi-printer"></i> Yazdır
            </button>
        </div>
    </div>

    <div style="padding:18px 26px 40px;">

        <!-- ══ Rapor Tab ══ -->
        <div class="d-flex gap-1 mb-3 flex-wrap">
            <?php foreach ([
                ['genel', 'bi-bar-chart-line', 'Genel Özet'],
                ['branches', 'bi-shop', 'Şube Analizi'],
                ['finance', 'bi-cash-stack', 'Finans'],
                ['cargo', 'bi-box-seam', 'Kargo'],
            ] as [$k, $ico, $lbl]): ?>
                <button class="pricing-tab <?= $k === 'genel' ? 'active' : '' ?>" id="rtab-<?= $k ?>"
                    onclick="switchReport('<?= $k ?>')">
                    <i class="bi <?= $ico ?> me-1"></i>
                    <?= $lbl ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- ════════════════ GENEL ÖZET ════════════════ -->
        <div class="report-pane" id="rpane-genel">

            <!-- KPI -->
            <div class="row g-3 mb-3">
                <?php foreach ([
                    ['Toplam Ciro', '₺' . number_format($reportData['total_revenue']), 'bi-graph-up', '#1b84ff', '#e8f1ff', '+18% geçen döneme göre', 'up'],
                    ['Toplam Kargo', number_format($reportData['total_shipments']), 'bi-box-seam', '#0e8045', '#e7f9f0', '+12% geçen döneme göre', 'up'],
                    ['Ortalama Kargo (₺)', '₺' . number_format($reportData['avg_shipment_val'], 1), 'bi-receipt', '#8e24aa', '#f3e5f5', '+5% geçen döneme göre', 'up'],
                    ['Emanet İşlemi', number_format($reportData['total_storage']), 'bi-archive', '#e08b00', '#fff8ec', '-3% geçen döneme göre', 'down'],
                ] as [$label, $val, $ico, $clr, $bg, $note, $dir]): ?>
                    <div class="col-6 col-lg-3">
                        <div class="card h-100" style="padding:18px;">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="card-sm-label">
                                        <?= $label ?>
                                    </div>
                                    <div class="stat-value" style="font-size:1.5rem;margin-top:4px;">
                                        <?= $val ?>
                                    </div>
                                    <span class="badge-change badge-<?= $dir ?> mt-2">
                                        <?= $dir === 'up' ? '↑' : '↓' ?>
                                        <?= $note ?>
                                    </span>
                                </div>
                                <div
                                    style="width:40px;height:40px;border-radius:8px;background:<?= $bg ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="bi <?= $ico ?>" style="color:<?= $clr ?>;font-size:1.1rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Aylık Ciro Grafiği + Ödeme Dağılımı -->
            <div class="row g-3 mb-3">
                <div class="col-12 col-lg-8">
                    <div class="card" style="padding:22px;">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="section-label"><i class="bi bi-bar-chart me-2" style="color:#1b84ff;"></i>Aylık
                                Ciro & Kargo Trendi</div>
                        </div>
                        <!-- Custom bar chart -->
                        <div
                            style="display:flex;align-items:flex-end;gap:10px;height:180px;padding-bottom:24px;position:relative;border-bottom:1px solid var(--border-color);">
                            <?php foreach ($monthlyRevenue as $m):
                                $h = round(($m['ciro'] / $maxRevenue) * 160);
                                ?>
                                <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;">
                                    <div style="font-size:.68rem;color:var(--text-muted);font-weight:600;">₺
                                        <?= number_format($m['ciro'] / 1000, 0) ?>K
                                    </div>
                                    <div style="width:100%;background:linear-gradient(180deg,#1b84ff,#60a5fa);border-radius:4px 4px 0 0;height:<?= $h ?>px;transition:opacity .2s;cursor:pointer;"
                                        onmouseover="this.style.opacity='.75'" onmouseout="this.style.opacity='1'"
                                        title="<?= $m['ay'] ?>: ₺<?= number_format($m['ciro']) ?> · <?= $m['kargo'] ?> kargo">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <!-- Y-axis labels -->
                            <div
                                style="position:absolute;left:-36px;top:0;bottom:24px;display:flex;flex-direction:column;justify-content:space-between;font-size:.64rem;color:var(--text-muted);">
                                <span>₺
                                    <?= number_format($maxRevenue / 1000, 0) ?>K
                                </span>
                                <span>₺
                                    <?= number_format($maxRevenue / 2000, 0) ?>K
                                </span>
                                <span>₺0</span>
                            </div>
                        </div>
                        <div style="display:flex;gap:10px;margin-top:8px;">
                            <?php foreach ($monthlyRevenue as $m): ?>
                                <div style="flex:1;text-align:center;font-size:.68rem;color:var(--text-muted);">
                                    <?= $m['ay'] ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card h-100" style="padding:22px;">
                        <div class="section-label mb-4"><i class="bi bi-pie-chart me-2" style="color:#1b84ff;"></i>Ödeme
                            Dağılımı</div>
                        <!-- Donut chart via SVG -->
                        <div style="text-align:center;margin-bottom:16px;">
                            <svg width="120" height="120" viewBox="0 0 36 36" style="transform:rotate(-90deg);">
                                <?php
                                $offset = 0;
                                $circumference = 2 * pi() * 15.9;
                                foreach ($paymentBreakdown as $pb):
                                    $dash = ($pb['pct'] / 100) * $circumference;
                                    $gap = $circumference - $dash;
                                    ?>
                                    <circle cx="18" cy="18" r="15.9" fill="transparent" stroke="<?= $pb['color'] ?>"
                                        stroke-width="3.8" stroke-dasharray="<?= round($dash, 2) ?> <?= round($gap, 2) ?>"
                                        stroke-dashoffset="-<?= round($offset, 2) ?>" />
                                    <?php
                                    $offset += $dash;
                                endforeach; ?>
                            </svg>
                        </div>
                        <?php foreach ($paymentBreakdown as $pb): ?>
                            <div class="d-flex align-items-center justify-content-between mb-2" style="font-size:.8rem;">
                                <div class="d-flex align-items-center gap-2">
                                    <span
                                        style="width:10px;height:10px;border-radius:50%;background:<?= $pb['color'] ?>;display:inline-block;flex-shrink:0;"></span>
                                    <?= $pb['type'] ?>
                                </div>
                                <div class="d-flex gap-2">
                                    <span style="font-weight:700;">
                                        <?= $pb['pct'] ?>%
                                    </span>
                                    <span style="color:var(--text-muted);">₺
                                        <?= number_format($pb['amount']) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Otobüs Firmaları -->
            <div class="card" style="padding:22px;">
                <div class="section-label mb-4"><i class="bi bi-bus-front me-2" style="color:#1b84ff;"></i>Firma
                    Performansı (Bu Dönem)</div>
                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Firma</th>
                                <th>Sefer</th>
                                <th>Kargo</th>
                                <th>Komisyon</th>
                                <th>Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $maxT = max(array_column($topCompanies, 'trips'));
                            foreach ($topCompanies as $i => $co): ?>
                                <tr>
                                    <td style="font-size:.78rem;color:var(--text-muted);">
                                        <?= $i + 1 ?>
                                    </td>
                                    <td style="font-size:.82rem;font-weight:700;">
                                        <?= $co['name'] ?>
                                    </td>
                                    <td style="font-size:.82rem;">
                                        <?= $co['trips'] ?>
                                    </td>
                                    <td style="font-size:.82rem;">
                                        <?= number_format($co['cargo']) ?>
                                    </td>
                                    <td style="font-size:.82rem;font-weight:700;color:#0e8045;">₺
                                        <?= number_format($co['commission']) ?>
                                    </td>
                                    <td style="width:120px;">
                                        <div
                                            style="background:var(--body-bg);border-radius:3px;height:7px;overflow:hidden;">
                                            <div
                                                style="height:100%;background:#1b84ff;border-radius:3px;width:<?= round($co['trips'] / $maxT * 100) ?>%;">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ════════════════ ŞUBE ANALİZİ ════════════════ -->
        <div class="report-pane" id="rpane-branches" style="display:none;">
            <div class="card mb-3" style="padding:22px;">
                <div class="section-label mb-4"><i class="bi bi-trophy me-2" style="color:#e08b00;"></i>Şube Sıralaması
                </div>
                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Sıra</th>
                                <th>Şube</th>
                                <th>Şehir</th>
                                <th>Kargo</th>
                                <th>Ciro</th>
                                <th>Performans</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topBranches as $i => $b): ?>
                                <tr>
                                    <td>
                                        <?php if ($i === 0): ?>
                                            <span style="font-size:1rem;">🥇</span>
                                        <?php elseif ($i === 1): ?>
                                            <span style="font-size:1rem;">🥈</span>
                                        <?php elseif ($i === 2): ?>
                                            <span style="font-size:1rem;">🥉</span>
                                        <?php else: ?>
                                            <span style="font-size:.8rem;color:var(--text-muted);">
                                                <?= $i + 1 ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="font-size:.83rem;font-weight:700;">
                                        <?= $b['name'] ?>
                                    </td>
                                    <td>
                                        <span class="status-badge" style="background:#e8f1ff;color:#1b84ff;">
                                            <?= $b['city'] ?>
                                        </span>
                                    </td>
                                    <td style="font-size:.82rem;">
                                        <?= number_format($b['shipments']) ?>
                                    </td>
                                    <td style="font-size:.82rem;font-weight:700;color:#0e8045;">₺
                                        <?= number_format($b['revenue']) ?>
                                    </td>
                                    <td style="width:150px;">
                                        <div
                                            style="background:var(--body-bg);border-radius:3px;height:8px;overflow:hidden;">
                                            <div
                                                style="height:100%;border-radius:3px;background:linear-gradient(90deg,#1b84ff,#60a5fa);width:<?= $b['pct'] ?>%;">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ════════════════ FİNANS ════════════════ -->
        <div class="report-pane" id="rpane-finance" style="display:none;">
            <div class="row g-3 mb-3">
                <?php foreach ([
                    ['Toplam Tahsilat', '₺847.320', '#1b84ff', '#e8f1ff', 'bi-cash'],
                    ['Kapıda Ödeme', '₺128.400', '#0e8045', '#e7f9f0', 'bi-cash-coin'],
                    ['Otobüsçüye Ödenen', '₺93.200', '#c03060', '#fff0f2', 'bi-bus-front'],
                    ['Net Kasa Geliri', '₺625.720', '#8e24aa', '#f3e5f5', 'bi-bank'],
                ] as [$lbl, $val, $clr, $bg, $ico]): ?>
                    <div class="col-6 col-lg-3">
                        <div class="card h-100" style="padding:18px;">
                            <div
                                style="width:38px;height:38px;border-radius:8px;background:<?= $bg ?>;display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
                                <i class="bi <?= $ico ?>" style="color:<?= $clr ?>;font-size:1rem;"></i>
                            </div>
                            <div class="card-sm-label">
                                <?= $lbl ?>
                            </div>
                            <div class="stat-value" style="font-size:1.4rem;margin-top:4px;">
                                <?= $val ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="card" style="padding:22px;">
                <div class="section-label mb-4"><i class="bi bi-table me-2" style="color:#1b84ff;"></i>Ödeme Yöntemi
                    Detayı</div>
                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Ödeme Yöntemi</th>
                                <th>İşlem Sayısı</th>
                                <th>Toplam Tutar</th>
                                <th>Ortalama</th>
                                <th>Pay (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($paymentBreakdown as $pb): ?>
                                <tr>
                                    <td>
                                        <span
                                            style="display:inline-flex;align-items:center;gap:6px;font-size:.82rem;font-weight:700;">
                                            <span
                                                style="width:10px;height:10px;border-radius:50%;background:<?= $pb['color'] ?>;display:inline-block;flex-shrink:0;"></span>
                                            <?= $pb['type'] ?>
                                        </span>
                                    </td>
                                    <td style="font-size:.82rem;">
                                        <?= number_format($pb['cnt']) ?>
                                    </td>
                                    <td style="font-size:.82rem;font-weight:700;">₺
                                        <?= number_format($pb['amount']) ?>
                                    </td>
                                    <td style="font-size:.82rem;">₺
                                        <?= number_format($pb['amount'] / max(1, $pb['cnt']), 1) ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                style="width:80px;background:var(--body-bg);border-radius:3px;height:7px;overflow:hidden;">
                                                <div
                                                    style="height:100%;background:<?= $pb['color'] ?>;border-radius:3px;width:<?= $pb['pct'] ?>%;">
                                                </div>
                                            </div>
                                            <span style="font-size:.78rem;font-weight:700;">
                                                <?= $pb['pct'] ?>%
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ════════════════ KARGO ════════════════ -->
        <div class="report-pane" id="rpane-cargo" style="display:none;">
            <div class="row g-3 mb-3">
                <?php foreach ([
                    ['Toplam Kargo', '6.248', '#1b84ff', 'bi-box-seam'],
                    ['Teslim Edildi', '5.812', '#0e8045', 'bi-check-circle'],
                    ['Emanette', '289', '#e08b00', 'bi-archive'],
                    ['İptal / İade', '147', '#c03060', 'bi-x-circle'],
                ] as [$lbl, $val, $clr, $ico]): ?>
                    <div class="col-6 col-lg-3">
                        <div class="card h-100" style="padding:18px;border-top:3px solid <?= $clr ?>;">
                            <div class="card-sm-label">
                                <?= $lbl ?>
                            </div>
                            <div class="stat-value" style="font-size:1.7rem;margin-top:6px;color:<?= $clr ?>;">
                                <?= $val ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="card" style="padding:22px;">
                <div class="section-label mb-4"><i class="bi bi-funnel me-2" style="color:#1b84ff;"></i>Durum Dağılımı
                </div>
                <?php foreach ([
                    ['Teslim Edildi', 5812, 93, '#0e8045'],
                    ['Emanette', 289, 5, '#e08b00'],
                    ['İptal / İade', 147, 2, '#c03060'],
                ] as [$lbl, $cnt, $pct, $clr]): ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1" style="font-size:.8rem;font-weight:600;">
                            <span>
                                <?= $lbl ?>
                            </span>
                            <span>
                                <?= number_format($cnt) ?> kargo &nbsp;•&nbsp; %
                                <?= $pct ?>
                            </span>
                        </div>
                        <div style="background:var(--body-bg);border-radius:4px;height:10px;overflow:hidden;">
                            <div
                                style="height:100%;background:<?= $clr ?>;border-radius:4px;width:<?= $pct ?>%;transition:width .4s;">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div><!-- /padding -->

    <!-- Toast -->
    <div id="reportToast" style="position:fixed;bottom:24px;right:24px;z-index:9999;padding:12px 20px;
     border-radius:6px;font-size:.82rem;font-weight:600;background:#1b84ff;color:#fff;
     opacity:0;transition:opacity .3s;pointer-events:none;"></div>

    <style>
        .pricing-tab {
            padding: 7px 16px;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            background: var(--card-bg);
            color: var(--text-muted);
            transition: all .15s;
        }

        .pricing-tab:hover {
            background: var(--body-bg);
            color: var(--text-dark);
        }

        .pricing-tab.active {
            border-color: #1b84ff;
            background: #e8f1ff;
            color: #1b84ff;
        }

        body.dark .pricing-tab.active {
            background: rgba(27, 132, 255, .15);
        }

        @media print {

            .page-header button,
            #reportToast {
                display: none !important;
            }
        }
    </style>

    <script>
        function switchReport(key) {
            document.querySelectorAll('[id^="rtab-"]').forEach(function (t) { t.classList.remove('active'); });
            document.querySelectorAll('.report-pane').forEach(function (p) { p.style.display = 'none'; });
            document.getElementById('rtab-' + key).classList.add('active');
            document.getElementById('rpane-' + key).style.display = '';
        }
        function updateRange() {
            var v = document.getElementById('reportRange').value;
            window.location.href = '?page=reports&range=' + v;
        }
        function exportReport() { showToast('Rapor dışa aktarılıyor...', 'info'); }
        function showToast(msg, type) {
            var t = document.getElementById('reportToast');
            t.style.background = { success: '#0e8045', error: '#c03060', info: '#1b84ff' }[type] || '#1b84ff';
            t.textContent = msg;
            t.style.opacity = '1';
            setTimeout(function () { t.style.opacity = '0'; }, 3000);
        }
    </script>