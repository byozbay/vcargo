<?php
/**
 * Bölge Müdürü — Dashboard
 * Basic skeleton so the region_manager role has at least a landing page.
 */
$regionName = $_SESSION['user_name'] ?? 'Bölge Müdürü';
?>
<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Bölge Dashboard</div>
            <div class="breadcrumb">
                <span style="color:var(--text-muted);">Hoş geldiniz, <?= htmlspecialchars($regionName) ?></span>
            </div>
        </div>
    </div>

    <div style="padding:18px 26px 40px;">

        <!-- ═══ KPI Kartları ═══ -->
        <div class="row g-3 mb-3">
            <div class="col-6 col-lg-3">
                <div class="card h-100" style="padding:18px;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="card-sm-label">Bölge Şubeleri</div>
                            <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">—</div>
                            <span class="badge-change badge-up mt-2">Veritabanından yüklenecek</span>
                        </div>
                        <div style="width:42px;height:42px;border-radius:8px;background:#f3e5f5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-shop" style="color:#8e24aa;font-size:1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100" style="padding:18px;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="card-sm-label">Toplam Kargo</div>
                            <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">—</div>
                            <span class="badge-change badge-up mt-2">Bu ay</span>
                        </div>
                        <div style="width:42px;height:42px;border-radius:8px;background:#e8f1ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-box-seam" style="color:#1b84ff;font-size:1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100" style="padding:18px;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="card-sm-label">Bölge Cirosu</div>
                            <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">— ₺</div>
                            <span class="badge-change badge-up mt-2">Bu ay</span>
                        </div>
                        <div style="width:42px;height:42px;border-radius:8px;background:#e7f9f0;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-wallet2" style="color:#0e8045;font-size:1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100" style="padding:18px;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="card-sm-label">Emanetteki Kargo</div>
                            <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">—</div>
                            <span class="badge-change badge-down mt-2">Bekleyen</span>
                        </div>
                        <div style="width:42px;height:42px;border-radius:8px;background:#fff8ec;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-archive" style="color:#e08b00;font-size:1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Placeholder Bilgi -->
        <div class="card" style="padding:32px;text-align:center;">
            <i class="bi bi-diagram-3" style="font-size:2.4rem;color:#8e24aa;opacity:.6;"></i>
            <div style="font-size:.92rem;font-weight:600;margin-top:12px;color:var(--text-dark);">
                Bölge Müdürü Paneli
            </div>
            <div style="font-size:.8rem;color:var(--text-muted);margin-top:6px;">
                Bölgesel raporlama, şube denetimi ve fiyatlandırma modülleri yakında aktif edilecektir.
            </div>
        </div>

    </div>
</main>
