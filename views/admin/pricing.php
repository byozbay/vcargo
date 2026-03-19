<?php
/* ── Pricing — Real DB Data ── */
require_once __DIR__ . '/../../core/autoload.php';
$base = new BaseModel();
// Load regions for cargo pricing tab
$regions = $base->query("SELECT r.region_id AS id, r.name, 40.00 AS cargo_base, 3.00 AS cargo_per_kg FROM regions r ORDER BY r.name");
// Load branches for storage pricing
$branches = $base->query(
    "SELECT b.branch_id, b.name, c.name AS city_name, b.free_storage_hours, b.storage_hourly_rate, b.baggage_hourly_rate
     FROM branches b LEFT JOIN cities c ON b.city_id = c.city_id
     WHERE b.is_active = 1 ORDER BY b.name"
);
?>
<main class="main-content">

    <div class="page-header">
        <div>
            <div class="page-title">Fiyatlandırma Yönetimi</div>
            <div class="breadcrumb">
                <a href="?page=dashboard" style="color:var(--text-muted);">Dashboard</a>
                <span class="sep">·</span>
                <span style="color:var(--text-muted);">Fiyatlandırma</span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn-outline-secondary-sm d-flex align-items-center gap-1" onclick="resetAllPrices()">
                <i class="bi bi-arrow-counterclockwise"></i> Varsayılana Dön
            </button>
            <button class="btn-primary-sm d-flex align-items-center gap-1" onclick="saveAllPrices()">
                <i class="bi bi-check-lg"></i> Tüm Değişiklikleri Kaydet
            </button>
        </div>
    </div>

    <div style="padding:18px 26px 40px;">

        <!-- ══ Tab Navigation ══ -->
        <div class="d-flex gap-1 mb-3 flex-wrap">
            <?php foreach ([
                ['cargo', 'bi-box-seam', 'Kargo Tarifeleri'],
                ['storage', 'bi-archive', 'Emanet Tarifeleri'],
                ['service', 'bi-wrench-adjustable', 'Ek Hizmetler'],
                ['indept', 'bi-person-badge', 'Bağımsız Emanet'],
            ] as [$key, $icon, $label]): ?>
                <button class="pricing-tab <?= $key === 'cargo' ? 'active' : '' ?>" id="tab-<?= $key ?>"
                    onclick="switchTab('<?= $key ?>')">
                    <i class="bi <?= $icon ?> me-1"></i>
                    <?= $label ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- ══ Kargo Tarifeleri ══ -->
        <div class="tab-pane active" id="pane-cargo">
            <div class="card mb-3" style="padding:20px;">
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                    <div>
                        <div class="section-label"><i class="bi bi-box-seam me-2" style="color:#1b84ff;"></i>Bölgesel
                            Kargo Tarifeleri</div>
                        <div style="font-size:.76rem;color:var(--text-muted);margin-top:3px;">Her bölgenin temel ücret
                            ve kg başına fiyatını buradan düzenleyebilirsiniz.</div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Bölge</th>
                                <th>Temel Ücret (₺)</th>
                                <th>Kg Ücreti (₺/kg)</th>
                                <th style="text-align:center;">Değişiklik</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($regions as $r): ?>
                                <tr>
                                    <td style="font-size:.83rem;font-weight:600;">
                                        <?= $r['name'] ?>
                                    </td>
                                    <td style="width:200px;">
                                        <div style="position:relative;">
                                            <span
                                                style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:.8rem;color:var(--text-muted);">₺</span>
                                            <input type="number" class="form-input price-field"
                                                data-original="<?= $r['cargo_base'] ?>" value="<?= $r['cargo_base'] ?>"
                                                step="0.5" min="0" style="padding-left:24px;" oninput="markChanged(this)">
                                        </div>
                                    </td>
                                    <td style="width:200px;">
                                        <div style="position:relative;">
                                            <span
                                                style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:.8rem;color:var(--text-muted);">₺</span>
                                            <input type="number" class="form-input price-field"
                                                data-original="<?= $r['cargo_per_kg'] ?>" value="<?= $r['cargo_per_kg'] ?>"
                                                step="0.10" min="0" style="padding-left:24px;" oninput="markChanged(this)">
                                        </div>
                                    </td>
                                    <td style="text-align:center;" class="change-cell-<?= $r['id'] ?>">
                                        <span style="font-size:.75rem;color:var(--text-muted);">—</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Genel Kargo Ayarları -->
            <div class="card" style="padding:22px;">
                <div class="section-label mb-4"><i class="bi bi-sliders me-2" style="color:#1b84ff;"></i>Genel Kargo
                    Ayarları</div>
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label-sm">Minimum Kargo Ücreti (₺)</label>
                        <div style="position:relative;">
                            <span
                                style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:.8rem;color:var(--text-muted);">₺</span>
                            <input type="number" class="form-input price-field" data-original="35" value="35" step="1"
                                style="padding-left:24px;" oninput="markChanged(this)">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label-sm">Sigorta Oranı (%)</label>
                        <div style="position:relative;">
                            <input type="number" class="form-input price-field" data-original="0.5" value="0.5"
                                step="0.1" min="0" max="10" oninput="markChanged(this)">
                            <span
                                style="position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:.8rem;color:var(--text-muted);">%</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label-sm">KDV Oranı (%)</label>
                        <div style="position:relative;">
                            <input type="number" class="form-input price-field" data-original="20" value="20" step="1"
                                min="0" max="50" oninput="markChanged(this)">
                            <span
                                style="position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:.8rem;color:var(--text-muted);">%</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label-sm">Max Kargo Ağırlığı (kg)</label>
                        <input type="number" class="form-input price-field" data-original="50" value="50" step="5"
                            oninput="markChanged(this)">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label-sm">Max Kargo Hacmi (dm³)</label>
                        <input type="number" class="form-input price-field" data-original="200" value="200" step="10"
                            oninput="markChanged(this)">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label-sm">Hacimsel Ağırlık Böleni</label>
                        <input type="number" class="form-input price-field" data-original="5000" value="5000" step="500"
                            oninput="markChanged(this)">
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ Emanet Tarifeleri ══ -->
        <div class="tab-pane" id="pane-storage" style="display:none;">
            <div class="card mb-3" style="padding:20px;">
                <div class="section-label mb-1"><i class="bi bi-archive me-2" style="color:#1b84ff;"></i>Bölgesel Emanet
                    Tarifeleri</div>
                <div style="font-size:.76rem;color:var(--text-muted);margin-bottom:20px;">Kargo şubeye indiğinde
                    başlayan emanet süresindeki birim ücretler.</div>
                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Şube</th>
                                <th>Saatlik Ücret (₺)</th>
                                <th>Ücretsiz Süre (saat)</th>
                                <th>Bagaj Ücret (₺/saat)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($branches as $b): ?>
                                <tr>
                                    <td style="font-size:.83rem;font-weight:600;">
                                        <?= htmlspecialchars($b['name']) ?>
                                        <div style="font-size:.72rem;color:var(--text-muted);"><?= htmlspecialchars($b['city_name'] ?? '') ?></div>
                                    </td>
                                    <td style="width:180px;">
                                        <div style="position:relative;">
                                            <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:.8rem;color:var(--text-muted);">₺</span>
                                            <input type="number" class="form-input price-field"
                                                data-branch="<?= $b['branch_id'] ?>" data-field="storage_rate"
                                                data-original="<?= $b['storage_hourly_rate'] ?>"
                                                value="<?= $b['storage_hourly_rate'] ?>"
                                                step="0.50" min="0" style="padding-left:24px;" oninput="markChanged(this)">
                                        </div>
                                    </td>
                                    <td style="width:180px;">
                                        <input type="number" class="form-input price-field"
                                            data-branch="<?= $b['branch_id'] ?>" data-field="free_hours"
                                            data-original="<?= $b['free_storage_hours'] ?>"
                                            value="<?= $b['free_storage_hours'] ?>"
                                            step="1" min="0" max="24" oninput="markChanged(this)">
                                    </td>
                                    <td style="width:180px;">
                                        <div style="position:relative;">
                                            <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:.8rem;color:var(--text-muted);">₺</span>
                                            <input type="number" class="form-input price-field"
                                                data-branch="<?= $b['branch_id'] ?>" data-field="baggage_rate"
                                                data-original="<?= $b['baggage_hourly_rate'] ?>"
                                                value="<?= $b['baggage_hourly_rate'] ?>"
                                                step="0.50" min="0" style="padding-left:24px;" oninput="markChanged(this)">
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card" style="padding:22px;">
                <div class="section-label mb-4"><i class="bi bi-bell me-2" style="color:#1b84ff;"></i>Emanet Uyarı
                    Ayarları</div>
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label-sm">İlk SMS Uyarısı (saat sonra)</label>
                        <input type="number" class="form-input" value="24" min="1">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label-sm">Tekrar SMS Aralığı (saat)</label>
                        <input type="number" class="form-input" value="48" min="1">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label-sm">Kritik Uyarı Eşiği (gün)</label>
                        <input type="number" class="form-input" value="7" min="1">
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ Ek Hizmetler ══ -->
        <div class="tab-pane" id="pane-service" style="display:none;">
            <div class="card" style="padding:22px;">
                <div class="section-label mb-4"><i class="bi bi-wrench-adjustable me-2" style="color:#1b84ff;"></i>Ek
                    Hizmet Ücretleri</div>
                <div class="row g-3">
                    <?php foreach ([
                        ['sms_notif', 'SMS Bildirim (alıcıya)', 3.00],
                        ['vip_phone', 'VIP Telefon Bildirimi', 8.00],
                        ['courier', 'Kurye Alım Hizmeti', 25.00],
                        ['insurance', 'Kargo Sigortası (min)', 15.00],
                        ['express', 'Ekspres Teslimat', 20.00],
                        ['fragile', 'Kırılgan Eşya Fark', 10.00],
                        ['cod_fee', 'Kapıda Ödeme Hizmet Bedeli', 12.00],
                        ['packing', 'Paketleme Hizmeti', 18.00],
                    ] as [$key, $label, $default]): ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <label class="form-label-sm">
                                <?= $label ?>
                            </label>
                            <div style="position:relative;">
                                <span
                                    style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:.8rem;color:var(--text-muted);">₺</span>
                                <input type="number" class="form-input price-field" data-original="<?= $default ?>"
                                    value="<?= $default ?>" step="0.50" min="0" style="padding-left:24px;"
                                    oninput="markChanged(this)">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ══ Bağımsız Emanet ══ -->
        <div class="tab-pane" id="pane-indept" style="display:none;">
            <div class="card" style="padding:22px;">
                <div class="section-label mb-1"><i class="bi bi-person-badge me-2" style="color:#1b84ff;"></i>Yolcu
                    Bagajı / Bağımsız Emanet Tarifeleri</div>
                <div style="font-size:.76rem;color:var(--text-muted);margin-bottom:20px;">Kargo harici yolcu eşyası
                    depolama için ayrı tarife uygulanır.</div>
                <div class="row g-3">
                    <?php foreach ([
                        ['Küçük Eşya (Çanta)', 'small', 4.00, 2.00],
                        ['Orta Boy Bavul', 'medium', 6.00, 3.50],
                        ['Büyük Bavul / Valiz', 'large', 8.00, 5.00],
                        ['Koli / Paket', 'parcel', 5.00, 3.00],
                    ] as [$label, $key, $hourly, $daily]): ?>
                        <div class="col-12 col-md-6">
                            <div class="card" style="padding:16px;background:var(--body-bg);">
                                <div style="font-size:.83rem;font-weight:700;margin-bottom:12px;">
                                    <?= $label ?>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label-sm">Saatlik (₺)</label>
                                        <div style="position:relative;">
                                            <span
                                                style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:.8rem;color:var(--text-muted);">₺</span>
                                            <input type="number" class="form-input price-field"
                                                data-original="<?= $hourly ?>" value="<?= $hourly ?>" step="0.50"
                                                style="padding-left:24px;" oninput="markChanged(this)">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label-sm">Günlük (₺)</label>
                                        <div style="position:relative;">
                                            <span
                                                style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:.8rem;color:var(--text-muted);">₺</span>
                                            <input type="number" class="form-input price-field"
                                                data-original="<?= $daily ?>" value="<?= $daily ?>" step="0.50"
                                                style="padding-left:24px;" oninput="markChanged(this)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="col-12 col-md-6">
                        <label class="form-label-sm">Ücretsiz İlk Süre (dk)</label>
                        <input type="number" class="form-input" value="30" min="0" step="15">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-sm">Max Bekleme Süresi (gün)</label>
                        <input type="number" class="form-input" value="3" min="1">
                    </div>
                </div>
            </div>
        </div>

        <!-- Değişiklik bilgi bandı -->
        <div id="changesBar" style="display:none;margin-top:16px;padding:14px 18px;
         background:#e8f1ff;border-radius:8px;border-left:4px solid #1b84ff;
         font-size:.81rem;color:#1b84ff;font-weight:600;
         display:flex;align-items:center;justify-content:space-between;flex-wrap:gap:10px;">
            <span><i class="bi bi-exclamation-circle me-2"></i><span id="changesCount">0</span> alanda değişiklik var —
                kaydedilmedi.</span>
            <button onclick="saveAllPrices()" class="btn-primary-sm"
                style="font-size:.78rem;padding:5px 14px;">Kaydet</button>
        </div>

    </div>

    <!-- Toast -->
    <div id="priceToast" style="position:fixed;bottom:24px;right:24px;z-index:9999;padding:12px 20px;
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
    </style>

    <script>
        var changedCount = 0;

        function switchTab(key) {
            document.querySelectorAll('.pricing-tab').forEach(function (t) { t.classList.remove('active'); });
            document.querySelectorAll('.tab-pane').forEach(function (p) { p.style.display = 'none'; });
            document.getElementById('tab-' + key).classList.add('active');
            document.getElementById('pane-' + key).style.display = '';
        }

        function markChanged(input) {
            var original = parseFloat(input.dataset.original);
            var current = parseFloat(input.value);
            var changed = original !== current;
            input.style.borderColor = changed ? '#1b84ff' : '';
            input.style.background = changed ? '#f0f6ff' : '';

            changedCount = document.querySelectorAll('.price-field').length > 0
                ? Array.from(document.querySelectorAll('.price-field')).filter(function (i) {
                    return parseFloat(i.dataset.original) !== parseFloat(i.value);
                }).length : 0;

            var bar = document.getElementById('changesBar');
            if (changedCount > 0) {
                bar.style.display = 'flex';
                document.getElementById('changesCount').textContent = changedCount;
            } else {
                bar.style.display = 'none';
            }
        }

        function saveAllPrices() {
            var count = changedCount;
            if (count === 0) { showToast('Kaydedilecek değişiklik yok.', 'info'); return; }

            // Collect branch storage rates from data-branch attributes
            var branchData = {};
            document.querySelectorAll('.price-field[data-branch]').forEach(function (inp) {
                var bid   = inp.dataset.branch;
                var field = inp.dataset.field;
                if (!branchData[bid]) branchData[bid] = {};
                branchData[bid][field] = parseFloat(inp.value);
            });

            fetch('api.php?action=pricing.save', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ branches: branchData })
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    document.querySelectorAll('.price-field').forEach(function (i) {
                        i.dataset.original = i.value;
                        i.style.borderColor = '';
                        i.style.background  = '';
                    });
                    changedCount = 0;
                    document.getElementById('changesBar').style.display = 'none';
                    showToast('✓ ' + count + ' fiyat güncellendi (' + (res.updated||0) + ' şube).', 'success');
                } else {
                    showToast('Hata: ' + (res.error || 'Bilinmeyen'), 'error');
                }
            })
            .catch(() => showToast('Sunucu hatası!', 'error'));
        }

        function resetAllPrices() {
            if (!confirm('Tüm fiyatları orijinal değerlere döndürmek istiyor musunuz?')) return;
            document.querySelectorAll('.price-field').forEach(function (i) {
                i.value = i.dataset.original;
                i.style.borderColor = '';
                i.style.background = '';
            });
            changedCount = 0;
            document.getElementById('changesBar').style.display = 'none';
            showToast('Fiyatlar sıfırlandı.', 'info');
        }

        function showToast(msg, type) {
            var t = document.getElementById('priceToast');
            t.style.background = { success: '#0e8045', error: '#c03060', info: '#1b84ff' }[type] || '#1b84ff';
            t.textContent = msg;
            t.style.opacity = '1';
            setTimeout(function () { t.style.opacity = '0'; }, 3000);
        }
    </script>