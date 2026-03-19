<main class="main-content">

<!-- ── Page Header ── -->
<div class="page-header">
    <div>
        <div class="page-title">Kargo Listesi</div>
        <div class="breadcrumb">
            <a href="?page=dashboard">Ana Sayfa</a>
            <span class="sep">›</span>
            <span>Kargo İşlemleri</span>
        </div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="?page=shipment_create" class="btn-primary-sm d-flex align-items-center gap-1">
            <i class="bi bi-plus-lg"></i> Yeni Kargo Kabul
        </a>
        <button class="btn-outline-secondary-sm d-flex align-items-center gap-1" onclick="window.print()">
            <i class="bi bi-printer"></i> Yazdır
        </button>
    </div>
</div>

<div style="padding:18px 26px 32px;">

    <!-- ══ ROW 1 — Mini Stat Kartları ══ -->
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="card" style="padding:14px 16px;">
                <div class="card-sm-label">Toplam Kargo</div>
                <div class="stat-value" style="font-size:1.5rem;">138</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card" style="padding:14px 16px;">
                <div class="card-sm-label">Sevkte</div>
                <div class="stat-value" style="font-size:1.5rem;color:#1b84ff;">34</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card" style="padding:14px 16px;">
                <div class="card-sm-label">Emanette</div>
                <div class="stat-value" style="font-size:1.5rem;color:#e08b00;">11</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card" style="padding:14px 16px;">
                <div class="card-sm-label">Teslim Edildi</div>
                <div class="stat-value" style="font-size:1.5rem;color:#0e8045;">89</div>
            </div>
        </div>
    </div>

    <!-- ══ Filtre Satırı ══ -->
    <div class="card mb-3" style="padding:16px 20px;">
        <div class="row g-2 align-items-end">
            <!-- Arama -->
            <div class="col-12 col-md-4">
                <label style="font-size:.75rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:4px;">Takip No / Alıcı Ara</label>
                <div style="display:flex;align-items:center;gap:8px;border:1px solid var(--border-color);border-radius:7px;padding:7px 12px;background:var(--body-bg);">
                    <i class="bi bi-search" style="color:#a3aab8;font-size:.85rem;"></i>
                    <input id="shipmentSearch" type="text" placeholder="TRK-... veya ad soyad"
                           style="border:none;background:transparent;outline:none;font-size:.83rem;width:100%;color:var(--text-dark);" />
                </div>
            </div>
            <!-- Durum -->
            <div class="col-6 col-md-2">
                <label style="font-size:.75rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:4px;">Durum</label>
                <select id="filterStatus" style="width:100%;border:1px solid var(--border-color);border-radius:7px;padding:8px 10px;font-size:.83rem;background:var(--body-bg);color:var(--text-dark);outline:none;">
                    <option value="">Tümü</option>
                    <option value="sevkte">Sevkte</option>
                    <option value="emanette">Emanette</option>
                    <option value="teslim">Teslim Edildi</option>
                    <option value="iptal">İptal</option>
                </select>
            </div>
            <!-- Ödeme Tipi -->
            <div class="col-6 col-md-2">
                <label style="font-size:.75rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:4px;">Ödeme</label>
                <select id="filterPayment" style="width:100%;border:1px solid var(--border-color);border-radius:7px;padding:8px 10px;font-size:.83rem;background:var(--body-bg);color:var(--text-dark);outline:none;">
                    <option value="">Tümü</option>
                    <option value="nakit">Nakit</option>
                    <option value="kart">Kredi Kartı</option>
                    <option value="cod">Alıcı Öder (C.O.D)</option>
                    <option value="cari">Cari</option>
                </select>
            </div>
            <!-- Tarih -->
            <div class="col-6 col-md-2">
                <label style="font-size:.75rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:4px;">Tarih</label>
                <input type="date" id="filterDate" value="<?= date('Y-m-d') ?>"
                       style="width:100%;border:1px solid var(--border-color);border-radius:7px;padding:7px 10px;font-size:.83rem;background:var(--body-bg);color:var(--text-dark);outline:none;" />
            </div>
            <!-- Filtrele Butonu -->
            <div class="col-6 col-md-2">
                <button class="btn-primary-sm w-100" style="height:37px;border-radius:7px;" onclick="filterTable()">
                    <i class="bi bi-funnel me-1"></i> Filtrele
                </button>
            </div>
        </div>
    </div>

    <!-- ══ Kargo Tablosu ══ -->
    <div class="card" style="padding:20px;">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div class="section-label">Kargo Kayıtları</div>
            <div class="d-flex gap-2">
                <span style="font-size:.78rem;color:var(--text-muted);align-self:center;">138 kayıt bulundu</span>
                <button class="btn-outline-secondary-sm d-flex align-items-center gap-1" style="font-size:.78rem;padding:5px 12px;">
                    <i class="bi bi-download"></i> Dışa Aktar
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="orders-table" id="shipmentTable">
                <thead>
                    <tr>
                        <th style="width:40px;"><input type="checkbox" id="selectAll" /></th>
                        <th>Takip No</th>
                        <th>Gönderici</th>
                        <th>Alıcı</th>
                        <th>Varış</th>
                        <th>Ödeme</th>
                        <th>Ücret</th>
                        <th>Tarih</th>
                        <th>Durum</th>
                        <th style="width:80px;">İşlem</th>
                    </tr>
                </thead>
                <tbody id="shipmentBody">

                    <tr data-status="sevkte" data-payment="nakit">
                        <td><input type="checkbox" class="row-check" /></td>
                        <td><a href="?page=shipment_detail&id=1" style="font-family:monospace;font-size:.79rem;color:var(--accent-blue);font-weight:600;text-decoration:none;">TRK-240224-001</a></td>
                        <td>
                            <div style="font-size:.81rem;font-weight:500;">Ahmet Yılmaz</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">0532 xxx xx xx</div>
                        </td>
                        <td>
                            <div style="font-size:.81rem;font-weight:500;">Mehmet Demir</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">0505 xxx xx xx</div>
                        </td>
                        <td style="font-size:.81rem;">Ankara</td>
                        <td><span class="status-badge" style="background:#e8f1ff;color:#1b84ff;">Nakit</span></td>
                        <td style="font-weight:600;font-size:.83rem;">₺85</td>
                        <td style="font-size:.79rem;color:var(--text-muted);">24.02.2026 08:14</td>
                        <td><span class="status-badge status-sevkte">Sevkte</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="icon-btn-circle" title="Detay"><i class="bi bi-eye" style="font-size:.8rem;"></i></button>
                                <button class="icon-btn-circle" title="Barkod Bas"><i class="bi bi-upc-scan" style="font-size:.8rem;"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr data-status="emanette" data-payment="cod">
                        <td><input type="checkbox" class="row-check" /></td>
                        <td><a href="?page=shipment_detail&id=2" style="font-family:monospace;font-size:.79rem;color:var(--accent-blue);font-weight:600;text-decoration:none;">TRK-240224-002</a></td>
                        <td>
                            <div style="font-size:.81rem;font-weight:500;">Fatma Kaya</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">0541 xxx xx xx</div>
                        </td>
                        <td>
                            <div style="font-size:.81rem;font-weight:500;">Ayşe Çelik</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">0555 xxx xx xx</div>
                        </td>
                        <td style="font-size:.81rem;">İzmir</td>
                        <td><span class="status-badge" style="background:#fff8ec;color:#e08b00;">C.O.D</span></td>
                        <td style="font-weight:600;font-size:.83rem;">₺120</td>
                        <td style="font-size:.79rem;color:var(--text-muted);">24.02.2026 09:30</td>
                        <td><span class="status-badge status-emanette">Emanette</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="icon-btn-circle" title="Detay"><i class="bi bi-eye" style="font-size:.8rem;"></i></button>
                                <button class="icon-btn-circle" title="Teslim Et"><i class="bi bi-check2-circle" style="font-size:.8rem;"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr data-status="teslim" data-payment="kart">
                        <td><input type="checkbox" class="row-check" /></td>
                        <td><a href="?page=shipment_detail&id=3" style="font-family:monospace;font-size:.79rem;color:var(--accent-blue);font-weight:600;text-decoration:none;">TRK-240224-003</a></td>
                        <td>
                            <div style="font-size:.81rem;font-weight:500;">Ali Öztürk</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">0533 xxx xx xx</div>
                        </td>
                        <td>
                            <div style="font-size:.81rem;font-weight:500;">Hasan Aydın</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">0544 xxx xx xx</div>
                        </td>
                        <td style="font-size:.81rem;">Bursa</td>
                        <td><span class="status-badge" style="background:#e7f9f0;color:#0e8045;">Kredi Kartı</span></td>
                        <td style="font-weight:600;font-size:.83rem;">₺65</td>
                        <td style="font-size:.79rem;color:var(--text-muted);">24.02.2026 10:05</td>
                        <td><span class="status-badge status-teslim">Teslim Edildi</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="icon-btn-circle" title="Detay"><i class="bi bi-eye" style="font-size:.8rem;"></i></button>
                                <button class="icon-btn-circle" title="Barkod Bas"><i class="bi bi-upc-scan" style="font-size:.8rem;"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr data-status="teslim" data-payment="cari">
                        <td><input type="checkbox" class="row-check" /></td>
                        <td><a href="?page=shipment_detail&id=4" style="font-family:monospace;font-size:.79rem;color:var(--accent-blue);font-weight:600;text-decoration:none;">TRK-240224-004</a></td>
                        <td>
                            <div style="font-size:.81rem;font-weight:500;">ABC Lojistik Ltd.</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">0212 xxx xx xx</div>
                        </td>
                        <td>
                            <div style="font-size:.81rem;font-weight:500;">Caner Yıldız</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">0551 xxx xx xx</div>
                        </td>
                        <td style="font-size:.81rem;">Antalya</td>
                        <td><span class="status-badge" style="background:#f3e5f5;color:#8e24aa;">Cari</span></td>
                        <td style="font-weight:600;font-size:.83rem;">₺145</td>
                        <td style="font-size:.79rem;color:var(--text-muted);">24.02.2026 11:20</td>
                        <td><span class="status-badge status-teslim">Teslim Edildi</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="icon-btn-circle" title="Detay"><i class="bi bi-eye" style="font-size:.8rem;"></i></button>
                                <button class="icon-btn-circle" title="Barkod Bas"><i class="bi bi-upc-scan" style="font-size:.8rem;"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr data-status="iptal" data-payment="nakit">
                        <td><input type="checkbox" class="row-check" /></td>
                        <td><a href="?page=shipment_detail&id=5" style="font-family:monospace;font-size:.79rem;color:var(--accent-blue);font-weight:600;text-decoration:none;">TRK-240224-005</a></td>
                        <td>
                            <div style="font-size:.81rem;font-weight:500;">Kemal Şahin</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">0545 xxx xx xx</div>
                        </td>
                        <td>
                            <div style="font-size:.81rem;font-weight:500;">Zeynep Arslan</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">0567 xxx xx xx</div>
                        </td>
                        <td style="font-size:.81rem;">Konya</td>
                        <td><span class="status-badge" style="background:#e8f1ff;color:#1b84ff;">Nakit</span></td>
                        <td style="font-weight:600;font-size:.83rem;">₺95</td>
                        <td style="font-size:.79rem;color:var(--text-muted);">24.02.2026 12:45</td>
                        <td><span class="status-badge status-iptal">İptal</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="icon-btn-circle" title="Detay"><i class="bi bi-eye" style="font-size:.8rem;"></i></button>
                                <button class="icon-btn-circle" title="Barkod Bas"><i class="bi bi-upc-scan" style="font-size:.8rem;"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr data-status="sevkte" data-payment="nakit">
                        <td><input type="checkbox" class="row-check" /></td>
                        <td><a href="?page=shipment_detail&id=6" style="font-family:monospace;font-size:.79rem;color:var(--accent-blue);font-weight:600;text-decoration:none;">TRK-240224-006</a></td>
                        <td>
                            <div style="font-size:.81rem;font-weight:500;">Meral Güneş</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">0538 xxx xx xx</div>
                        </td>
                        <td>
                            <div style="font-size:.81rem;font-weight:500;">Tarık Doğan</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">0594 xxx xx xx</div>
                        </td>
                        <td style="font-size:.81rem;">Adana</td>
                        <td><span class="status-badge" style="background:#e8f1ff;color:#1b84ff;">Nakit</span></td>
                        <td style="font-weight:600;font-size:.83rem;">₺110</td>
                        <td style="font-size:.79rem;color:var(--text-muted);">24.02.2026 13:10</td>
                        <td><span class="status-badge status-sevkte">Sevkte</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="icon-btn-circle" title="Detay"><i class="bi bi-eye" style="font-size:.8rem;"></i></button>
                                <button class="icon-btn-circle" title="Barkod Bas"><i class="bi bi-upc-scan" style="font-size:.8rem;"></i></button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex align-items-center justify-content-between mt-3 flex-wrap gap-2">
            <div style="font-size:.78rem;color:var(--text-muted);">
                1-6 / 138 kayıt gösteriliyor
            </div>
            <div class="d-flex gap-1">
                <button class="icon-btn-circle" disabled><i class="bi bi-chevron-left" style="font-size:.75rem;"></i></button>
                <button style="width:30px;height:30px;border-radius:50%;border:none;background:var(--accent-blue);color:#fff;font-size:.78rem;font-weight:700;cursor:pointer;">1</button>
                <button class="icon-btn-circle" style="font-size:.75rem;">2</button>
                <button class="icon-btn-circle" style="font-size:.75rem;">3</button>
                <span style="align-self:center;color:var(--text-muted);font-size:.8rem;">···</span>
                <button class="icon-btn-circle" style="font-size:.75rem;">23</button>
                <button class="icon-btn-circle"><i class="bi bi-chevron-right" style="font-size:.75rem;"></i></button>
            </div>
        </div>

    </div><!-- /card -->

</div><!-- /body -->

</main>

<!-- ── style.css'te olmayan shipment'a özgü badge renkleri ── -->
<style>
.status-sevkte  { background:#e8f1ff;  color:#145dc0; }
.status-emanette{ background:#fff8ec;  color:#e08b00; }
.status-teslim  { background:#e7f9f0;  color:#0e8045; }
.status-iptal   { background:#fff0f2;  color:#c03060; }
</style>

<script>
// Select-all checkbox
document.getElementById('selectAll').addEventListener('change', function () {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
});

// Client-side filter (to be replaced with server-side pagination)
function filterTable() {
    var search  = document.getElementById('shipmentSearch').value.toLowerCase();
    var status  = document.getElementById('filterStatus').value;
    var payment = document.getElementById('filterPayment').value;

    document.querySelectorAll('#shipmentBody tr').forEach(function (row) {
        var text        = row.textContent.toLowerCase();
        var rowStatus   = row.getAttribute('data-status')  || '';
        var rowPayment  = row.getAttribute('data-payment') || '';

        var matchSearch  = !search  || text.includes(search);
        var matchStatus  = !status  || rowStatus  === status;
        var matchPayment = !payment || rowPayment === payment;

        row.style.display = (matchSearch && matchStatus && matchPayment) ? '' : 'none';
    });
}

// Live search
document.getElementById('shipmentSearch').addEventListener('input', filterTable);
document.getElementById('filterStatus').addEventListener('change', filterTable);
document.getElementById('filterPayment').addEventListener('change', filterTable);
</script>
