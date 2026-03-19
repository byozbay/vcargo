<?php
require_once __DIR__ . "/../../core/autoload.php";
$base = new BaseModel();
$counts = $base->query(
    "SELECT
        COUNT(*) AS total,
        SUM(CASE WHEN status='in_transit' THEN 1 ELSE 0 END) AS in_transit,
        SUM(CASE WHEN status IN('in_storage','at_branch') THEN 1 ELSE 0 END) AS in_storage,
        SUM(CASE WHEN status='delivered' THEN 1 ELSE 0 END) AS delivered
     FROM shipments WHERE is_active=1"
)[0] ?? [];
$shipments = $base->query(
    "SELECT s.shipment_id, s.tracking_no, s.sender_name, s.sender_phone,
            s.receiver_name, s.receiver_phone, s.status, s.payment_type,
            s.total_fee, DATE_FORMAT(s.created_at,'%d.%m.%Y %H:%i') AS created_fmt,
            c.name AS dest_city, b.name AS branch_name
     FROM shipments s
     LEFT JOIN cities c ON c.city_id = s.destination_city_id
     LEFT JOIN branches b ON b.branch_id = s.branch_id
     WHERE s.is_active = 1
     ORDER BY s.created_at DESC LIMIT 200"
);
$statusLabel = ['in_transit'=>'Sevkte','in_storage'=>'Emanette','at_branch'=>'Şubede',
                'delivered'=>'Teslim Edildi','cancelled'=>'İptal','pending'=>'Bekliyor'];
$payLabel = ['SENDER_PAYS'=>'Nakit','CARD'=>'Kredi Kartı','RECEIVER_PAYS'=>'C.O.D','ACCOUNT'=>'Cari'];
$payStyle = ['SENDER_PAYS'=>'background:#e8f1ff;color:#1b84ff;','CARD'=>'background:#e7f9f0;color:#0e8045;',
             'RECEIVER_PAYS'=>'background:#fff8ec;color:#e08b00;','ACCOUNT'=>'background:#f3e5f5;color:#8e24aa;'];
$statStyle = ['in_transit'=>'background:#e8f1ff;color:#145dc0;','in_storage'=>'background:#fff8ec;color:#e08b00;',
              'at_branch'=>'background:#fff8ec;color:#e08b00;','delivered'=>'background:#e7f9f0;color:#0e8045;',
              'cancelled'=>'background:#fff0f2;color:#c03060;','pending'=>'background:#f0f0f0;color:#555;'];
?>
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
                <div class="stat-value" style="font-size:1.5rem;"><?= number_format($counts['total'] ?? 0) ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card" style="padding:14px 16px;">
                <div class="card-sm-label">Sevkte</div>
                <div class="stat-value" style="font-size:1.5rem;color:#1b84ff;"><?= number_format($counts['in_transit'] ?? 0) ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card" style="padding:14px 16px;">
                <div class="card-sm-label">Emanette</div>
                <div class="stat-value" style="font-size:1.5rem;color:#e08b00;"><?= number_format($counts['in_storage'] ?? 0) ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card" style="padding:14px 16px;">
                <div class="card-sm-label">Teslim Edildi</div>
                <div class="stat-value" style="font-size:1.5rem;color:#0e8045;"><?= number_format($counts['delivered'] ?? 0) ?></div>
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
                <span style="font-size:.78rem;color:var(--text-muted);align-self:center;"><?= count($shipments) ?> kayıt bulundu</span>
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

                    <?php if (empty($shipments)): ?>
                        <tr><td colspan="11" class="text-center" style="padding:32px;color:var(--text-muted);font-size:.82rem;">
                            <i class="bi bi-inbox" style="font-size:1.8rem;display:block;margin-bottom:8px;opacity:.4;"></i>
                            Kargo bulunamadı.
                        </td></tr>
                    <?php else: foreach ($shipments as $s): ?>
                        <tr data-status="<?= htmlspecialchars($s['status']) ?>">
                            <td><input type="checkbox" class="row-check" /></td>
                            <td><a href="?page=shipment_detail&id=<?= $s['shipment_id'] ?>"
                                   style="font-family:monospace;font-size:.79rem;color:var(--accent-blue);font-weight:600;text-decoration:none;">
                                <?= htmlspecialchars($s['tracking_no']) ?></a></td>
                            <td>
                                <div style="font-size:.81rem;font-weight:500;"><?= htmlspecialchars($s['sender_name']) ?></div>
                                <div style="font-size:.72rem;color:var(--text-muted);"><?= htmlspecialchars($s['sender_phone'] ?? '') ?></div>
                            </td>
                            <td>
                                <div style="font-size:.81rem;font-weight:500;"><?= htmlspecialchars($s['receiver_name']) ?></div>
                                <div style="font-size:.72rem;color:var(--text-muted);"><?= htmlspecialchars($s['receiver_phone'] ?? '') ?></div>
                            </td>
                            <td style="font-size:.81rem;"><?= htmlspecialchars($s['dest_city'] ?? '—') ?></td>
                            <td style="font-size:.79rem;color:var(--text-muted);"><?= htmlspecialchars($s['branch_name'] ?? '—') ?></td>
                            <td><span class="status-badge" style="<?= $payStyle[$s['payment_type']] ?? '' ?>">
                                <?= $payLabel[$s['payment_type']] ?? $s['payment_type'] ?></span></td>
                            <td style="font-weight:600;font-size:.83rem;">₺<?= number_format((float)$s['total_fee'],2) ?></td>
                            <td style="font-size:.79rem;color:var(--text-muted);"><?= $s['created_fmt'] ?></td>
                            <td><span class="status-badge" style="<?= $statStyle[$s['status']] ?? '' ?>">
                                <?= $statusLabel[$s['status']] ?? $s['status'] ?></span></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="?page=shipment_detail&id=<?= $s['shipment_id'] ?>" class="icon-btn-circle" title="Detay"
                                       style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                                        <i class="bi bi-eye" style="font-size:.8rem;"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>

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
