<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Yeni Sefer Tanımla</div>
            <div class="breadcrumb">
                <a href="?page=dashboard">Ana Sayfa</a>
                <span class="sep">›</span>
                <a href="?page=voyage">Seferler</a>
                <span class="sep">›</span>
                <span>Yeni Sefer</span>
            </div>
        </div>
        <a href="?page=voyage" class="btn-outline-secondary-sm d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Geri Dön
        </a>
    </div>

    <div style="padding:18px 26px 40px;">
        <form id="voyageForm" onsubmit="submitVoyage(event)">
            <div class="row g-3">

                <!-- ══ SOL KOLON ══ -->
                <div class="col-12 col-lg-8">

                    <!-- ── Otobüs Firması & Araç ── -->
                    <div class="card mb-3">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div
                                style="width:32px;height:32px;background:#e8f1ff;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-bus-front" style="color:#1b84ff;"></i>
                            </div>
                            <div class="section-label">Araç & Firma Bilgileri</div>
                        </div>

                        <!-- Firma Kartları -->
                        <label class="form-label-sm mb-2">Otobüs Firması <span style="color:#f31260;">*</span></label>

                        <!-- Firma ARAMA ALANI -->
                        <div style="position:relative;margin-bottom:12px;">
                            <i class="bi bi-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#a3aab8;font-size:.82rem;pointer-events:none;"></i>
                            <input type="text" id="companySearch" class="form-input"
                                   style="padding-left:34px;padding-right:110px;"
                                   placeholder="Firma adı ile ara..."
                                   oninput="searchCompanies(this.value)" />
                            <span style="position:absolute;right:11px;top:50%;transform:translateY(-50%);font-size:.68rem;color:var(--text-muted);pointer-events:none;white-space:nowrap;">
                                <i class="bi bi-star-fill" style="color:#e08b00;font-size:.65rem;"></i> En çok 5 firma
                            </span>
                        </div>

                        <!-- Bulunamadı uyarısı -->
                        <div id="noCompanyResult" style="display:none;padding:10px 12px;background:var(--body-bg);border-radius:7px;font-size:.8rem;color:var(--text-muted);text-align:center;margin-bottom:8px;">
                            <i class="bi bi-search me-1"></i>Sonuç bulunamadı.
                            <button type="button" onclick="openNewCompanyModal()" class="btn-outline-secondary-sm ms-2" style="font-size:.74rem;padding:2px 10px;">+ Yeni Ekle</button>
                        </div>

                        <div class="row g-2 mb-3" id="companyCardRow">
                            <?php
                            $companies = [
                                ['key' => 'metro', 'name' => 'Metro Turizm', 'color' => '#e8f1ff', 'icon_color' => '#1b84ff', 'comm' => 15],
                                ['key' => 'pamukkale', 'name' => 'Pamukkale', 'color' => '#e7f9f0', 'icon_color' => '#0e8045', 'comm' => 15],
                                ['key' => 'uludag', 'name' => 'Uludağ Turizm', 'color' => '#fff8ec', 'icon_color' => '#e08b00', 'comm' => 12],
                                ['key' => 'kamil', 'name' => 'Kamil Koç', 'color' => '#f3e5f5', 'icon_color' => '#8e24aa', 'comm' => 13],
                                ['key' => 'varan', 'name' => 'Varan Turizm', 'color' => '#fff0f2', 'icon_color' => '#c03060', 'comm' => 14],
                            ];
                            foreach ($companies as $c): ?>
                                <div class="col-6 col-md-4 company-col" data-name="<?= strtolower($c['name']) ?>">
                                    <label class="company-card" id="cc-<?= $c['key'] ?>"
                                        onclick="selectCompany('<?= $c['key'] ?>',<?= $c['comm'] ?>)">
                                        <input type="radio" name="company" value="<?= $c['key'] ?>" style="display:none;" />
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                style="width:32px;height:32px;background:<?= $c['color'] ?>;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                <i class="bi bi-bus-front"
                                                    style="color:<?= $c['icon_color'] ?>;font-size:.85rem;"></i>
                                            </div>
                                            <div>
                                                <div style="font-size:.8rem;font-weight:600;">
                                                    <?= $c['name'] ?>
                                                </div>
                                                <div style="font-size:.7rem;color:var(--text-muted);">Kom. %
                                                    <?= $c['comm'] ?>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            <!-- ── Yeni Firma Ekle Kartı ── -->
                            <div class="col-6 col-md-4 company-col" data-name="__add__" id="addCompanyCol">
                                <label class="company-card add-company-card" onclick="openNewCompanyModal()"
                                    style="cursor:pointer;border-style:dashed;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div
                                            style="width:32px;height:32px;background:var(--body-bg);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1.5px dashed var(--border-color);">
                                            <i class="bi bi-plus-lg"
                                                style="color:var(--text-muted);font-size:.9rem;"></i>
                                        </div>
                                        <div>
                                            <div style="font-size:.8rem;font-weight:600;color:var(--text-muted);">Yeni
                                                Firma</div>
                                            <div style="font-size:.7rem;color:var(--text-muted);">&nbsp;</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <!-- Dinamik firma kartları buraya eklenir -->
                        <div id="dynamicCompanies" class="row g-2 mb-2"></div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Plaka <span style="color:#f31260;">*</span></label>
                                <input type="text" id="plate" class="form-input" placeholder="34 ABC 001"
                                    oninput="this.value=this.value.toUpperCase()" required />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Araç Tipi</label>
                                <select id="vehicleType" class="form-input">
                                    <option value="standard">Standart Otobüs</option>
                                    <option value="vip">VIP / Lüks</option>
                                    <option value="minibus">Minibüs</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- ── Şoför & Sefer Bilgileri ── -->
                    <div class="card mb-3">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div
                                style="width:32px;height:32px;background:#e7f9f0;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-person-workspace" style="color:#0e8045;"></i>
                            </div>
                            <div class="section-label">Şoför & Sefer Bilgileri</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Şoför Adı Soyadı</label>
                                <input type="text" id="driverName" class="form-input" placeholder="Ad Soyad" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Şoför Telefonu</label>
                                <input type="tel" id="driverPhone" class="form-input" placeholder="0 5xx xxx xx xx" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Kalkış Tarihi & Saati <span
                                        style="color:#f31260;">*</span></label>
                                <input type="datetime-local" id="departureTime" class="form-input"
                                    value="<?= date('Y-m-d\TH:i') ?>" required />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Tahmini Varış</label>
                                <input type="datetime-local" id="arrivalTime" class="form-input" />
                            </div>
                        </div>
                    </div>

                    <!-- ── Güzergah ── -->
                    <div class="card mb-3">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div
                                style="width:32px;height:32px;background:#fff8ec;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-map" style="color:#e08b00;"></i>
                            </div>
                            <div class="section-label">Güzergah</div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Kalkış Noktası <span
                                        style="color:#f31260;">*</span></label>
                                <input type="text" id="originCity" class="form-input" placeholder="İstanbul"
                                    value="İstanbul" required />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Son Varış Noktası <span
                                        style="color:#f31260;">*</span></label>
                                <input type="text" id="destCity" class="form-input" placeholder="Ankara" required />
                            </div>
                        </div>

                        <!-- Ara Duraklar -->
                        <label class="form-label-sm mb-2">Ara Duraklar</label>
                        <div id="stopsContainer" class="d-flex flex-column gap-2 mb-2">
                            <div class="stop-row d-flex gap-2 align-items-center">
                                <input type="text" class="form-input stop-input" placeholder="Ara durak şehri..." />
                                <button type="button" class="icon-btn-circle" style="flex-shrink:0;"
                                    onclick="removeStop(this)" title="Kaldır">
                                    <i class="bi bi-x" style="color:#c03060;font-size:.85rem;"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" onclick="addStop()"
                            class="btn-outline-secondary-sm d-flex align-items-center gap-1" style="font-size:.78rem;">
                            <i class="bi bi-plus-lg"></i> Ara Durak Ekle
                        </button>
                    </div>

                    <!-- ── Komisyon & Ücret ── -->
                    <div class="card">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div
                                style="width:32px;height:32px;background:#f3e5f5;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-percent" style="color:#8e24aa;"></i>
                            </div>
                            <div class="section-label">Komisyon & Ödeme</div>
                        </div>
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">Komisyon Oranı (%)</label>
                                <div style="position:relative;">
                                    <input type="number" id="commRate" class="form-input" value="15" min="0" max="100"
                                        step="0.5" style="padding-right:38px;" oninput="updatePreview()" />
                                    <span
                                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:.8rem;color:var(--text-muted);">%</span>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">Şoföre Ödeme Yöntemi</label>
                                <select id="payMethod" class="form-input">
                                    <option value="nakit">Nakit</option>
                                    <option value="havale">Havale / EFT</option>
                                    <option value="kart">Kredi Kartı / POS</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">IBAN (opsiyonel)</label>
                                <input type="text" id="iban" class="form-input" placeholder="TR00 0000..."
                                    style="font-size:.78rem;" />
                            </div>
                            <div class="col-12">
                                <label class="form-label-sm">Notlar</label>
                                <input type="text" id="tripNote" class="form-input"
                                    placeholder="Kırılgan kargo var, özel talimat..." />
                            </div>
                        </div>
                    </div>

                </div><!-- /col-8 -->

                <!-- ══ SAĞ KOLON — Özet & Kaydet ══ -->
                <div class="col-12 col-lg-4">

                    <!-- Sefer Özet Kartı -->
                    <div class="card mb-3" style="background:var(--body-bg);">
                        <div class="section-label mb-3">Sefer Özeti</div>
                        <div class="d-flex flex-column gap-2" style="font-size:.83rem;">
                            <div class="d-flex justify-content-between">
                                <span style="color:var(--text-muted);">Firma</span>
                                <span id="prev-company" style="font-weight:600;">—</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span style="color:var(--text-muted);">Plaka</span>
                                <span id="prev-plate" style="font-weight:600;font-family:monospace;">—</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span style="color:var(--text-muted);">Güzergah</span>
                                <span id="prev-route"
                                    style="font-weight:600;font-size:.79rem;text-align:right;max-width:55%;">—</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span style="color:var(--text-muted);">Kalkış</span>
                                <span id="prev-dep" style="font-weight:600;">—</span>
                            </div>
                            <hr style="border-color:var(--border-color);margin:4px 0;" />
                            <div class="d-flex justify-content-between">
                                <span style="color:var(--text-muted);">Komisyon</span>
                                <span id="prev-comm" style="font-weight:600;color:#c03060;">%15</span>
                            </div>
                        </div>
                    </div>

                    <!-- Planlı Sevk -->
                    <div class="card mb-3">
                        <div class="section-label mb-3">Bu Sefere Eklenecek Kargolar</div>
                        <div style="font-size:.78rem;color:var(--text-muted);">
                            <i class="bi bi-info-circle me-1"></i>
                            Sefer kaydı oluşturduktan sonra <a href="?page=dispatch"
                                style="color:var(--accent-blue);text-decoration:none;">Kargo Sevk</a> sayfasından bu
                            sefere kargo ekleyebilirsiniz.
                        </div>
                        <div class="mt-3">
                            <div style="padding:10px;background:var(--body-bg);border-radius:8px;text-align:center;">
                                <div style="font-size:1.4rem;opacity:.3;margin-bottom:4px;"><i class="bi bi-boxes"></i>
                                </div>
                                <div style="font-size:.75rem;color:var(--text-muted);">Henüz kargo atanmadı</div>
                            </div>
                        </div>
                    </div>

                    <!-- Aksiyon -->
                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn-primary-sm w-100"
                            style="height:44px;font-size:.9rem;border-radius:8px;">
                            <i class="bi bi-check2-circle me-1"></i> Seferi Kaydet
                        </button>
                        <a href="?page=dispatch"
                            class="btn-outline-secondary-sm w-100 d-flex align-items-center justify-content-center gap-1"
                            style="height:38px;font-size:.84rem;border-radius:8px;">
                            <i class="bi bi-send"></i> Kaydet &amp; Kargo Sevke Geç
                        </a>
                        <button type="reset" class="btn-outline-secondary-sm w-100"
                            style="height:36px;font-size:.82rem;border-radius:8px;color:var(--text-muted);"
                            onclick="resetPreview()">
                            <i class="bi bi-x-lg me-1"></i> Temizle
                        </button>
                    </div>

                </div><!-- /col-4 -->

            </div>
        </form>
    </div><!-- /body -->

</main>

<!-- ══ Yeni Otobüs Firması Modal ══ -->
<div id="newCompanyModal"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9998;align-items:center;justify-content:center;">
    <div
        style="background:var(--card-bg);border-radius:12px;padding:28px;width:460px;max-width:96vw;box-shadow:0 20px 60px rgba(0,0,0,.3);">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <div style="font-size:1rem;font-weight:700;">Yeni Otobüs Firması</div>
                <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px;">Sisteme yeni bir taşıyıcı firma
                    ekleyin</div>
            </div>
            <button onclick="closeNewCompanyModal()"
                style="border:none;background:none;cursor:pointer;font-size:1.3rem;color:var(--text-muted);line-height:1;">&times;</button>
        </div>

        <div class="d-flex flex-column gap-3">
            <div class="row g-3">
                <div class="col-12 col-md-8">
                    <label class="modal-label">Firma Adı <span style="color:#f31260;">*</span></label>
                    <input type="text" id="nc-name" class="form-input" placeholder="Metro Turizm A.Ş." />
                </div>
                <div class="col-12 col-md-4">
                    <label class="modal-label">Komisyon (%)</label>
                    <div style="position:relative;">
                        <input type="number" id="nc-comm" class="form-input" value="15" min="0" max="100" step="0.5"
                            style="padding-right:28px;" />
                        <span
                            style="position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:.8rem;color:var(--text-muted);">%</span>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <label class="modal-label">Yetkili / Koordinatör</label>
                    <input type="text" id="nc-contact" class="form-input" placeholder="Mehmet Kara" />
                </div>
                <div class="col-12 col-md-6">
                    <label class="modal-label">Telefon</label>
                    <input type="tel" id="nc-phone" class="form-input" placeholder="0212 xxx xx xx" />
                </div>
                <div class="col-12">
                    <label class="modal-label">IBAN (opsiyonel)</label>
                    <input type="text" id="nc-iban" class="form-input" placeholder="TR00 0000 ..."
                        style="font-size:.79rem;" />
                </div>
                <!-- Renk / İkon Seçimi -->
                <div class="col-12">
                    <label class="modal-label mb-2">Kart Rengi</label>
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="color-chip active" data-bg="#e8f1ff" data-ic="#1b84ff"
                            onclick="selectColor(this)" style="background:#e8f1ff;border-color:#1b84ff;">Mavi</button>
                        <button type="button" class="color-chip" data-bg="#e7f9f0" data-ic="#0e8045"
                            onclick="selectColor(this)" style="background:#e7f9f0;">Yeşil</button>
                        <button type="button" class="color-chip" data-bg="#fff8ec" data-ic="#e08b00"
                            onclick="selectColor(this)" style="background:#fff8ec;">Turuncu</button>
                        <button type="button" class="color-chip" data-bg="#f3e5f5" data-ic="#8e24aa"
                            onclick="selectColor(this)" style="background:#f3e5f5;">Mor</button>
                        <button type="button" class="color-chip" data-bg="#e9ecef" data-ic="#495057"
                            onclick="selectColor(this)" style="background:#e9ecef;">Gri</button>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-1">
                <button onclick="saveNewCompany()" class="btn-primary-sm flex-grow-1" style="height:42px;">
                    <i class="bi bi-check2-circle me-1"></i> Firmayı Ekle & Seç
                </button>
                <button onclick="closeNewCompanyModal()" class="btn-outline-secondary-sm"
                    style="height:42px;padding:0 18px;">İptal</button>
            </div>
        </div>

    </div>
</div>

<style>
    .form-label-sm {
        display: block;
        font-size: .72rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .03em;
        margin-bottom: 4px;
    }

    .form-input {
        width: 100%;
        border: 1px solid var(--border-color);
        border-radius: 7px;
        padding: 8px 12px;
        font-size: .84rem;
        background: var(--body-bg);
        color: var(--text-dark);
        outline: none;
        transition: border-color .15s, box-shadow .15s;
        font-family: 'Inter', sans-serif;
    }

    .form-input:focus {
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 3px rgba(27, 132, 255, .12);
    }

    /* Firma Seçim Kartları */
    .company-card {
        display: block;
        cursor: pointer;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        padding: 10px 12px;
        transition: border-color .15s, background .15s;
    }

    .company-card:hover {
        background: var(--body-bg);
    }

    .company-card.active {
        border-color: var(--accent-blue);
        background: #f0f6ff;
    }

    body.dark .company-card.active {
        background: rgba(27, 132, 255, .12);
    }

    /* Renk chip */
    .color-chip {
        padding: 5px 12px;
        border-radius: 6px;
        font-size: .75rem;
        font-weight: 600;
        border: 2px solid transparent;
        cursor: pointer;
        color: #333;
        transition: border-color .15s, transform .1s;
    }

    .color-chip.active {
        border-color: #1b84ff;
        transform: scale(1.08);
    }

    body.dark .color-chip {
        color: var(--text-dark);
    }

    /* Modal label */
    .modal-label {
        display: block;
        font-size: .72rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .03em;
        margin-bottom: 4px;
    }
</style>

<script>
    var selectedCompany = '';
    var companyNames = {
        metro: 'Metro Turizm', pamukkale: 'Pamukkale', uludag: 'Uludağ Turizm',
        kamil: 'Kamil Koç', varan: 'Varan Turizm'
    };
    var dynamicCompanyCount = 0;
    var selectedColor = { bg: '#e8f1ff', ic: '#1b84ff' };

    /* ── Firma Ara ── */
    function searchCompanies(q) {
        q = q.trim().toLowerCase();
        var cols = document.querySelectorAll('.company-col');
        var anyVisible = false;

        cols.forEach(function(col) {
            var name = col.getAttribute('data-name');
            if (name === '__add__') {
                /* "+ Yeni Firma" kartını arama sırasında gizle, boşsa göster */
                col.style.display = q ? 'none' : '';
                return;
            }
            var match = !q || name.includes(q);
            col.style.display = match ? '' : 'none';
            if (match) anyVisible = true;
        });

        /* Dinamik eklenen kartlar da filtrele */
        document.querySelectorAll('#dynamicCompanies .company-col').forEach(function(col) {
            var name = col.getAttribute('data-name') || '';
            var match = !q || name.includes(q);
            col.style.display = match ? '' : 'none';
            if (match) anyVisible = true;
        });

        document.getElementById('noCompanyResult').style.display = (!anyVisible && q) ? 'block' : 'none';
    }

    /* ── Firma Seç ── */

    function selectCompany(key, commRate) {
        selectedCompany = key;
        document.querySelectorAll('.company-card').forEach(function (c) { c.classList.remove('active'); });
        document.getElementById('cc-' + key).classList.add('active');
        document.getElementById('commRate').value = commRate;
        updatePreview();
    }

    /* ── Önizleme Güncelle ── */
    function updatePreview() {
        document.getElementById('prev-company').textContent = selectedCompany ? companyNames[selectedCompany] : '—';
        document.getElementById('prev-plate').textContent = document.getElementById('plate').value.trim() || '—';
        var origin = document.getElementById('originCity').value.trim() || 'İstanbul';
        var dest = document.getElementById('destCity').value.trim() || '—';
        document.getElementById('prev-route').textContent = origin + ' → ' + dest;
        var dep = document.getElementById('departureTime').value;
        if (dep) {
            var d = new Date(dep);
            document.getElementById('prev-dep').textContent = d.toLocaleDateString('tr-TR', { day: '2-digit', month: '2-digit' }) + ' ' + d.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
        }
        document.getElementById('prev-comm').textContent = '%' + (document.getElementById('commRate').value || 0);
    }

    /* Canlı önizleme */
    ['plate', 'originCity', 'destCity', 'departureTime', 'commRate'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('input', updatePreview);
    });

    /* ── Ara Durak ── */
    function addStop() {
        var div = document.createElement('div');
        div.className = 'stop-row d-flex gap-2 align-items-center';
        div.innerHTML = '<input type="text" class="form-input stop-input" placeholder="Ara durak şehri..." />' +
            '<button type="button" class="icon-btn-circle" style="flex-shrink:0;" onclick="removeStop(this)" title="Kaldır">' +
            '<i class="bi bi-x" style="color:#c03060;font-size:.85rem;"></i></button>';
        document.getElementById('stopsContainer').appendChild(div);
    }
    function removeStop(btn) { btn.closest('.stop-row').remove(); }

    /* ── Submit ── */
    function submitVoyage(e) {
        e.preventDefault();
        if (!selectedCompany) { showToast('Lütfen bir firma seçin!', 'error'); return; }
        showToast('✓ Sefer başarıyla kaydedildi!', 'success');
        /* TODO: AJAX */
    }
    function resetPreview() {
        selectedCompany = '';
        document.querySelectorAll('.company-card').forEach(c => c.classList.remove('active'));
        ['prev-company', 'prev-plate', 'prev-route', 'prev-dep'].forEach(id => document.getElementById(id).textContent = '—');
        document.getElementById('prev-comm').textContent = '%15';
    }

    /* ── Yeni Firma Modal ── */
    function openNewCompanyModal() {
        document.getElementById('newCompanyModal').style.display = 'flex';
        document.getElementById('nc-name').focus();
    }
    function closeNewCompanyModal() {
        document.getElementById('newCompanyModal').style.display = 'none';
    }

    function selectColor(btn) {
        document.querySelectorAll('.color-chip').forEach(function (c) { c.classList.remove('active'); });
        btn.classList.add('active');
        selectedColor = { bg: btn.dataset.bg, ic: btn.dataset.ic };
    }

    function saveNewCompany() {
        var name = document.getElementById('nc-name').value.trim();
        if (!name) { showToast('Firma adı zorunludur!', 'error'); return; }

        var comm = parseFloat(document.getElementById('nc-comm').value) || 15;
        var key = 'dynamic_' + (++dynamicCompanyCount);
        var bg = selectedColor.bg;
        var ic = selectedColor.ic;

        /* Adı kaydet */
        companyNames[key] = name;

        /* Kart oluştur */
        var col = document.createElement('div');
        col.className = 'col-6 col-md-4 company-col';
        col.setAttribute('data-name', name.toLowerCase());
        col.innerHTML =
            '<label class="company-card" id="cc-' + key + '" onclick="selectCompany(\'' + key + '\',' + comm + ')">' +
            '<div class="d-flex align-items-center gap-2">' +
            '<div style="width:32px;height:32px;background:' + bg + ';border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">' +
            '<i class="bi bi-bus-front" style="color:' + ic + ';font-size:.85rem;"></i>' +
            '</div>' +
            '<div>' +
            '<div style="font-size:.8rem;font-weight:600;">' + name + '</div>' +
            '<div style="font-size:.7rem;color:var(--text-muted);">Kom. %' + comm + '</div>' +
            '</div>' +
            '</div>' +
            '</label>';

        document.getElementById('dynamicCompanies').appendChild(col);

        closeNewCompanyModal();
        /* Modal alanlarını temizle */
        document.getElementById('nc-name').value = '';
        document.getElementById('nc-comm').value = '15';
        document.getElementById('nc-contact').value = '';
        document.getElementById('nc-phone').value = '';
        document.getElementById('nc-iban').value = '';

        /* Yeni firmayı otomatik seç */
        selectCompany(key, comm);
        showToast('✓ ' + name + ' eklendi ve seçildi.', 'success');
    }

    /* ── Toast ── */
    function showToast(msg, type) {
        var colors = { success: '#0e8045', error: '#c03060', info: '#1b84ff' };
        var t = document.createElement('div');
        t.style.cssText = 'position:fixed;bottom:24px;right:24px;padding:11px 18px;border-radius:8px;font-size:.82rem;font-weight:600;z-index:9999;box-shadow:0 4px 14px rgba(0,0,0,.18);color:#fff;background:' + (colors[type] || '#333') + ';transition:opacity .3s;';
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(function () { t.style.opacity = '0'; setTimeout(function () { t.remove(); }, 300); }, 3000);
    }
</script>