<?php
/* manifest.php — Otobüs Teslim Fişi (Manifesto)
 * Sefer bazlı kargo listesini çıktıya hazırlar.
 * URL örneği: ?page=manifest&trip_id=3
 */
require_once __DIR__ . '/../../core/autoload.php';
$tripId = intval($_GET['trip_id'] ?? 0);

/* ── Real DB query ── */
$base = new BaseModel();

$tripRow = $tripId > 0 ? $base->query(
    "SELECT t.trip_id AS id, t.plate_no AS plate, bc.name AS company,
            t.driver_name AS driver, t.driver_phone AS phone,
            CONCAT(COALESCE(oc.name,'—'), ' → ', COALESCE(dc.name,'—')) AS route,
            DATE_FORMAT(t.departure_time, '%d.%m.%Y %H:%i') AS departure,
            t.commission_rate AS comm_rate,
            t.total_cargo_fee, t.net_payment, t.branch_id
     FROM trips t
     LEFT JOIN bus_companies bc ON bc.company_id = t.company_id
     LEFT JOIN cities oc ON oc.city_id = t.origin_city_id
     LEFT JOIN cities dc ON dc.city_id = t.destination_city_id
     WHERE t.trip_id = ? AND t.is_active = 1
     LIMIT 1",
    [$tripId]
) : [];

$trip = $tripRow[0] ?? null;
if (!$trip) {
    $noTrip = true;
    $trip = ['id'=>0,'plate'=>'—','company'=>'—','driver'=>'—','phone'=>'—',
             'route'=>'—','departure'=>'—','comm_rate'=>0,'total_cargo_fee'=>0,'net_payment'=>0,'branch_id'=>0];
    $shipments = [];
} else {
    $noTrip = false;
    $shipments = $base->query(
        "SELECT s.tracking_no AS no,
                s.sender_name AS sender,
                s.receiver_name AS receiver,
                COALESCE(c.name,'—') AS dest,
                COALESCE(s.content_description, 'Koli') AS type,
                s.piece_count AS qty,
                COALESCE(s.weight, 0) AS weight,
                s.total_fee AS price,
                CASE s.payment_type
                    WHEN 'SENDER_PAYS'   THEN 'Gönderici Öder'
                    WHEN 'RECEIVER_PAYS' THEN 'Alıcı Öder (C.O.D)'
                    WHEN 'ACCOUNT'       THEN 'Cari'
                    ELSE s.payment_type
                END AS payment
         FROM shipments s
         LEFT JOIN cities c ON c.city_id = s.destination_city_id
         WHERE s.trip_id = ? AND s.is_active = 1
         ORDER BY s.shipment_id",
        [$tripId]
    );
}

$gross      = array_sum(array_column($shipments, 'price'));
$commission = $gross * ($trip['comm_rate'] / 100);
$net        = $gross - $commission;
$totalQty   = array_sum(array_column($shipments, 'qty'));
$totalKg    = array_sum(array_column($shipments, 'weight'));

$branchName = 'vCargo Şubesi';
if (!$noTrip && !empty($trip['branch_id'])) {
    $br = $base->query("SELECT name FROM branches WHERE branch_id = ? LIMIT 1", [$trip['branch_id']]);
    $branchName = $br[0]['name'] ?? $branchName;
}
?>
<main class="main-content">

    <!-- ── Page Header (baskıda gizlenir) ── -->
    <div class="page-header no-print">
        <div>
            <div class="page-title">Manifesto</div>
            <div class="breadcrumb">
                <a href="?page=voyage">Seferler</a>
                <span class="sep">›</span>
                <span>Manifesto —
                    <?= htmlspecialchars($trip['plate']) ?>
                </span>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn-primary-sm d-flex align-items-center gap-1">
                <i class="bi bi-printer"></i> Yazdır / PDF
            </button>
            <a href="?page=voyage" class="btn-outline-secondary-sm d-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Seferlere Dön
            </a>
        </div>
    </div>

    <div style="padding:18px 26px 40px;">

        <!-- ══ Manifesto Belgesi ══ -->
        <div class="manifest-doc" id="manifestDoc">

            <!-- Başlık -->
            <div class="manifest-header">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="manifest-logo">
                            <i class="bi bi-truck" style="font-size:1.6rem;color:#1b84ff;"></i>
                        </div>
                        <div>
                            <div style="font-size:1.3rem;font-weight:800;letter-spacing:-.02em;">vCargo</div>
                            <div style="font-size:.72rem;color:var(--text-muted);margin-top:1px;">Otogar Lojistik
                                Yönetim Sistemi</div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:1rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;">
                            OTOBÜs TESLİM FİŞİ</div>
                        <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px;">
                            Manifesto No: <strong style="font-family:monospace;">MNF-
                                <?= str_pad($trip['id'], 4, '0', STR_PAD_LEFT) ?>-
                                <?= date('Ymd') ?>
                            </strong>
                        </div>
                        <div style="font-size:.72rem;color:var(--text-muted);">
                            Oluşturma:
                            <?= date('d.m.Y H:i') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sefer Bilgileri -->
            <div class="manifest-section">
                <div class="manifest-section-title">Sefer Bilgileri</div>
                <div class="row g-0">
                    <div class="col-6 col-md-3">
                        <div class="manifest-field">
                            <div class="manifest-field-label">Plaka</div>
                            <div class="manifest-field-value" style="font-family:monospace;font-size:1rem;">
                                <?= htmlspecialchars($trip['plate']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="manifest-field">
                            <div class="manifest-field-label">Firma</div>
                            <div class="manifest-field-value">
                                <?= htmlspecialchars($trip['company']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="manifest-field">
                            <div class="manifest-field-label">Güzergah</div>
                            <div class="manifest-field-value">
                                <?= htmlspecialchars($trip['route']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="manifest-field">
                            <div class="manifest-field-label">Kalkış</div>
                            <div class="manifest-field-value">
                                <?= htmlspecialchars($trip['departure']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="manifest-field">
                            <div class="manifest-field-label">Şoför</div>
                            <div class="manifest-field-value">
                                <?= htmlspecialchars($trip['driver']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="manifest-field">
                            <div class="manifest-field-label">Şoför Tel</div>
                            <div class="manifest-field-value">
                                <?= htmlspecialchars($trip['phone']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="manifest-field">
                            <div class="manifest-field-label">Toplam Kargo</div>
                            <div class="manifest-field-value" style="color:#1b84ff;font-weight:700;">
                                <?= count($shipments) ?> adet
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="manifest-field">
                            <div class="manifest-field-label">Toplam Ağırlık</div>
                            <div class="manifest-field-value">
                                <?= number_format($totalKg, 1) ?> kg
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kargo Listesi -->
            <div class="manifest-section">
                <div class="manifest-section-title">Kargo Listesi</div>
                <div class="table-responsive">
                    <table class="manifest-table">
                        <thead>
                            <tr>
                                <th style="width:30px;">#</th>
                                <th>Takip No</th>
                                <th>Gönderici</th>
                                <th>Alıcı</th>
                                <th>Varış</th>
                                <th>Tip</th>
                                <th>Adet</th>
                                <th>Ağırlık</th>
                                <th>Ücret</th>
                                <th>Ödeme</th>
                                <th class="no-print">Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($shipments as $i => $s): ?>
                                <tr>
                                    <td style="color:var(--text-muted);font-size:.78rem;">
                                        <?= $i + 1 ?>
                                    </td>
                                    <td><span style="font-family:monospace;font-size:.78rem;font-weight:700;color:#1b84ff;">
                                            <?= htmlspecialchars($s['no']) ?>
                                        </span></td>
                                    <td style="font-size:.82rem;">
                                        <?= htmlspecialchars($s['sender']) ?>
                                    </td>
                                    <td style="font-size:.82rem;font-weight:600;">
                                        <?= htmlspecialchars($s['receiver']) ?>
                                    </td>
                                    <td style="font-size:.82rem;">
                                        <?= htmlspecialchars($s['dest']) ?>
                                    </td>
                                    <td style="font-size:.8rem;">
                                        <?= htmlspecialchars($s['type']) ?>
                                    </td>
                                    <td style="font-size:.82rem;text-align:center;">
                                        <?= $s['qty'] ?>
                                    </td>
                                    <td style="font-size:.82rem;">
                                        <?= number_format($s['weight'], 1) ?> kg
                                    </td>
                                    <td style="font-weight:700;font-size:.83rem;">₺
                                        <?= number_format($s['price'], 2) ?>
                                    </td>
                                    <td><span style="font-size:.72rem;white-space:nowrap;">
                                            <?= htmlspecialchars($s['payment']) ?>
                                        </span></td>
                                    <td class="no-print">
                                        <span class="status-badge"
                                            style="background:#fff8ec;color:#e08b00;font-size:.7rem;">Teslim
                                            Bekleniyor</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Finansal Özet -->
            <div class="manifest-section">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <!-- İmza Alanları -->
                        <div class="manifest-section-title">İmzalar</div>
                        <div class="d-flex gap-4 mt-2">
                            <div class="signature-box">
                                <div class="signature-line"></div>
                                <div class="signature-label">Şube Yetkilisi</div>
                            </div>
                            <div class="signature-box">
                                <div class="signature-line"></div>
                                <div class="signature-label">Şoför / Teslim Alan</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="manifest-section-title">Finansal Özet</div>
                        <table class="manifest-summary-table">
                            <tr>
                                <td>Toplam Parça</td>
                                <td class="text-end">
                                    <?= $totalQty ?> adet
                                </td>
                            </tr>
                            <tr>
                                <td>Brüt Kargo Ücreti</td>
                                <td class="text-end" style="font-weight:600;">₺
                                    <?= number_format($gross, 2) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Firma Komisyonu (%
                                    <?= $trip['comm_rate'] ?>)
                                </td>
                                <td class="text-end" style="color:#c03060;font-weight:600;">-₺
                                    <?= number_format($commission, 2) ?>
                                </td>
                            </tr>
                            <tr style="border-top:2px solid var(--border-color);">
                                <td style="font-weight:700;font-size:.88rem;">Şoföre Ödenecek Net</td>
                                <td class="text-end" style="font-weight:800;font-size:1rem;color:#1b84ff;">₺
                                    <?= number_format($net, 2) ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Alt Bilgi -->
            <div class="manifest-footer">
                <div>vCargo Otogar Lojistik Yönetim Sistemi · Bu belge elektronik ortamda oluşturulmuştur.</div>
                <div style="font-size:.7rem;opacity:.7;margin-top:2px;">
                    Şube: İstanbul Otogar Şubesi · Tarih:
                    <?= date('d.m.Y H:i') ?>
                </div>
            </div>

        </div><!-- /manifest-doc -->

    </div><!-- /body -->
</main>

<!-- ── Stiller ── -->
<style>
    /* ─── Belge Konteyner ─── */
    .manifest-doc {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: var(--card-radius);
        overflow: hidden;
        max-width: 900px;
        margin: 0 auto;
    }

    /* ─── Başlık ─── */
    .manifest-header {
        padding: 20px 24px;
        border-bottom: 2px solid var(--accent-blue);
        background: var(--card-bg);
    }

    .manifest-logo {
        width: 48px;
        height: 48px;
        background: #e8f1ff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ─── Bölüm ─── */
    .manifest-section {
        padding: 16px 24px;
        border-bottom: 1px solid var(--border-color);
    }

    .manifest-section:last-child {
        border-bottom: none;
    }

    .manifest-section-title {
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--text-muted);
        margin-bottom: 12px;
        padding-bottom: 6px;
        border-bottom: 1px dashed var(--border-color);
    }

    /* ─── Alan ─── */
    .manifest-field {
        padding: 8px 12px 8px 0;
    }

    .manifest-field-label {
        font-size: .68rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .03em;
        margin-bottom: 2px;
    }

    .manifest-field-value {
        font-size: .84rem;
        font-weight: 600;
        color: var(--text-dark);
    }

    /* ─── Kargo Tablosu ─── */
    .manifest-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .8rem;
    }

    .manifest-table thead tr {
        background: var(--body-bg);
        border-bottom: 2px solid var(--border-color);
    }

    .manifest-table thead th {
        padding: 8px 10px;
        text-align: left;
        font-size: .68rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .manifest-table tbody tr {
        border-bottom: 1px solid var(--border-color);
    }

    .manifest-table tbody tr:last-child {
        border-bottom: none;
    }

    .manifest-table tbody td {
        padding: 9px 10px;
        color: var(--text-dark);
        vertical-align: middle;
    }

    /* ─── Finansal Özet ─── */
    .manifest-summary-table {
        width: 100%;
        font-size: .82rem;
    }

    .manifest-summary-table td {
        padding: 5px 4px;
        color: var(--text-dark);
    }

    .manifest-summary-table .text-end {
        text-align: right;
    }

    /* ─── İmza ─── */
    .signature-box {
        flex: 1;
    }

    .signature-line {
        height: 44px;
        border-bottom: 2px solid var(--text-dark);
        margin-bottom: 6px;
    }

    .signature-label {
        font-size: .72rem;
        color: var(--text-muted);
        text-align: center;
    }

    /* ─── Alt Bilgi ─── */
    .manifest-footer {
        padding: 12px 24px;
        background: var(--body-bg);
        border-top: 1px solid var(--border-color);
        font-size: .75rem;
        color: var(--text-muted);
        text-align: center;
    }

    /* ─── YAZICI STİLLERİ ─── */
    @media print {

        .no-print,
        .page-header,
        nav,
        .sidebar,
        .topbar {
            display: none !important;
        }

        .main-content {
            margin: 0 !important;
            padding: 0 !important;
        }

        body {
            background: #fff !important;
        }

        .manifest-doc {
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            max-width: 100% !important;
        }

        .manifest-header {
            border-bottom: 2px solid #1b84ff !important;
        }

        .manifest-table tbody tr {
            page-break-inside: avoid;
        }
    }
</style>