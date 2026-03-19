<?php
// ── Mock data (will be replaced with real DB queries later) ──
$todayDate = date('d.m.Y');
$branchName = 'İstanbul Otogar Şubesi';
?>
<main class="main-content">
<!-- ── Page Header ── -->
<div class="page-header">
    <div>
        <div class="page-title">Şube Dashboard</div>
        <div class="breadcrumb">
            <span style="color:var(--text-muted);">
                <?= $branchName ?>
            </span>
            <span class="sep">·</span>
            <span style="color:var(--text-muted);">
                <?= $todayDate ?>
            </span>
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
                        <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">47</div>
                        <span class="badge-change badge-up mt-2">&#8593; 12% dün'e göre</span>
                    </div>
                    <div
                        style="width:42px;height:42px;border-radius:8px;background:#e8f1ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
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
                        <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">23</div>
                        <span class="badge-change badge-down mt-2">&#8595; 3 yeni geldi</span>
                    </div>
                    <div
                        style="width:42px;height:42px;border-radius:8px;background:#fff8ec;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
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
                        <div class="card-sm-label">Emanetteki Kargo</div>
                        <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">11</div>
                        <div style="font-size:.74rem;color:var(--text-muted);margin-top:6px;">2 tanesi &gt;24 saat</div>
                    </div>
                    <div
                        style="width:42px;height:42px;border-radius:8px;background:#f3e5f5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
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
                            <span style="font-size:1.1rem;font-weight:600;vertical-align:super;">₺</span>4.320
                        </div>
                        <span class="badge-change badge-up mt-2">&#8593; 8% haftaya göre</span>
                    </div>
                    <div
                        style="width:42px;height:42px;border-radius:8px;background:#e7f9f0;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-safe2" style="color:#0e8045;font-size:1.2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /ROW 1 -->

    <!-- ══════════ ROW 2 — CHART + DURUM ÖZET ══════════ -->
    <div class="row g-3 mb-3">

        <!-- Haftalık Kargo Grafiği -->
        <div class="col-12 col-lg-7">
            <div class="card h-100" style="padding:20px;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="section-label">Haftalık Kargo Trendi</div>
                        <div class="section-sub">Son 7 günün kargo kabul / teslim karşılaştırması</div>
                    </div>
                    <div class="icon-btn-circle"><i class="bi bi-three-dots"></i></div>
                </div>
                <div style="height:200px;">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Kargo Durum Dağılımı -->
        <div class="col-12 col-lg-5">
            <div class="card h-100" style="padding:20px;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="section-label">Kargo Durumları</div>
                    <div class="icon-btn-circle"><i class="bi bi-three-dots"></i></div>
                </div>

                <!-- Donut -->
                <div class="d-flex align-items-center gap-4">
                    <div style="width:110px;height:110px;flex-shrink:0;position:relative;">
                        <canvas id="statusDonut" width="110" height="110"></canvas>
                    </div>
                    <div class="flex-grow-1 d-flex flex-column gap-2" style="font-size:.81rem;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><span class="legend-dot me-1" style="background:#1b84ff;"></span> Sevkte</span>
                            <span style="font-weight:600;">34</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><span class="legend-dot me-1" style="background:#e08b00;"></span> Emanette</span>
                            <span style="font-weight:600;">11</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><span class="legend-dot me-1" style="background:#17c964;"></span> Teslim Edildi</span>
                            <span style="font-weight:600;">89</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><span class="legend-dot me-1" style="background:#f31260;"></span> İptal</span>
                            <span style="font-weight:600;">4</span>
                        </div>
                    </div>
                </div>

                <!-- Progress bars -->
                <div class="mt-3 d-flex flex-column gap-2">
                    <div>
                        <div class="d-flex justify-content-between"
                            style="font-size:.74rem;color:var(--text-muted);margin-bottom:3px;">
                            <span>Sevkte</span><span>24%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" style="width:24%;background:#1b84ff;"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between"
                            style="font-size:.74rem;color:var(--text-muted);margin-bottom:3px;">
                            <span>Emanette</span><span>8%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" style="width:8%;background:#e08b00;"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between"
                            style="font-size:.74rem;color:var(--text-muted);margin-bottom:3px;">
                            <span>Teslim Edildi</span><span>63%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" style="width:63%;background:#17c964;"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between"
                            style="font-size:.74rem;color:var(--text-muted);margin-bottom:3px;">
                            <span>İptal</span><span>3%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" style="width:3%;background:#f31260;"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div><!-- /ROW 2 -->

    <!-- ══════════ ROW 3 — SON KARGOLAR + HIZLI İŞLEMLER ══════════ -->
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
                            <tr>
                                <td><span
                                        style="font-family:monospace;font-size:.8rem;color:var(--accent-blue);font-weight:600;">TRK-240224-001</span>
                                </td>
                                <td>
                                    <div style="font-size:.82rem;font-weight:500;">Ahmet Yılmaz</div>
                                    <div style="font-size:.73rem;color:var(--text-muted);">0532 xxx xx xx</div>
                                </td>
                                <td><span style="font-size:.8rem;">Ankara</span></td>
                                <td><span style="font-weight:600;">₺85</span></td>
                                <td><span class="status-badge status-sevkte">Sevkte</span></td>
                            </tr>
                            <tr>
                                <td><span
                                        style="font-family:monospace;font-size:.8rem;color:var(--accent-blue);font-weight:600;">TRK-240224-002</span>
                                </td>
                                <td>
                                    <div style="font-size:.82rem;font-weight:500;">Fatma Kaya</div>
                                    <div style="font-size:.73rem;color:var(--text-muted);">0541 xxx xx xx</div>
                                </td>
                                <td><span style="font-size:.8rem;">İzmir</span></td>
                                <td><span style="font-weight:600;">₺120</span></td>
                                <td><span class="status-badge status-emanette">Emanette</span></td>
                            </tr>
                            <tr>
                                <td><span
                                        style="font-family:monospace;font-size:.8rem;color:var(--accent-blue);font-weight:600;">TRK-240224-003</span>
                                </td>
                                <td>
                                    <div style="font-size:.82rem;font-weight:500;">Mehmet Demir</div>
                                    <div style="font-size:.73rem;color:var(--text-muted);">0505 xxx xx xx</div>
                                </td>
                                <td><span style="font-size:.8rem;">Bursa</span></td>
                                <td><span style="font-weight:600;">₺65</span></td>
                                <td><span class="status-badge status-teslim">Teslim Edildi</span></td>
                            </tr>
                            <tr>
                                <td><span
                                        style="font-family:monospace;font-size:.8rem;color:var(--accent-blue);font-weight:600;">TRK-240224-004</span>
                                </td>
                                <td>
                                    <div style="font-size:.82rem;font-weight:500;">Ayşe Çelik</div>
                                    <div style="font-size:.73rem;color:var(--text-muted);">0555 xxx xx xx</div>
                                </td>
                                <td><span style="font-size:.8rem;">Antalya</span></td>
                                <td><span style="font-weight:600;">₺145</span></td>
                                <td><span class="status-badge status-teslim">Teslim Edildi</span></td>
                            </tr>
                            <tr>
                                <td><span
                                        style="font-family:monospace;font-size:.8rem;color:var(--accent-blue);font-weight:600;">TRK-240224-005</span>
                                </td>
                                <td>
                                    <div style="font-size:.82rem;font-weight:500;">Ali Öztürk</div>
                                    <div style="font-size:.73rem;color:var(--text-muted);">0533 xxx xx xx</div>
                                </td>
                                <td><span style="font-size:.8rem;">Konya</span></td>
                                <td><span style="font-weight:600;">₺95</span></td>
                                <td><span class="status-badge status-iptal">İptal</span></td>
                            </tr>
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
                        <div
                            style="width:34px;height:34px;background:#e8f1ff;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
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
                        <div
                            style="width:34px;height:34px;background:#e7f9f0;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
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
                        <div
                            style="width:34px;height:34px;background:#f3e5f5;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
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
                        <div
                            style="width:34px;height:34px;background:#fff8ec;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
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
                    <a href="?page=voyage"
                        style="font-size:.8rem;color:var(--accent-blue);text-decoration:none;">Tümü</a>
                </div>
                <div class="d-flex flex-column gap-3">

                    <div class="d-flex align-items-center gap-2">
                        <div
                            style="width:36px;height:36px;background:#e8f1ff;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-bus-front" style="color:#1b84ff;font-size:.95rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div style="font-size:.81rem;font-weight:600;">34 ABC 001 → Ankara</div>
                            <div style="font-size:.73rem;color:var(--text-muted);">Metro Turizm · 08:00</div>
                        </div>
                        <span class="status-badge status-sevkte">Aktif</span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div
                            style="width:36px;height:36px;background:#e8f1ff;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-bus-front" style="color:#1b84ff;font-size:.95rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div style="font-size:.81rem;font-weight:600;">35 XY 002 → İzmir</div>
                            <div style="font-size:.73rem;color:var(--text-muted);">Pamukkale · 10:30</div>
                        </div>
                        <span class="status-badge status-sevkte">Aktif</span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div
                            style="width:36px;height:36px;background:#f5f5f5;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-bus-front" style="color:#94a3b8;font-size:.95rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div style="font-size:.81rem;font-weight:600;">06 KA 777 → Bursa</div>
                            <div style="font-size:.73rem;color:var(--text-muted);">Uludağ · 15:00</div>
                        </div>
                        <span class="status-badge" style="background:#f0f0f0;color:#94a3b8;">Bekleniyor</span>
                    </div>

                </div>
            </div>

        </div>

    </div><!-- /ROW 3 -->

</div><!-- /body -->
</main>
<!-- ── Charts ── -->
<script>
    /* Weekly Bar+Line Chart */
    new Chart(document.getElementById('weeklyChart'), {
        type: 'bar',
        data: {
            labels: ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'],
            datasets: [
                {
                    label: 'Kabul',
                    data: [38, 52, 45, 60, 47, 30, 22],
                    backgroundColor: 'rgba(27,132,255,0.85)',
                    borderRadius: 4,
                    barThickness: 14,
                    order: 2,
                },
                {
                    label: 'Teslim',
                    data: [30, 44, 38, 55, 41, 26, 18],
                    type: 'line',
                    borderColor: '#17c964',
                    backgroundColor: 'rgba(23,201,100,0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    order: 1,
                }
            ]
        },
        options: {
            plugins: {
                legend: { display: true, position: 'top', labels: { font: { size: 11 }, boxWidth: 10 } },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#a3aab8' } },
                y: { grid: { color: 'rgba(0,0,0,.05)' }, ticks: { font: { size: 11 }, color: '#a3aab8', stepSize: 10 } }
            },
            animation: { duration: 900 },
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    /* Status Donut */
    new Chart(document.getElementById('statusDonut'), {
        type: 'doughnut',
        data: {
            labels: ['Sevkte', 'Emanette', 'Teslim', 'İptal'],
            datasets: [{
                data: [34, 11, 89, 4],
                backgroundColor: ['#1b84ff', '#e08b00', '#17c964', '#f31260'],
                borderWidth: 0,
                cutout: '72%',
            }]
        },
        options: {
            plugins: { legend: { display: false }, tooltip: { enabled: true } },
            animation: { animateRotate: true },
        }
    });
</script>