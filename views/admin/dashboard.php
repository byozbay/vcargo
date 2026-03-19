<?php
// ── Dashboard Real Data ──────────────────────────────────────
$todayDate = date('d.m.Y');

$branchModel  = new BranchModel();
$shipModel    = new ShipmentModel();
$storageModel = new StorageModel();
$txModel      = new TransactionModel();

// KPI Cards
$totalBranches = $branchModel->count(['is_active' => 1, 'status' => 'active']);
$pendingBranches = $branchModel->count(['status' => 'pending']);
$shipStats    = $shipModel->getDashboardStats(); // total, in_transit, in_storage, delivered, accepted
$storStats    = $storageModel->getStats();
$vaultSummary = $txModel->getDailySummary((int)($_SESSION['branch_id'] ?? 0));

// Branch performance
$allBranches = $branchModel->getAllWithCity();

// Recent transactions (last 10)
$recentTx = $txModel->getVaultTransactions(0);
if (count($recentTx) > 8) $recentTx = array_slice($recentTx, 0, 8);

// Recent users
$userModel  = new UserModel();
$recentUsers = $userModel->getList('', '', 0);
if (count($recentUsers) > 4) $recentUsers = array_slice($recentUsers, 0, 4);

// Bus companies
$base = new BaseModel();
$busCompanies = $base->query("SELECT bc.*, COUNT(t.trip_id) as trip_count 
    FROM bus_companies bc 
    LEFT JOIN trips t ON bc.company_id = t.company_id AND t.is_active = 1 
    WHERE bc.is_active = 1 
    GROUP BY bc.company_id 
    ORDER BY trip_count DESC 
    LIMIT 5");
$maxTrips = max(array_column($busCompanies, 'trip_count') ?: [1]);

// Monthly revenue
$monthlyRevenue = $base->query(
    "SELECT COALESCE(SUM(total_fee), 0) AS total FROM shipments WHERE is_active = 1 AND payment_status = 'paid' AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())"
);
$monthlyRevTotal = (float) ($monthlyRevenue[0]['total'] ?? 0);

// Accounts over limit
$accountModel = new AccountModel();
$overLimitAccounts = $accountModel->getOverLimit();
$totalOpenBalance = $base->query("SELECT COALESCE(SUM(balance), 0) as total FROM accounts WHERE is_active = 1 AND balance > 0");
$openBalance = (float) ($totalOpenBalance[0]['total'] ?? 0);

// Trip stats
$tripStats = $base->query(
    "SELECT 
        SUM(CASE WHEN status IN ('departed') AND DATE(departure_time) = CURDATE() THEN 1 ELSE 0 END) AS active_today,
        SUM(CASE WHEN status = 'completed' AND DATE(updated_at) = CURDATE() THEN 1 ELSE 0 END) AS completed_today,
        SUM(CASE WHEN status = 'planned' THEN 1 ELSE 0 END) AS planned,
        SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled
    FROM trips WHERE is_active = 1"
);
$ts = $tripStats[0] ?? ['active_today' => 0, 'completed_today' => 0, 'planned' => 0, 'cancelled' => 0];
?>
<main class="main-content">

<!-- ── Page Header ── -->
<div class="page-header">
    <div>
        <div class="page-title">Sistem Genel Görünümü</div>
        <div class="breadcrumb">
            <span style="color:var(--text-muted);"><?= htmlspecialchars($_SESSION['user_role'] === 'admin' ? 'Süper Admin' : ($_SESSION['full_name'] ?? 'Kullanıcı')) ?></span>
            <span class="sep">·</span>
            <span style="color:var(--text-muted);"><?= $todayDate ?></span>
        </div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <button onclick="window.print()" class="btn-outline-secondary-sm d-flex align-items-center gap-1">
            <i class="bi bi-printer"></i> Rapor Yazdır
        </button>
        <a href="?page=reports" class="btn-primary-sm d-flex align-items-center gap-1">
            <i class="bi bi-bar-chart-line"></i> Detaylı Raporlar
        </a>
    </div>
</div>

<div style="padding:18px 26px 40px;">

    <!-- ══ ROW 1 — SİSTEM KPI ══ -->
    <div class="row g-3 mb-3">

        <!-- Toplam Şube -->
        <div class="col-6 col-lg-3">
            <div class="card h-100" style="padding:18px;">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="card-sm-label">Aktif Şube</div>
                        <div class="stat-value" style="font-size:1.8rem;margin-top:4px;"><?= $totalBranches ?></div>
                        <?php if ($pendingBranches > 0): ?>
                        <span class="badge-change badge-down mt-2"><?= $pendingBranches ?> onay bekliyor</span>
                        <?php endif; ?>
                    </div>
                    <div style="width:42px;height:42px;border-radius:8px;background:#e8f1ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-shop" style="color:#1b84ff;font-size:1.2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bugünkü Toplam Kargo -->
        <div class="col-6 col-lg-3">
            <div class="card h-100" style="padding:18px;">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="card-sm-label">Bugün Kargo (Sistem)</div>
                        <div class="stat-value" style="font-size:1.8rem;margin-top:4px;"><?= (int)($shipStats['total'] ?? 0) ?></div>
                        <span class="badge-change badge-up mt-2"><?= (int)($shipStats['delivered'] ?? 0) ?> teslim edildi</span>
                    </div>
                    <div style="width:42px;height:42px;border-radius:8px;background:#e7f9f0;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-box-seam" style="color:#0e8045;font-size:1.2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aylık Ciro -->
        <div class="col-6 col-lg-3">
            <div class="card h-100" style="padding:18px;">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="card-sm-label">Aylık Ciro</div>
                        <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">₺<?= number_format($monthlyRevTotal, 0, ',', '.') ?></div>
                    </div>
                    <div style="width:42px;height:42px;border-radius:8px;background:#fff8ec;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-cash-stack" style="color:#e08b00;font-size:1.2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Açık Cari Bakiye -->
        <div class="col-6 col-lg-3">
            <div class="card h-100" style="padding:18px;">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="card-sm-label">Açık Cari Bakiye</div>
                        <div class="stat-value" style="font-size:1.8rem;margin-top:4px;color:#c03060;">₺<?= number_format($openBalance, 0, ',', '.') ?></div>
                        <?php if (count($overLimitAccounts) > 0): ?>
                        <span class="badge-change badge-down mt-2">&#8595; <?= count($overLimitAccounts) ?> limit aşımı</span>
                        <?php endif; ?>
                    </div>
                    <div style="width:42px;height:42px;border-radius:8px;background:#fff0f2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-exclamation-triangle" style="color:#c03060;font-size:1.2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /row 1 -->

    <!-- ══ ROW 2 — Sefer Özeti + Emanet Durumu ══ -->
    <div class="row g-3 mb-3">

        <!-- Sefer Mini KPI'ları -->
        <div class="col-12 col-lg-4">
            <div class="card" style="padding:20px;height:100%;">
                <div class="section-label mb-3">Sefer Özeti</div>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center justify-content-between py-2" style="border-bottom:1px solid var(--border-color);">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;background:#e8f1ff;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-bus-front" style="color:#1b84ff;font-size:.85rem;"></i>
                            </div>
                            <div>
                                <div style="font-size:.8rem;font-weight:600;">Bugün Aktif Sefer</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Yola çıkmış</div>
                            </div>
                        </div>
                        <div style="font-size:1.4rem;font-weight:800;color:#1b84ff;"><?= (int)($ts['active_today'] ?? 0) ?></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between py-2" style="border-bottom:1px solid var(--border-color);">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;background:#e7f9f0;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-check-circle" style="color:#0e8045;font-size:.85rem;"></i>
                            </div>
                            <div>
                                <div style="font-size:.8rem;font-weight:600;">Tamamlanan</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Bugün</div>
                            </div>
                        </div>
                        <div style="font-size:1.4rem;font-weight:800;color:#0e8045;"><?= (int)($ts['completed_today'] ?? 0) ?></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between py-2" style="border-bottom:1px solid var(--border-color);">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;background:#fff8ec;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-clock-history" style="color:#e08b00;font-size:.85rem;"></i>
                            </div>
                            <div>
                                <div style="font-size:.8rem;font-weight:600;">Planlanan</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Bekliyor</div>
                            </div>
                        </div>
                        <div style="font-size:1.4rem;font-weight:800;color:#e08b00;"><?= (int)($ts['planned'] ?? 0) ?></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between py-2">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;background:#fff0f2;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-x-circle" style="color:#c03060;font-size:.85rem;"></i>
                            </div>
                            <div>
                                <div style="font-size:.8rem;font-weight:600;">İptal</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Toplam</div>
                            </div>
                        </div>
                        <div style="font-size:1.4rem;font-weight:800;color:#c03060;"><?= (int)($ts['cancelled'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emanet Durumu -->
        <div class="col-12 col-lg-4">
            <div class="card" style="padding:20px;height:100%;">
                <div class="section-label mb-3">Emanet Durumu</div>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center justify-content-between py-2" style="border-bottom:1px solid var(--border-color);">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;background:#e8f1ff;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-archive" style="color:#1b84ff;font-size:.85rem;"></i>
                            </div>
                            <div>
                                <div style="font-size:.8rem;font-weight:600;">Depodaki Eşya</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Aktif kayıt</div>
                            </div>
                        </div>
                        <div style="font-size:1.4rem;font-weight:800;color:#1b84ff;"><?= (int)($storStats['total_stored'] ?? 0) ?></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between py-2" style="border-bottom:1px solid var(--border-color);">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;background:#fff8ec;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-hourglass-split" style="color:#e08b00;font-size:.85rem;"></i>
                            </div>
                            <div>
                                <div style="font-size:.8rem;font-weight:600;">Ücretli Sürede</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Ücretsiz süre aşılmış</div>
                            </div>
                        </div>
                        <div style="font-size:1.4rem;font-weight:800;color:#e08b00;"><?= (int)($storStats['paid_count'] ?? 0) ?></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between py-2">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;background:#fff0f2;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-exclamation-octagon" style="color:#c03060;font-size:.85rem;"></i>
                            </div>
                            <div>
                                <div style="font-size:.8rem;font-weight:600;">Kritik (24+ saat)</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Hemen teslim et</div>
                            </div>
                        </div>
                        <div style="font-size:1.4rem;font-weight:800;color:#c03060;"><?= (int)($storStats['critical_count'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kasa Günlük Özet -->
        <div class="col-12 col-lg-4">
            <div class="card" style="padding:20px;height:100%;">
                <div class="section-label mb-3">Bugünkü Kasa</div>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center justify-content-between py-2" style="border-bottom:1px solid var(--border-color);">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;background:#e7f9f0;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-arrow-down-left" style="color:#0e8045;font-size:.85rem;"></i>
                            </div>
                            <div>
                                <div style="font-size:.8rem;font-weight:600;">Toplam Giriş</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Nakit + Kart</div>
                            </div>
                        </div>
                        <div style="font-size:1.2rem;font-weight:800;color:#0e8045;">₺<?= number_format((float)($vaultSummary['total_in'] ?? 0), 2, ',', '.') ?></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between py-2" style="border-bottom:1px solid var(--border-color);">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;background:#fff0f2;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-arrow-up-right" style="color:#c03060;font-size:.85rem;"></i>
                            </div>
                            <div>
                                <div style="font-size:.8rem;font-weight:600;">Toplam Çıkış</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Giderler</div>
                            </div>
                        </div>
                        <div style="font-size:1.2rem;font-weight:800;color:#c03060;">₺<?= number_format((float)($vaultSummary['total_out'] ?? 0), 2, ',', '.') ?></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between py-2">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;background:#e8f1ff;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-wallet2" style="color:#1b84ff;font-size:.85rem;"></i>
                            </div>
                            <div>
                                <div style="font-size:.8rem;font-weight:600;">Net Kasa</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Bugünkü bakiye</div>
                            </div>
                        </div>
                        <?php $netVault = (float)($vaultSummary['total_in'] ?? 0) - (float)($vaultSummary['total_out'] ?? 0); ?>
                        <div style="font-size:1.2rem;font-weight:800;color:<?= $netVault >= 0 ? '#0e8045' : '#c03060' ?>;">₺<?= number_format($netVault, 2, ',', '.') ?></div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /row 2 -->

    <!-- ══ ROW 3 — Şube Performans + Otobüs Firmaları ══ -->
    <div class="row g-3 mb-3">

        <!-- Şube Performans Tablosu -->
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div>
                        <div class="section-label">Şube Listesi</div>
                        <div class="section-sub">Aktif şubeler — durum ve ayarlar</div>
                    </div>
                    <a href="?page=branches" class="btn-outline-secondary-sm" style="font-size:.76rem;">Tümünü Gör</a>
                </div>
                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Şube</th>
                                <th>Şehir</th>
                                <th>Tür</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($allBranches, 0, 6) as $i => $b):
                                $typeBadge = $b['type'] === 'CORPORATE'
                                    ? '<span class="status-badge" style="background:#e8f1ff;color:#1b84ff;font-size:.68rem;">Merkez</span>'
                                    : '<span class="status-badge" style="background:#f3e5f5;color:#8e24aa;font-size:.68rem;">Bayi</span>';
                                $statusColors = ['active' => ['#e7f9f0','#0e8045','Aktif'], 'pending' => ['#fff8ec','#e08b00','Bekliyor'], 'suspended' => ['#fff0f2','#c03060','Askıda']];
                                $sc = $statusColors[$b['status']] ?? ['#f5f5f5','#666','Bilinmiyor'];
                            ?>
                                <tr>
                                    <td style="font-size:.78rem;font-weight:700;color:var(--text-muted);"><?= $i + 1 ?></td>
                                    <td style="font-size:.82rem;font-weight:600;"><?= htmlspecialchars($b['name']) ?></td>
                                    <td style="font-size:.8rem;"><?= htmlspecialchars($b['city_name'] ?? '-') ?></td>
                                    <td><?= $typeBadge ?></td>
                                    <td><span class="status-badge" style="background:<?= $sc[0] ?>;color:<?= $sc[1] ?>;font-size:.68rem;"><?= $sc[2] ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- En Aktif Otobüs Firmaları -->
        <div class="col-12 col-lg-5">
            <div class="card" style="padding:20px;height:100%;">
                <div class="section-label mb-3">Otobüs Firmaları</div>
                <div class="d-flex flex-column gap-2">
                    <?php
                    $firmColors = ['#1b84ff', '#0e8045', '#8e24aa', '#e08b00', '#c03060'];
                    foreach ($busCompanies as $i => $f):
                        $pct = $maxTrips > 0 ? (int)($f['trip_count'] / $maxTrips * 100) : 0;
                    ?>
                    <div>
                        <div class="d-flex justify-content-between mb-1" style="font-size:.77rem;">
                            <span><?= htmlspecialchars($f['name']) ?></span>
                            <span style="font-weight:600;"><?= $f['trip_count'] ?> sefer · %<?= $f['commission_rate'] ?> komisyon</span>
                        </div>
                        <div style="height:5px;background:var(--border-color);border-radius:3px;overflow:hidden;">
                            <div style="height:5px;width:<?= $pct ?>%;background:<?= $firmColors[$i % 5] ?>;border-radius:3px;"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div><!-- /row 3 -->

    <!-- ══ ROW 4 — Son İşlemler + Uyarılar & Kullanıcılar ══ -->
    <div class="row g-3">

        <!-- Son İşlemler -->
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div>
                        <div class="section-label">Son Finansal İşlemler</div>
                        <div class="section-sub">Tüm şubeler — gerçek zamanlı</div>
                    </div>
                    <a href="?page=vault" class="btn-outline-secondary-sm" style="font-size:.76rem;">Tümü</a>
                </div>
                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Saat</th>
                                <th>Açıklama</th>
                                <th>Yöntem</th>
                                <th>Tip</th>
                                <th>Tutar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentTx)): ?>
                            <tr><td colspan="5" style="text-align:center;color:var(--text-muted);font-size:.82rem;padding:20px;">Henüz işlem kaydı yok</td></tr>
                            <?php else: ?>
                            <?php foreach ($recentTx as $tx):
                                $typeHtml = $tx['type'] === 'IN'
                                    ? '<span class="status-badge" style="background:#e7f9f0;color:#0e8045;font-size:.7rem;"><i class="bi bi-arrow-down-left me-1"></i>Gelir</span>'
                                    : '<span class="status-badge" style="background:#fff0f2;color:#c03060;font-size:.7rem;"><i class="bi bi-arrow-up-right me-1"></i>Gider</span>';
                                $methodHtml = match($tx['method'] ?? '') {
                                    'CASH' => '<span class="status-badge" style="background:#fff8ec;color:#e08b00;font-size:.7rem;">Nakit</span>',
                                    'CARD' => '<span class="status-badge" style="background:#e8f1ff;color:#1b84ff;font-size:.7rem;">Kart</span>',
                                    default => '<span class="status-badge" style="background:#f5f5f5;color:#666;font-size:.7rem;">' . htmlspecialchars($tx['method'] ?? '-') . '</span>',
                                };
                                $amtColor = $tx['type'] === 'IN' ? '#0e8045' : '#c03060';
                                $amtSign  = $tx['type'] === 'IN' ? '+' : '−';
                            ?>
                                <tr>
                                    <td style="font-size:.77rem;color:var(--text-muted);"><?= date('H:i', strtotime($tx['created_at'])) ?></td>
                                    <td style="font-size:.8rem;"><?= htmlspecialchars($tx['description'] ?? '-') ?></td>
                                    <td><?= $methodHtml ?></td>
                                    <td><?= $typeHtml ?></td>
                                    <td style="font-weight:700;font-size:.84rem;color:<?= $amtColor ?>;"><?= $amtSign ?>₺<?= number_format((float)$tx['amount'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sağ kolon: Uyarılar + Kullanıcılar -->
        <div class="col-12 col-lg-5">

            <!-- Sistem Uyarıları -->
            <div class="card mb-3" style="padding:20px;">
                <div class="section-label mb-3">⚠️ Sistem Uyarıları</div>
                <div class="d-flex flex-column gap-2">
                    <?php if (empty($overLimitAccounts) && (int)($storStats['critical_count'] ?? 0) === 0 && $pendingBranches === 0): ?>
                    <div style="padding:10px 12px;background:#e7f9f0;border-radius:6px;font-size:.78rem;border-left:3px solid #0e8045;">
                        <div style="font-weight:700;color:#0e8045;">Herşey Yolunda</div>
                        <div style="color:var(--text-muted);margin-top:2px;">Şu anda aktif uyarı yok.</div>
                    </div>
                    <?php else: ?>
                        <?php foreach (array_slice($overLimitAccounts, 0, 2) as $ol): ?>
                        <div style="padding:10px 12px;background:#fff0f2;border-radius:6px;font-size:.78rem;border-left:3px solid #c03060;">
                            <div style="font-weight:700;color:#c03060;">Cari Limit Aşımı</div>
                            <div style="color:var(--text-muted);margin-top:2px;"><?= htmlspecialchars($ol['name']) ?> — ₺<?= number_format((float)$ol['balance'], 0, ',', '.') ?> / ₺<?= number_format((float)$ol['credit_limit'], 0, ',', '.') ?></div>
                        </div>
                        <?php endforeach; ?>
                        <?php if ((int)($storStats['critical_count'] ?? 0) > 0): ?>
                        <div style="padding:10px 12px;background:#fff8ec;border-radius:6px;font-size:.78rem;border-left:3px solid #e08b00;">
                            <div style="font-weight:700;color:#e08b00;">Emanet Süresi Kritik</div>
                            <div style="color:var(--text-muted);margin-top:2px;"><?= (int)$storStats['critical_count'] ?> eşya 24+ saat depoda</div>
                        </div>
                        <?php endif; ?>
                        <?php if ($pendingBranches > 0): ?>
                        <div style="padding:10px 12px;background:#e8f1ff;border-radius:6px;font-size:.78rem;border-left:3px solid #1b84ff;">
                            <div style="font-weight:700;color:#1b84ff;">Yeni Şube Başvurusu</div>
                            <div style="color:var(--text-muted);margin-top:2px;"><?= $pendingBranches ?> şube onay bekliyor</div>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Son Kullanıcılar -->
            <div class="card" style="padding:20px;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="section-label">Son Kullanıcılar</div>
                    <a href="?page=users" style="font-size:.75rem;color:var(--accent-blue);">Tümü</a>
                </div>
                <div class="d-flex flex-column gap-3">
                    <?php
                    $initColors = ['#1b84ff', '#0e8045', '#8e24aa', '#e08b00'];
                    $roleLabels = [
                        'admin' => 'Süper Admin', 'accountant' => 'Muhasebe',
                        'region_manager' => 'Bölge Müdürü', 'branch_manager' => 'Şube Müdürü',
                        'branch_staff' => 'Personel', 'courier' => 'Kurye',
                    ];
                    foreach ($recentUsers as $i => $u):
                    ?>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;border-radius:50%;background:<?= $initColors[$i % 4] ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <span style="color:#fff;font-size:.72rem;font-weight:700;"><?= mb_strtoupper(mb_substr($u['full_name'] ?? 'U', 0, 1)) ?></span>
                            </div>
                            <div style="flex:1;overflow:hidden;">
                                <div style="font-size:.8rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($u['full_name'] ?? $u['username']) ?></div>
                                <div style="font-size:.71rem;color:var(--text-muted);"><?= $roleLabels[$u['role']] ?? $u['role'] ?> · <?= htmlspecialchars($u['branch_name'] ?? '-') ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

    </div><!-- /row 4 -->

</div><!-- /body -->

</main>
