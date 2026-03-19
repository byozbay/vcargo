<?php
$todayDate = date('d.m.Y');
?>
<main class="main-content">

<!-- ── Page Header ── -->
<div class="page-header">
    <div>
        <div class="page-title">Sistem Genel Görünümü</div>
        <div class="breadcrumb">
            <span style="color:var(--text-muted);">Süper Admin</span>
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
                        <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">18</div>
                        <span class="badge-change badge-up mt-2">&#8593; 3 yeni bu ay</span>
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
                        <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">384</div>
                        <span class="badge-change badge-up mt-2">&#8593; 8% dün'e göre</span>
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
                        <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">₺284K</div>
                        <span class="badge-change badge-up mt-2">&#8593; 14% geçen aya göre</span>
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
                        <div class="stat-value" style="font-size:1.8rem;margin-top:4px;color:#c03060;">₺68.4K</div>
                        <span class="badge-change badge-down mt-2">&#8595; 3 limit aşımı var</span>
                    </div>
                    <div style="width:42px;height:42px;border-radius:8px;background:#fff0f2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-exclamation-triangle" style="color:#c03060;font-size:1.2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /row 1 -->

    <!-- ══ ROW 2 — Haftalık Gelir Grafiği + Sefer Özeti ══ -->
    <div class="row g-3 mb-3">

        <!-- Alan Grafiği — Günlük Gelir -->
        <div class="col-12 col-lg-8">
            <div class="card" style="padding:20px;height:100%;">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div>
                        <div class="section-label">Günlük Kargo Geliri</div>
                        <div class="section-sub">Son 14 gün — tüm şubeler</div>
                    </div>
                    <div class="d-flex gap-2" style="font-size:.75rem;">
                        <span style="display:inline-flex;align-items:center;gap:4px;"><span style="width:10px;height:10px;background:#1b84ff;border-radius:2px;display:inline-block;"></span>Nakit</span>
                        <span style="display:inline-flex;align-items:center;gap:4px;"><span style="width:10px;height:10px;background:#0e8045;border-radius:2px;display:inline-block;"></span>Kart</span>
                        <span style="display:inline-flex;align-items:center;gap:4px;"><span style="width:10px;height:10px;background:#e08b00;border-radius:2px;display:inline-block;"></span>Cari</span>
                    </div>
                </div>
                <canvas id="revenueChart" height="110"></canvas>
            </div>
        </div>

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
                        <div style="font-size:1.4rem;font-weight:800;color:#1b84ff;">24</div>
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
                        <div style="font-size:1.4rem;font-weight:800;color:#0e8045;">11</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between py-2" style="border-bottom:1px solid var(--border-color);">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;background:#fff8ec;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-clock-history" style="color:#e08b00;font-size:.85rem;"></i>
                            </div>
                            <div>
                                <div style="font-size:.8rem;font-weight:600;">Planlanan</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Yarın</div>
                            </div>
                        </div>
                        <div style="font-size:1.4rem;font-weight:800;color:#e08b00;">18</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between py-2">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;background:#fff0f2;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-x-circle" style="color:#c03060;font-size:.85rem;"></i>
                            </div>
                            <div>
                                <div style="font-size:.8rem;font-weight:600;">İptal</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">Bu hafta</div>
                            </div>
                        </div>
                        <div style="font-size:1.4rem;font-weight:800;color:#c03060;">2</div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /row 2 -->

    <!-- ══ ROW 3 — Şube Performans + Kargo Dağılımı ══ -->
    <div class="row g-3 mb-3">

        <!-- Şube Performans Tablosu -->
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div>
                        <div class="section-label">Şube Performansı</div>
                        <div class="section-sub">Bu ay — kargo adedi & gelir sıralaması</div>
                    </div>
                    <a href="?page=branches" class="btn-outline-secondary-sm" style="font-size:.76rem;">Tümünü Gör</a>
                </div>
                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Şube</th>
                                <th>Tür</th>
                                <th>Kargo</th>
                                <th>Ciro</th>
                                <th>Hedef %</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $branches = [
                                ['rank' => 1, 'name' => 'İstanbul Otogar', 'type' => 'CORPORATE', 'cargo' => 842, 'revenue' => 48600, 'target' => 96],
                                ['rank' => 2, 'name' => 'Ankara Şehirler', 'type' => 'CORPORATE', 'cargo' => 614, 'revenue' => 36200, 'target' => 84],
                                ['rank' => 3, 'name' => 'İzmir Ege', 'type' => 'FRANCHISE', 'cargo' => 501, 'revenue' => 28900, 'target' => 78],
                                ['rank' => 4, 'name' => 'Bursa Osmangazi', 'type' => 'FRANCHISE', 'cargo' => 388, 'revenue' => 22100, 'target' => 71],
                                ['rank' => 5, 'name' => 'Antalya Liman', 'type' => 'FRANCHISE', 'cargo' => 312, 'revenue' => 18400, 'target' => 62],
                                ['rank' => 6, 'name' => 'Konya Merkez', 'type' => 'FRANCHISE', 'cargo' => 274, 'revenue' => 15600, 'target' => 55],
                            ];
                            foreach ($branches as $b):
                                $barColor = $b['target'] >= 80 ? '#0e8045' : ($b['target'] >= 60 ? '#e08b00' : '#c03060');
                                $typeBadge = $b['type'] === 'CORPORATE'
                                    ? '<span class="status-badge" style="background:#e8f1ff;color:#1b84ff;font-size:.68rem;">Merkez</span>'
                                    : '<span class="status-badge" style="background:#f3e5f5;color:#8e24aa;font-size:.68rem;">Bayi</span>';
                                ?>
                                <tr>
                                    <td>
                                        <span style="font-size:.78rem;font-weight:700;color:<?= $b['rank'] <= 3 ? '#e08b00' : 'var(--text-muted)' ?>;">
                                            <?= $b['rank'] <= 3 ? '🏅' : '#' . $b['rank'] ?>
                                        </span>
                                    </td>
                                    <td style="font-size:.82rem;font-weight:600;"><?= $b['name'] ?></td>
                                    <td><?= $typeBadge ?></td>
                                    <td style="font-size:.82rem;font-weight:600;"><?= number_format($b['cargo']) ?></td>
                                    <td style="font-size:.82rem;font-weight:700;color:#0e8045;">₺<?= number_format($b['revenue']) ?></td>
                                    <td style="min-width:100px;">
                                        <div style="font-size:.72rem;color:var(--text-muted);margin-bottom:3px;"><?= $b['target'] ?>%</div>
                                        <div style="height:5px;background:var(--border-color);border-radius:3px;overflow:hidden;">
                                            <div style="height:5px;width:<?= $b['target'] ?>%;background:<?= $barColor ?>;border-radius:3px;"></div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kargo Durum Dağılımı + En çok kullanan firmaları -->
        <div class="col-12 col-lg-5">
            <div class="row g-3 h-100">

                <!-- Donut -->
                <div class="col-12">
                    <div class="card" style="padding:20px;">
                        <div class="section-label mb-3">Kargo Durum Dağılımı</div>
                        <div class="row g-0 align-items-center">
                            <div class="col-5 d-flex justify-content-center">
                                <canvas id="statusDonut" width="130" height="130"></canvas>
                            </div>
                            <div class="col-7">
                                <div class="d-flex flex-column gap-2" style="font-size:.77rem;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><span style="display:inline-block;width:8px;height:8px;background:#0e8045;border-radius:2px;margin-right:5px;"></span>Teslim Edildi</span>
                                        <span style="font-weight:700;">58%</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><span style="display:inline-block;width:8px;height:8px;background:#1b84ff;border-radius:2px;margin-right:5px;"></span>Yolda</span>
                                        <span style="font-weight:700;">22%</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><span style="display:inline-block;width:8px;height:8px;background:#e08b00;border-radius:2px;margin-right:5px;"></span>Emanette</span>
                                        <span style="font-weight:700;">14%</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><span style="display:inline-block;width:8px;height:8px;background:#c03060;border-radius:2px;margin-right:5px;"></span>Bekliyor</span>
                                        <span style="font-weight:700;">6%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- En Aktif Otobüs Firmaları -->
                <div class="col-12">
                    <div class="card" style="padding:20px;">
                        <div class="section-label mb-3">En Aktif Otobüs Firmaları</div>
                        <div class="d-flex flex-column gap-2">
                            <?php
                            $firms = [
                                ['name' => 'Metro Turizm', 'trips' => 184, 'pct' => 92],
                                ['name' => 'Pamukkale', 'trips' => 141, 'pct' => 71],
                                ['name' => 'Kamil Koç', 'trips' => 118, 'pct' => 59],
                                ['name' => 'Uludağ Turizm', 'trips' => 96, 'pct' => 48],
                                ['name' => 'Varan Turizm', 'trips' => 74, 'pct' => 37],
                            ];
                            $firmColors = ['#1b84ff', '#0e8045', '#8e24aa', '#e08b00', '#c03060'];
                            foreach ($firms as $i => $f):
                                ?>
                                <div>
                                    <div class="d-flex justify-content-between mb-1" style="font-size:.77rem;">
                                        <span><?= $f['name'] ?></span>
                                        <span style="font-weight:600;"><?= $f['trips'] ?> sefer</span>
                                    </div>
                                    <div style="height:5px;background:var(--border-color);border-radius:3px;overflow:hidden;">
                                        <div style="height:5px;width:<?= $f['pct'] ?>%;background:<?= $firmColors[$i] ?>;border-radius:3px;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div><!-- /row 3 -->

    <!-- ══ ROW 4 — Son Sistem İşlemleri + Aktif Kullanıcılar ══ -->
    <div class="row g-3">

        <!-- Son İşlemler -->
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div>
                        <div class="section-label">Son Finansal İşlemler</div>
                        <div class="section-sub">Tüm şubeler — gerçek zamanlı</div>
                    </div>
                    <a href="?page=transactions" class="btn-outline-secondary-sm" style="font-size:.76rem;">Tümü</a>
                </div>
                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Saat</th>
                                <th>Şube</th>
                                <th>Açıklama</th>
                                <th>Yöntem</th>
                                <th>Tip</th>
                                <th>Tutar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $txs = [
                                ['time' => '11:32', 'branch' => 'İstanbul', 'desc' => 'Kargo Ücreti TRK-001', 'method' => 'Nakit', 'type' => 'IN', 'amount' => 85.00],
                                ['time' => '11:28', 'branch' => 'Ankara', 'desc' => 'Kargo Ücreti TRK-002', 'method' => 'Kart', 'type' => 'IN', 'amount' => 120.00],
                                ['time' => '11:14', 'branch' => 'İzmir', 'desc' => 'Otobüse Ödeme Metro', 'method' => 'Nakit', 'type' => 'OUT', 'amount' => 940.00],
                                ['time' => '10:58', 'branch' => 'Bursa', 'desc' => 'Emanet Ücreti', 'method' => 'Nakit', 'type' => 'IN', 'amount' => 44.00],
                                ['time' => '10:45', 'branch' => 'Antalya', 'desc' => 'Kargo Ücreti TRK-003', 'method' => 'Kart', 'type' => 'IN', 'amount' => 76.00],
                                ['time' => '10:30', 'branch' => 'İstanbul', 'desc' => 'Gider – Kırtasiye', 'method' => 'Nakit', 'type' => 'OUT', 'amount' => 60.00],
                            ];
                            foreach ($txs as $tx):
                                $typeHtml = $tx['type'] === 'IN'
                                    ? '<span class="status-badge" style="background:#e7f9f0;color:#0e8045;font-size:.7rem;"><i class="bi bi-arrow-down-left me-1"></i>Gelir</span>'
                                    : '<span class="status-badge" style="background:#fff0f2;color:#c03060;font-size:.7rem;"><i class="bi bi-arrow-up-right me-1"></i>Gider</span>';
                                $methodHtml = $tx['method'] === 'Nakit'
                                    ? '<span class="status-badge" style="background:#fff8ec;color:#e08b00;font-size:.7rem;">Nakit</span>'
                                    : '<span class="status-badge" style="background:#e8f1ff;color:#1b84ff;font-size:.7rem;">Kart</span>';
                                $amtColor = $tx['type'] === 'IN' ? '#0e8045' : '#c03060';
                                $amtSign = $tx['type'] === 'IN' ? '+' : '−';
                                ?>
                                <tr>
                                    <td style="font-size:.77rem;color:var(--text-muted);"><?= $tx['time'] ?></td>
                                    <td style="font-size:.78rem;"><?= $tx['branch'] ?></td>
                                    <td style="font-size:.8rem;"><?= $tx['desc'] ?></td>
                                    <td><?= $methodHtml ?></td>
                                    <td><?= $typeHtml ?></td>
                                    <td style="font-weight:700;font-size:.84rem;color:<?= $amtColor ?>;"><?= $amtSign ?>₺<?= number_format($tx['amount'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sağ kolon: Aktif Kullanıcılar + Uyarılar -->
        <div class="col-12 col-lg-5">

            <!-- Sistem Uyarıları -->
            <div class="card mb-3" style="padding:20px;">
                <div class="section-label mb-3">⚠️ Sistem Uyarıları</div>
                <div class="d-flex flex-column gap-2">
                    <div style="padding:10px 12px;background:#fff0f2;border-radius:6px;font-size:.78rem;border-left:3px solid #c03060;">
                        <div style="font-weight:700;color:#c03060;">Cari Limit Aşımı</div>
                        <div style="color:var(--text-muted);margin-top:2px;">Karadeniz Taşıma A.Ş. — ₺6.800 / ₺6.000</div>
                    </div>
                    <div style="padding:10px 12px;background:#fff0f2;border-radius:6px;font-size:.78rem;border-left:3px solid #c03060;">
                        <div style="font-weight:700;color:#c03060;">Cari Limit Aşımı</div>
                        <div style="color:var(--text-muted);margin-top:2px;">Yıldız Tekstil A.Ş. — ₺5.100 / ₺5.000</div>
                    </div>
                    <div style="padding:10px 12px;background:#fff8ec;border-radius:6px;font-size:.78rem;border-left:3px solid #e08b00;">
                        <div style="font-weight:700;color:#e08b00;">Emanet Süresi Dolmak Üzere</div>
                        <div style="color:var(--text-muted);margin-top:2px;">Ankara — 4 kargo 24 saat dolacak</div>
                    </div>
                    <div style="padding:10px 12px;background:#e8f1ff;border-radius:6px;font-size:.78rem;border-left:3px solid #1b84ff;">
                        <div style="font-weight:700;color:#1b84ff;">Yeni Şube Başvurusu</div>
                        <div style="color:var(--text-muted);margin-top:2px;">Gaziantep Bayi — onay bekliyor</div>
                    </div>
                </div>
            </div>

            <!-- Son Eklenen Kullanıcılar -->
            <div class="card" style="padding:20px;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="section-label">Son Kullanıcılar</div>
                    <a href="?page=users" style="font-size:.75rem;color:var(--accent-blue);">Tümü</a>
                </div>
                <div class="d-flex flex-column gap-3">
                    <?php
                    $users = [
                        ['name' => 'Mehmet Kara', 'role' => 'Şube Müdürü', 'branch' => 'İstanbul', 'time' => '1 sa önce'],
                        ['name' => 'Ayşe Yıldız', 'role' => 'Personel', 'branch' => 'Ankara', 'time' => '3 sa önce'],
                        ['name' => 'Hasan Demir', 'role' => 'Bölge Müdürü', 'branch' => 'İzmir', 'time' => 'Dün'],
                        ['name' => 'Caner Güneş', 'role' => 'Personel', 'branch' => 'Bursa', 'time' => 'Dün'],
                    ];
                    $initColors = ['#1b84ff', '#0e8045', '#8e24aa', '#e08b00'];
                    foreach ($users as $i => $u):
                        ?>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;border-radius:50%;background:<?= $initColors[$i] ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <span style="color:#fff;font-size:.72rem;font-weight:700;"><?= mb_strtoupper(mb_substr($u['name'], 0, 1)) ?></span>
                            </div>
                            <div style="flex:1;overflow:hidden;">
                                <div style="font-size:.8rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= $u['name'] ?></div>
                                <div style="font-size:.71rem;color:var(--text-muted);"><?= $u['role'] ?> · <?= $u['branch'] ?></div>
                            </div>
                            <div style="font-size:.7rem;color:var(--text-muted);white-space:nowrap;"><?= $u['time'] ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

    </div><!-- /row 4 -->

</div><!-- /body -->

</main>

<script>
window.addEventListener('DOMContentLoaded', function () {

    /* ── Günlük Gelir Alan Grafiği ── */
    var labels = ['11 Şub','12 Şub','13 Şub','14 Şub','15 Şub','16 Şub',
                  '17 Şub','18 Şub','19 Şub','20 Şub','21 Şub','22 Şub','23 Şub','Bugün'];
    var rc = document.getElementById('revenueChart').getContext('2d');
    new Chart(rc, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Nakit',
                    data: [12400,11800,13200,10900,14500,13800,15200,16100,14800,17300,16500,18200,17800,19400],
                    backgroundColor: 'rgba(27,132,255,0.75)',
                    borderRadius: 3,
                    stack: 'stack'
                },
                {
                    label: 'Kart',
                    data: [5600,6200,5900,7100,6800,7500,7200,8100,7600,8900,8200,9400,8800,9600],
                    backgroundColor: 'rgba(14,128,69,0.75)',
                    borderRadius: 3,
                    stack: 'stack'
                },
                {
                    label: 'Cari',
                    data: [2100,1800,2400,1600,2800,2200,3100,2600,2900,3400,3100,3700,3200,3800],
                    backgroundColor: 'rgba(224,139,0,0.75)',
                    borderRadius: 3,
                    stack: 'stack'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: { stacked: true, grid: { display: false }, ticks: { font: { size: 10 } } },
                y: { stacked: true, grid: { color: 'rgba(0,0,0,.06)' }, ticks: { font: { size: 10 },
                     callback: function(v){ return '₺' + (v/1000).toFixed(0) + 'K'; } } }
            },
            plugins: { legend: { display: false }, tooltip: {
                callbacks: { label: function(c){ return c.dataset.label + ': ₺' + c.raw.toLocaleString('tr-TR'); } }
            }}
        }
    });

    /* ── Kargo Durum Donut ── */
    var dc = document.getElementById('statusDonut').getContext('2d');
    new Chart(dc, {
        type: 'doughnut',
        data: {
            labels: ['Teslim Edildi','Yolda','Emanette','Bekliyor'],
            datasets: [{
                data: [58, 22, 14, 6],
                backgroundColor: ['#0e8045','#1b84ff','#e08b00','#c03060'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            cutout: '70%',
            plugins: { legend: { display: false },
                tooltip: { callbacks: { label: function(c){ return c.label + ': %' + c.raw; } } }
            }
        }
    });
});
</script>
