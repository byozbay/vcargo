<?php
require_once __DIR__ . '/../../core/autoload.php';
$base    = new BaseModel();
$regions = $base->query("SELECT region_id AS id, name FROM regions WHERE is_active=1 ORDER BY name");
$cities  = $base->query("SELECT city_id, name FROM cities WHERE is_active=1 ORDER BY name");
?>
<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Yeni Şube Oluştur</div>
            <div class="breadcrumb">
                <a href="?page=dashboard" style="color:var(--text-muted);">Dashboard</a>
                <span class="sep">·</span>
                <a href="?page=branches" style="color:var(--text-muted);">Şube Listesi</a>
                <span class="sep">·</span>
                <span style="color:var(--text-muted);">Yeni Şube</span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="?page=branches" class="btn-outline-secondary-sm d-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Geri
            </a>
            <button type="button" class="btn-primary-sm d-flex align-items-center gap-1" onclick="submitBranch()">
                <i class="bi bi-check-lg"></i> Şubeyi Kaydet
            </button>
        </div>
    </div>

    <div style="padding:18px 26px 40px;">
        <form id="branchForm" onsubmit="submitBranch(event)">

            <div class="row g-3">

                <!-- ═══ Sol kolon — Ana Bilgiler ═══ -->
                <div class="col-12 col-lg-8">

                    <!-- Temel Bilgiler -->
                    <div class="card mb-3" style="padding:22px;">
                        <div class="section-label mb-4">
                            <i class="bi bi-shop me-2" style="color:#1b84ff;"></i>Şube Temel Bilgileri
                        </div>
                        <div class="row g-3">

                            <!-- Şube Adı -->
                            <div class="col-12 col-md-8">
                                <label class="form-label-sm">Şube Adı <span style="color:#c03060;">*</span></label>
                                <input type="text" class="form-input" id="branchName"
                                    placeholder="ör. Ankara Otogar Şubesi" required>
                            </div>

                            <!-- Şube Türü -->
                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">Şube Türü <span style="color:#c03060;">*</span></label>
                                <div class="d-flex gap-2 mt-1">
                                    <label class="type-chip active" id="chip-corporate"
                                        onclick="selectType('CORPORATE')">
                                        <i class="bi bi-building"></i> Merkez
                                    </label>
                                    <label class="type-chip" id="chip-franchise" onclick="selectType('FRANCHISE')">
                                        <i class="bi bi-shop"></i> Bayi
                                    </label>
                                </div>
                                <input type="hidden" id="branchType" value="CORPORATE">
                            </div>

                            <!-- Bölge -->
                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">Bölge <span style="color:#c03060;">*</span></label>
                                <select class="form-input" id="branchRegion" required>
                                    <option value="">Seçiniz...</option>
                                    <?php foreach ($regions as $r): ?>
                                        <option value="<?= $r['id'] ?>">
                                            <?= $r['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Şehir -->
                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">Şehir <span style="color:#c03060;">*</span></label>
                                <select class="form-input" id="branchCity" required>
                                    <option value=""></option>
                                    <option value="">Seçiniz...</option>
                                    <?php foreach ($cities as $c): ?>
                                        <option value="<?= $c['city_id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- İlçe -->
                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">İlçe</label>
                                <input type="text" class="form-input" id="branchDistrict" placeholder="İlçe adı">
                            </div>

                            <!-- Tam Adres -->
                            <div class="col-12">
                                <label class="form-label-sm">Tam Adres <span style="color:#c03060;">*</span></label>
                                <textarea class="form-input" id="branchAddress" rows="2"
                                    placeholder="Otogar terminal binası, kapı no, posta kodu..." required></textarea>
                            </div>

                        </div>
                    </div>

                    <!-- İletişim Bilgileri -->
                    <div class="card mb-3" style="padding:22px;">
                        <div class="section-label mb-4">
                            <i class="bi bi-telephone me-2" style="color:#1b84ff;"></i>İletişim Bilgileri
                        </div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Şube Telefonu <span style="color:#c03060;">*</span></label>
                                <input type="text" class="form-input" id="branchPhone" placeholder="0___ ___ __ __"
                                    required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Şube E-posta</label>
                                <input type="email" class="form-input" id="branchEmail"
                                    placeholder="sube@vcargo.com.tr">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Müdür Adı Soyadı <span
                                        style="color:#c03060;">*</span></label>
                                <input type="text" class="form-input" id="managerName" placeholder="Ad Soyad" required
                                    oninput="updatePreview()">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Müdür Telefonu <span
                                        style="color:#c03060;">*</span></label>
                                <input type="text" class="form-input" id="managerPhone" placeholder="0___ ___ __ __"
                                    required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Müdür T.C. Kimlik No</label>
                                <input type="text" class="form-input" id="managerTc"
                                    placeholder="11 haneli T.C. numarası" maxlength="11">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Müdür E-posta</label>
                                <input type="email" class="form-input" id="managerEmail"
                                    placeholder="mudur@vcargo.com.tr">
                            </div>
                        </div>
                    </div>

                    <!-- Finans & Operasyon Ayarları -->
                    <div class="card mb-3" style="padding:22px;">
                        <div class="section-label mb-4">
                            <i class="bi bi-wallet2 me-2" style="color:#1b84ff;"></i>Operasyon Ayarları
                        </div>
                        <div class="row g-3">

                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">Emanet Ücreti (₺/saat)</label>
                                <input type="number" class="form-input" id="storageRate" value="5.00" step="0.50"
                                    min="0" oninput="updatePreview()">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">Ücretsiz Emanet Süresi (saat)</label>
                                <input type="number" class="form-input" id="storageFreeHours" value="2" min="0"
                                    max="24">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">Cari Limit (₺)</label>
                                <input type="number" class="form-input" id="creditLimit" value="10000" step="1000"
                                    min="0" oninput="updatePreview()">
                            </div>

                            <!-- Ödeme Yöntemleri -->
                            <div class="col-12">
                                <label class="form-label-sm mb-2">İzin Verilen Ödeme Yöntemleri</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    <label class="pay-chip active" id="pay-cash" onclick="togglePay(this)">
                                        <i class="bi bi-cash"></i> Nakit
                                    </label>
                                    <label class="pay-chip active" id="pay-card" onclick="togglePay(this)">
                                        <i class="bi bi-credit-card"></i> Kart
                                    </label>
                                    <label class="pay-chip active" id="pay-current" onclick="togglePay(this)">
                                        <i class="bi bi-person-badge"></i> Cari
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">IBAN</label>
                                <input type="text" class="form-input" id="branchIban"
                                    placeholder="TR__ ____ ____ ____ ____ ____ __" maxlength="32">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Vergi No / T.C.</label>
                                <input type="text" class="form-input" id="branchTaxNo"
                                    placeholder="Vergi numarası veya T.C." maxlength="11">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Vergi Dairesi</label>
                                <input type="text" class="form-input" id="branchTaxOffice"
                                    placeholder="ör. Çankaya Vergi Dairesi">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Başlangıç Tarihi</label>
                                <input type="date" class="form-input" id="branchStart" value="<?= date('Y-m-d') ?>">
                            </div>

                            <!-- Notlar -->
                            <div class="col-12">
                                <label class="form-label-sm">Dahili Notlar</label>
                                <textarea class="form-input" id="branchNotes" rows="2"
                                    placeholder="Şube hakkında dahili not..."></textarea>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- ═══ Sağ kolon — Önizleme + Durum ═══ -->
                <div class="col-12 col-lg-4">

                    <!-- Önizleme Kartı -->
                    <div class="card mb-3" style="padding:20px;position:sticky;top:80px;">
                        <div class="section-label mb-3">Şube Özeti</div>

                        <div style="background:var(--body-bg);border-radius:8px;padding:16px;margin-bottom:16px;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div id="prev-icon"
                                    style="width:48px;height:48px;border-radius:10px;background:#e8f1ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="bi bi-building" style="color:#1b84ff;font-size:1.3rem;"></i>
                                </div>
                                <div>
                                    <div id="prev-name" style="font-size:.9rem;font-weight:700;color:var(--text-dark);">
                                        Şube Adı</div>
                                    <div id="prev-type" style="font-size:.72rem;color:var(--text-muted);">Merkez Şube
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-2" style="font-size:.78rem;">
                                <div class="d-flex justify-content-between">
                                    <span style="color:var(--text-muted);">Bölge</span>
                                    <span id="prev-region" style="font-weight:600;">—</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span style="color:var(--text-muted);">Şehir</span>
                                    <span id="prev-city" style="font-weight:600;">—</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span style="color:var(--text-muted);">Müdür</span>
                                    <span id="prev-manager" style="font-weight:600;">—</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span style="color:var(--text-muted);">Emanet Ücreti</span>
                                    <span id="prev-storage" style="font-weight:600;">₺5.00/saat</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span style="color:var(--text-muted);">Cari Limit</span>
                                    <span id="prev-credit" style="font-weight:600;">₺10.000</span>
                                </div>
                            </div>
                        </div>

                        <!-- Durum seçimi -->
                        <div class="mb-3">
                            <label class="form-label-sm mb-2">Başlangıç Durumu</label>
                            <select class="form-input" id="branchStatus">
                                <option value="active">Aktif — Hemen operasyona aç</option>
                                <option value="pending">Onay Bekliyor — Sonra aktif et</option>
                            </select>
                        </div>

                        <button type="button" onclick="submitBranch()"
                            class="btn-primary-sm w-100 d-flex align-items-center justify-content-center gap-2"
                            style="padding:10px;">
                            <i class="bi bi-check-lg"></i> Şubeyi Kaydet
                        </button>
                        <button type="button" onclick="resetForm()"
                            class="btn-outline-secondary-sm w-100 d-flex align-items-center justify-content-center gap-2 mt-2"
                            style="padding:9px;">
                            <i class="bi bi-x-lg"></i> Formu Temizle
                        </button>
                    </div>

                    <!-- Uyarılar -->
                    <div class="card" style="padding:18px;border-left:4px solid #e08b00;">
                        <div style="font-size:.78rem;font-weight:700;color:#e08b00;margin-bottom:8px;">
                            <i class="bi bi-info-circle me-1"></i>Dikkat
                        </div>
                        <ul
                            style="font-size:.76rem;color:var(--text-muted);padding-left:16px;margin:0;line-height:1.8;">
                            <li>Şube oluşturulduktan sonra müdüre otomatik e-posta gönderilir.</li>
                            <li>Müdür, ilk girişte şifresini belirleyecektir.</li>
                            <li>Cari limit ve emanet ücretleri sonradan değiştirilebilir.</li>
                            <li>Şube kodu sistem tarafından otomatik atanır.</li>
                        </ul>
                    </div>

                </div>

            </div><!-- /row -->
        </form>
    </div>

    <!-- Toast -->
    <div id="bcToast"
        style="position:fixed;bottom:24px;right:24px;z-index:9999;padding:12px 20px;border-radius:6px;
     font-size:.82rem;font-weight:600;background:#1b84ff;color:#fff;opacity:0;transition:opacity .3s;pointer-events:none;"></div>

    <style>
        .form-label-sm {
            display: block;
            font-size: .72rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .03em;
            margin-bottom: 5px;
        }

        .type-chip,
        .pay-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 14px;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            transition: border-color .15s, background .15s;
            background: var(--card-bg);
            color: var(--text-dark);
            user-select: none;
        }

        .type-chip.active,
        .pay-chip.active {
            border-color: #1b84ff;
            background: #e8f1ff;
            color: #1b84ff;
        }

        body.dark .type-chip.active,
        body.dark .pay-chip.active {
            background: rgba(27, 132, 255, .15);
        }
    </style>

    <script>
        var selectedType = 'CORPORATE';

        /* ── Şube Türü Seç ── */
        function selectType(type) {
            selectedType = type;
            document.getElementById('branchType').value = type;
            document.querySelectorAll('.type-chip').forEach(function (c) { c.classList.remove('active'); });
            document.getElementById(type === 'CORPORATE' ? 'chip-corporate' : 'chip-franchise').classList.add('active');

            var icon = document.getElementById('prev-icon');
            icon.querySelector('i').className = type === 'CORPORATE' ? 'bi bi-building' : 'bi bi-shop';
            icon.style.background = type === 'CORPORATE' ? '#e8f1ff' : '#f3e5f5';
            icon.querySelector('i').style.color = type === 'CORPORATE' ? '#1b84ff' : '#8e24aa';

            document.getElementById('prev-type').textContent = type === 'CORPORATE' ? 'Merkez Şube' : 'Bayi Şube';
            updatePreview();
        }

        /* ── Ödeme Toggle ── */
        function togglePay(el) {
            el.classList.toggle('active');
        }

        /* ── Önizleme Güncelle ── */
        function updatePreview() {
            var name = document.getElementById('branchName').value || 'Şube Adı';
            var region = document.getElementById('branchRegion');
            var city = document.getElementById('branchCity');
            var manager = document.getElementById('managerName').value || '—';
            var storage = parseFloat(document.getElementById('storageRate').value || 5).toFixed(2);
            var credit = parseInt(document.getElementById('creditLimit').value || 10000);

            document.getElementById('prev-name').textContent = name;
            document.getElementById('prev-region').textContent = region.options[region.selectedIndex]?.text || '—';
            document.getElementById('prev-city').textContent = city.options[city.selectedIndex]?.text || '—';
            document.getElementById('prev-manager').textContent = manager;
            document.getElementById('prev-storage').textContent = '₺' + storage + '/saat';
            document.getElementById('prev-credit').textContent = '₺' + credit.toLocaleString('tr-TR');
        }

        /* ── Bağlantı ── */
        document.getElementById('branchName').addEventListener('input', updatePreview);
        document.getElementById('branchRegion').addEventListener('change', updatePreview);
        document.getElementById('branchCity').addEventListener('change', updatePreview);
        document.getElementById('creditLimit').addEventListener('input', updatePreview);
        document.getElementById('storageRate').addEventListener('input', updatePreview);

        /* ── Submit ── */
        function submitBranch(e) {
            if (e) e.preventDefault();
            var name = document.getElementById('branchName').value.trim();
            if (!name) { showToast('Şube adı zorunludur!', 'error'); document.getElementById('branchName').focus(); return; }
            if (!document.getElementById('branchRegion').value) { showToast('Bölge seçiniz!', 'error'); return; }
            if (!document.getElementById('branchCity').value) { showToast('Şehir seçiniz!', 'error'); return; }
            if (!document.getElementById('branchAddress').value.trim()) { showToast('Adres zorunludur!', 'error'); return; }
            if (!document.getElementById('managerName').value.trim()) { showToast('Müdür adı zorunludur!', 'error'); return; }
            if (!document.getElementById('branchPhone').value.trim()) { showToast('Şube telefonu zorunludur!', 'error'); return; }
            if (!document.getElementById('managerPhone').value.trim()) { showToast('Müdür telefonu zorunludur!', 'error'); return; }

            var btn = document.querySelector('button[onclick="submitBranch()"]');
            if (btn) { btn.disabled = true; }

            var data = {
                name:                document.getElementById('branchName').value.trim(),
                branch_type:         selectedType,
                region_id:           document.getElementById('branchRegion').value,
                city_id:             document.getElementById('branchCity').value,
                address:             document.getElementById('branchAddress').value.trim(),
                phone:               document.getElementById('branchPhone').value.trim(),
                email:               document.getElementById('branchEmail').value.trim(),
                manager_name:        document.getElementById('managerName').value.trim(),
                manager_phone:       document.getElementById('managerPhone').value.trim(),
                storage_hourly_rate: parseFloat(document.getElementById('storageRate').value) || 5,
                free_storage_hours:  parseInt(document.getElementById('storageFreeHours').value) || 2,
                status:              document.getElementById('branchStatus').value,
                notes:               document.getElementById('branchNotes').value.trim(),
            };

            fetch('api.php?action=branches.create', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(function(res) {
                if (res.success) {
                    showToast('✓ ' + data.name + ' şubesi başarıyla kaydedildi!', 'success');
                    setTimeout(function() { window.location.href = '?page=branches'; }, 1800);
                } else {
                    showToast('Hata: ' + (res.error || 'Bilinmeyen'), 'error');
                    if (btn) btn.disabled = false;
                }
            })
            .catch(function() { showToast('Sunucu hatası!', 'error'); if (btn) btn.disabled = false; });
        }

        /* ── Reset ── */
        function resetForm() {
            if (!confirm('Formu temizlemek istiyor musunuz?')) return;
            document.getElementById('branchForm').reset();
            selectType('CORPORATE');
            document.querySelectorAll('.pay-chip').forEach(function (c) { c.classList.add('active'); });
            updatePreview();
            showToast('Form temizlendi.', 'info');
        }

        /* ── Toast ── */
        function showToast(msg, type) {
            var t = document.getElementById('bcToast');
            t.style.background = { success: '#0e8045', error: '#c03060', info: '#1b84ff' }[type] || '#1b84ff';
            t.textContent = msg;
            t.style.opacity = '1';
            setTimeout(function () { t.style.opacity = '0'; }, 3200);
        }
    </script>