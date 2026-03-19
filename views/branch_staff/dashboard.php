<?php
// ── Branch Staff Dashboard — Real Data ───────────────────────
$todayDate = date('d.m.Y');
$branchId  = (int) ($_SESSION['branch_id'] ?? 0);

$branchModel  = new BranchModel();
$shipModel    = new ShipmentModel();
$storageModel = new StorageModel();
$txModel      = new TransactionModel();
$tripModel    = new TripModel();

$branch     = $branchModel->find($branchId);
$branchName = $branch['name'] ?? 'Şube';
$shipStats  = $shipModel->getDashboardStats($branchId);
$storStats  = $storageModel->getStats($branchId);
$vaultSum   = $txModel->getDailySummary($branchId);

// Last 5 shipments
$lastShipments = $shipModel->getList($branchId, '', '', 5, 0);

// Today's trips
$todayTrips = $tripModel->getList($branchId, '', 5, 0);
?>
<main class="main-content">
<!-- ── Page Header ── -->
<div class="page-header">
    <div>
        <div class="page-title">Şube Dashboard</div>
        <div class="breadcrumb">
            <span style="color:var(--text-muted);"><?= htmlspecialchars($branchName) ?></span>
            <span class="sep">·</span>
            <span style="color:var(--text-muted);"><?= $todayDate ?></span>
        </div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="?page=shipment_create" class="btn-primary-sm d-flex align-items-center gap-1">
            <i class="bi bi-plus-lg"></i> Yeni Kargo Kabul
        </a>
        <a href="?page=storage_create" class="btn-outline-secondary-sm d-flex align-items-center gap-1">
            <i class="bi bi-bag-plus"></i> Emanet Kaydı
        </a>
    </div>
</div>

<!-- ── Body ── -->
<div style="padding:18px 26px 32px;">

    <!-- ══════════ ROW 1 — KPI STAT CARDS ══════════ -->
    <div class="row g-3 mb-3">

        <!-- Bugünkü Kargo Kabulü -->
        <div class="col-6 col-lg-3">
            <div class="card h-100" style="padding:18px;">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="card-sm-label">Bugün Kargo Kabulü</div>
                        <div class="stat-value" style="font-size:1.8rem;margin-top:4px;"><?= (int)($shipStats['total'] ?? 0) ?></div>
                        <span class="badge-change badge-up mt-2">&#8593; <?= (int)($shipStats['accepted'] ?? 0) ?> kabul edildi</span>
                    </div>
                    <div style="width:42px;height:42px;border-radius:8px;background:#e8f1ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-box-seam" style="color:#1b84ff;font-size:1.2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teslim Bekleyen -->
        <div class="col-6 col-lg-3">
            <div class="card h-100" style="padding:18px;">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="card-sm-label">Teslim Bekleyen</div>
                        <div class="stat-value" style="font-size:1.8rem;margin-top:4px;"><?= (int)($shipStats['in_storage'] ?? 0) + (int)($shipStats['in_transit'] ?? 0) ?></div>
                        <span class="badge-change badge-down mt-2">&#8595; <?= (int)($shipStats['in_transit'] ?? 0) ?> yolda</span>
                    </div>
                    <div style="width:42px;height:42px;border-radius:8px;background:#fff8ec;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-hourglass-split" style="color:#e08b00;font-size:1.2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emanetteki Kargo -->
        <div class="col-6 col-lg-3">
            <div class="card h-100" style="padding:18px;">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="card-sm-label">Emanetteki Eşya</div>
                        <div class="stat-value" style="font-size:1.8rem;margin-top:4px;"><?= (int)($storStats['total_stored'] ?? 0) ?></div>
                        <?php if ((int)($storStats['critical_count'] ?? 0) > 0): ?>
                        <div style="font-size:.74rem;color:#c03060;margin-top:6px;font-weight:600;"><?= (int)$storStats['critical_count'] ?> tanesi &gt;24 saat</div>
                        <?php endif; ?>
                    </div>
                    <div style="width:42px;height:42px;border-radius:8px;background:#f3e5f5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-archive" style="color:#8e24aa;font-size:1.2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Günlük Kasa -->
        <div class="col-6 col-lg-3">
            <div class="card h-100" style="padding:18px;">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="card-sm-label">Bugün Kasa Girişi</div>
                        <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">
                            <span style="font-size:1.1rem;font-weight:600;vertical-align:super;">₺</span><?= number_format((float)($vaultSum['total_in'] ?? 0), 0, ',', '.') ?>
                        </div>
                        <span class="badge-change badge-up mt-2"><?= (int)($vaultSum['tx_count'] ?? 0) ?> işlem</span>
                    </div>
                    <div style="width:42px;height:42px;border-radius:8px;background:#e7f9f0;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-safe2" style="color:#0e8045;font-size:1.2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /ROW 1 -->

    <!-- ══════════ ROW 2 — SON KARGOLAR + HIZLI İŞLEMLER ══════════ -->
    <div class="row g-3">

        <!-- Son Kargolar Tablosu -->
        <div class="col-12 col-lg-8">
            <div class="card" style="padding:20px;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="section-label">Son Kargo İşlemleri</div>
                    <a href="?page=shipment" style="font-size:.8rem;color:var(--accent-blue);text-decoration:none;">
                        Tümünü Gör <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Takip No</th>
                                <th>Alıcı</th>
                                <th>Varış</th>
                                <th>Ücret</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($lastShipments)): ?>
                            <tr><td colspan="5" style="text-align:center;color:var(--text-muted);font-size:.82rem;padding:20px;">Henüz kargo kaydı yok</td></tr>
                            <?php else: ?>
                            <?php
                            $statusLabels = [
                                'accepted' => ['Kabul', '#e8f1ff', '#1b84ff'],
                                'dispatched' => ['Sevkte', '#e8f1ff', '#1b84ff'],
                                'in_transit' => ['Yolda', '#e8f1ff', '#1b84ff'],
                                'at_branch' => ['Şubede', '#fff8ec', '#e08b00'],
                                'in_storage' => ['Emanette', '#fff8ec', '#e08b00'],
                                'delivered' => ['Teslim Edildi', '#e7f9f0', '#0e8045'],
                                'cancelled' => ['İptal', '#fff0f2', '#c03060'],
                                'returned' => ['İade', '#fff0f2', '#c03060'],
                            ];
                            foreach ($lastShipments as $s):
                                $st = $statusLabels[$s['status']] ?? ['Bilinmiyor', '#f5f5f5', '#666'];
                            ?>
                            <tr>
                                <td><span style="font-family:monospace;font-size:.8rem;color:var(--accent-blue);font-weight:600;"><?= htmlspecialchars($s['tracking_no']) ?></span></td>
                                <td>
                                    <div style="font-size:.82rem;font-weight:500;"><?= htmlspecialchars($s['receiver_name']) ?></div>
                                    <div style="font-size:.73rem;color:var(--text-muted);"><?= htmlspecialchars($s['receiver_phone']) ?></div>
                                </td>
                                <td><span style="font-size:.8rem;"><?= htmlspecialchars($s['dest_city'] ?? '-') ?></span></td>
                                <td><span style="font-weight:600;">₺<?= number_format((float)$s['total_fee'], 0, ',', '.') ?></span></td>
                                <td><span class="status-badge" style="background:<?= $st[1] ?>;color:<?= $st[2] ?>;"><?= $st[0] ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sağ Panel: Hızlı İşlemler + Aktif Seferler -->
        <div class="col-12 col-lg-4">

            <!-- Hızlı İşlemler -->
            <div class="card mb-3" style="padding:20px;">
                <div class="section-label mb-3">Hızlı İşlemler</div>
                <div class="d-flex flex-column gap-2">
                    <a href="?page=shipment_create" class="d-flex align-items-center gap-3"
                        style="padding:10px 12px;border:1px solid var(--border-color);border-radius:6px;text-decoration:none;color:var(--text-dark);transition:background .15s;"
                        onmouseover="this.style.background='var(--body-bg)'" onmouseout="this.style.background=''">
                        <div style="width:34px;height:34px;background:#e8f1ff;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-plus-square" style="color:#1b84ff;"></i>
                        </div>
                        <div>
                            <div style="font-size:.82rem;font-weight:600;">Yeni Kargo Kabul</div>
                            <div style="font-size:.73rem;color:var(--text-muted);">Gönderici / Alıcı bilgileri gir</div>
                        </div>
                    </a>
                    <a href="?page=delivery" class="d-flex align-items-center gap-3"
                        style="padding:10px 12px;border:1px solid var(--border-color);border-radius:6px;text-decoration:none;color:var(--text-dark);transition:background .15s;"
                        onmouseover="this.style.background='var(--body-bg)'" onmouseout="this.style.background=''">
                        <div style="width:34px;height:34px;background:#e7f9f0;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-check2-circle" style="color:#0e8045;"></i>
                        </div>
                        <div>
                            <div style="font-size:.82rem;font-weight:600;">Kargo Teslim</div>
                            <div style="font-size:.73rem;color:var(--text-muted);">Barkod okut, teslim et</div>
                        </div>
                    </a>
                    <a href="?page=storage_create" class="d-flex align-items-center gap-3"
                        style="padding:10px 12px;border:1px solid var(--border-color);border-radius:6px;text-decoration:none;color:var(--text-dark);transition:background .15s;"
                        onmouseover="this.style.background='var(--body-bg)'" onmouseout="this.style.background=''">
                        <div style="width:34px;height:34px;background:#f3e5f5;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-bag-plus" style="color:#8e24aa;"></i>
                        </div>
                        <div>
                            <div style="font-size:.82rem;font-weight:600;">Yeni Emanet Kaydı</div>
                            <div style="font-size:.73rem;color:var(--text-muted);">Yolcu bagajı, bağımsız emanet</div>
                        </div>
                    </a>
                    <a href="?page=dispatch" class="d-flex align-items-center gap-3"
                        style="padding:10px 12px;border:1px solid var(--border-color);border-radius:6px;text-decoration:none;color:var(--text-dark);transition:background .15s;"
                        onmouseover="this.style.background='var(--body-bg)'" onmouseout="this.style.background=''">
                        <div style="width:34px;height:34px;background:#fff8ec;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-send" style="color:#e08b00;"></i>
                        </div>
                        <div>
                            <div style="font-size:.82rem;font-weight:600;">Kargo Sevk</div>
                            <div style="font-size:.73rem;color:var(--text-muted);">Otobüs seç, manifesto bas</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Aktif Seferler -->
            <div class="card" style="padding:20px;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="section-label">Bugünkü Seferler</div>
                    <a href="?page=voyage" style="font-size:.8rem;color:var(--accent-blue);text-decoration:none;">Tümü</a>
                </div>
                <div class="d-flex flex-column gap-3">
                    <?php if (empty($todayTrips)): ?>
                    <div style="text-align:center;color:var(--text-muted);font-size:.82rem;padding:10px;">Henüz sefer kaydı yok</div>
                    <?php else: ?>
                    <?php
                    $tripStatusBadge = [
                        'planned' => ['Bekleniyor', '#f0f0f0', '#94a3b8'],
                        'departed' => ['Aktif', '#e8f1ff', '#1b84ff'],
                        'arrived' => ['Vardı', '#e7f9f0', '#0e8045'],
                        'completed' => ['Tamamlandı', '#e7f9f0', '#0e8045'],
                        'cancelled' => ['İptal', '#fff0f2', '#c03060'],
                    ];
                    foreach (array_slice($todayTrips, 0, 4) as $trip):
                        $tb = $tripStatusBadge[$trip['status']] ?? ['Bilinmiyor', '#f5f5f5', '#666'];
                    ?>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:36px;height:36px;background:<?= $trip['status'] === 'departed' ? '#e8f1ff' : '#f5f5f5' ?>;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-bus-front" style="color:<?= $trip['status'] === 'departed' ? '#1b84ff' : '#94a3b8' ?>;font-size:.95rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div style="font-size:.81rem;font-weight:600;"><?= htmlspecialchars($trip['plate_no']) ?> → <?= htmlspecialchars($trip['dest_city'] ?? '-') ?></div>
                            <div style="font-size:.73rem;color:var(--text-muted);"><?= htmlspecialchars($trip['company_name'] ?? '-') ?> · <?= $trip['departure_time'] ? date('H:i', strtotime($trip['departure_time'])) : '-' ?></div>
                        </div>
                        <span class="status-badge" style="background:<?= $tb[1] ?>;color:<?= $tb[2] ?>;"><?= $tb[0] ?></span>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div><!-- /ROW 2 -->

</div><!-- /body -->
</main>