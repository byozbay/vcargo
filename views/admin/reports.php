<?php
// Mock summary data
$reportData = [
    'total_revenue' => 847320,
    'total_shipments' => 6248,
    'total_storage' => 1140,
    'total_companies' => 8,
    'avg_shipment_val' => 135.6,
    'cod_collected' => 128400,
];

$monthlyRevenue = [
    ['ay' => 'Ağu', 'ciro' => 58200, 'kargo' => 412],
    ['ay' => 'Eyl', 'ciro' => 63400, 'kargo' => 448],
    ['ay' => 'Eki', 'ciro' => 71800, 'kargo' => 512],
    ['ay' => 'Kas', 'ciro' => 68900, 'kargo' => 496],
    ['ay' => 'Ara', 'ciro' => 82300, 'kargo' => 584],
    ['ay' => 'Oca', 'ciro' => 74100, 'kargo' => 538],
    ['ay' => 'Şub', 'ciro' => 91200, 'kargo' => 667],
];

$topBranches = [
    ['name' => 'İstanbul - Esenler', 'city' => 'İstanbul', 'shipments' => 1842, 'revenue' => 128400, 'pct' => 100],
    ['name' => 'Ankara - AŞTİ', 'city' => 'Ankara', 'shipments' => 1418, 'revenue' => 98700, 'pct' => 77],
    ['name' => 'İzmir - UAŞM', 'city' => 'İzmir', 'shipments' => 978, 'revenue' => 68200, 'pct' => 53],
    ['name' => 'Bursa Otogarı', 'city' => 'Bursa', 'shipments' => 714, 'revenue' => 49800, 'pct' => 38],
    ['name' => 'Antalya Otogarı', 'city' => 'Antalya', 'shipments' => 561, 'revenue' => 39100, 'pct' => 30],
];

$topCompanies = [
    ['name' => 'Metro Turizm', 'trips' => 184, 'cargo' => 1840, 'commission' => 13800],
    ['name' => 'Pamukkale Turizm', 'trips' => 141, 'cargo' => 1290, 'commission' => 8385],
    ['name' => 'Kamil Koç', 'trips' => 118, 'cargo' => 1060, 'commission' => 7420],
    ['name' => 'Uludağ Turizm', 'trips' => 96, 'cargo' => 860, 'commission' => 5160],
    ['name' => 'Varan Turizm', 'trips' => 74, 'cargo' => 590, 'commission' => 4720],
];

$paymentBreakdown = [
    ['type' => 'Nakit', 'pct' => 54, 'amount' => 457753, 'color' => '#1b84ff'],
    ['type' => 'Kredi Kartı', 'pct' => 31, 'amount' => 262669, 'color' => '#0e8045'],
    ['type' => 'Cari Hesap', 'pct' => 15, 'amount' => 126898, 'color' => '#e08b00'],
];

$maxRevenue = max(array_column($monthlyRevenue, 'ciro'));
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
                    onchange="updateRange()">
                    <option value="7">Son 7 Gün</option>
                    <option value="30">Son 30 Gün</option>
                    <option selected value="90">Son 3 Ay</option>
                    <option value="180">Son 6 Ay</option>
                    <option value="365">Bu Yıl</option>
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
                                        <?= number_format(round($reportData['total_shipments'] * $pb['pct'] / 100)) ?>
                                    </td>
                                    <td style="font-size:.82rem;font-weight:700;">₺
                                        <?= number_format($pb['amount']) ?>
                                    </td>
                                    <td style="font-size:.82rem;">₺
                                        <?= number_format($pb['amount'] / max(1, round($reportData['total_shipments'] * $pb['pct'] / 100)), 1) ?>
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
            showToast('Rapor güncelleniyor: Son ' + v + ' gün...', 'info');
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