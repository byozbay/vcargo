<?php
$branches = [
    ['id' => 1, 'name' => 'İstanbul Otogar'],
    ['id' => 2, 'name' => 'Ankara Şehirler'],
    ['id' => 3, 'name' => 'İzmir Ege'],
    ['id' => 4, 'name' => 'Bursa Osmangazi'],
    ['id' => 5, 'name' => 'Antalya Liman'],
    ['id' => 6, 'name' => 'Konya Merkez'],
    ['id' => 7, 'name' => 'Adana Seyhan'],
    ['id' => 9, 'name' => 'Trabzon Sahil'],
    ['id' => 10, 'name' => 'Kayseri Terminal'],
];

$regions = [
    ['id' => 1, 'name' => 'Marmara Bölgesi'],
    ['id' => 2, 'name' => 'İç Anadolu Bölgesi'],
    ['id' => 3, 'name' => 'Ege Bölgesi'],
    ['id' => 4, 'name' => 'Akdeniz Bölgesi'],
    ['id' => 5, 'name' => 'Karadeniz Bölgesi'],
    ['id' => 6, 'name' => 'Doğu Anadolu Bölgesi'],
    ['id' => 7, 'name' => 'Güneydoğu Anadolu Bölgesi'],
];

$roles = [
    'admin' => ['Süper Admin', '#c03060', '#fff0f2', 'bi-shield-fill', 'Tüm sistem, tüm şubeler'],
    'region_manager' => ['Bölge Müdürü', '#8e24aa', '#f3e5f5', 'bi-diagram-3-fill', 'Bölge raporlar, fiyatlandırma, şube denetimi'],
    'branch_manager' => ['Şube Müdürü', '#1b84ff', '#e8f1ff', 'bi-building-fill', 'Şube kasa, personel, emanet inisiyatifi'],
    'branch_staff' => ['Personel', '#0e8045', '#e7f9f0', 'bi-person-badge-fill', 'Kargo kabul/teslim, barkod basımı'],
    'accountant' => ['Muhasebe', '#e08b00', '#fff8ec', 'bi-calculator-fill', 'Finans, fatura, cari mutabakat, raporlar'],
    'courier' => ['Kurye', '#546e7a', '#f1f3f4', 'bi-bicycle', 'Mobil alım/dağıtım onayı'],
];
?>
<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Yeni Kullanıcı Oluştur</div>
            <div class="breadcrumb">
                <a href="?page=dashboard" style="color:var(--text-muted);">Dashboard</a>
                <span class="sep">·</span>
                <a href="?page=users" style="color:var(--text-muted);">Kullanıcılar</a>
                <span class="sep">·</span>
                <span style="color:var(--text-muted);">Yeni Kullanıcı</span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="?page=users" class="btn-outline-secondary-sm d-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Geri
            </a>
            <button type="button" onclick="submitUser()" class="btn-primary-sm d-flex align-items-center gap-1">
                <i class="bi bi-check-lg"></i> Kullanıcıyı Kaydet
            </button>
        </div>
    </div>

    <div style="padding:18px 26px 40px;">
        <form id="userForm" onsubmit="submitUser(event)">
            <div class="row g-3">

                <!-- ═══ Sol Kolon ═══ -->
                <div class="col-12 col-lg-8">

                    <!-- Rol Seçimi -->
                    <div class="card mb-3" style="padding:22px;">
                        <div class="section-label mb-3">
                            <i class="bi bi-shield-half me-2" style="color:#1b84ff;"></i>Kullanıcı Rolü
                            <span style="color:#c03060;font-size:.85rem;">*</span>
                        </div>
                        <div class="row g-2" id="roleCards">
                            <?php foreach ($roles as $key => [$label, $color, $bg, $icon, $desc]): ?>
                                <div class="col-12 col-md-6">
                                    <label class="role-card <?= $key === 'branch_staff' ? 'active' : '' ?>"
                                        id="rc-<?= $key ?>" onclick="selectRole('<?= $key ?>')">
                                        <div class="d-flex align-items-center gap-3">
                                            <div
                                                style="width:38px;height:38px;border-radius:8px;background:<?= $bg ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                <i class="bi <?= $icon ?>" style="color:<?= $color ?>;font-size:1rem;"></i>
                                            </div>
                                            <div>
                                                <div style="font-size:.83rem;font-weight:700;">
                                                    <?= $label ?>
                                                </div>
                                                <div style="font-size:.71rem;color:var(--text-muted);">
                                                    <?= $desc ?>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" id="userRole" value="branch_staff">
                    </div>

                    <!-- Kişisel Bilgiler -->
                    <div class="card mb-3" style="padding:22px;">
                        <div class="section-label mb-4">
                            <i class="bi bi-person me-2" style="color:#1b84ff;"></i>Kişisel Bilgiler
                        </div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Ad <span style="color:#c03060;">*</span></label>
                                <input type="text" class="form-input" id="firstName" placeholder="Kullanıcı adı"
                                    required oninput="updatePreview()">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Soyad <span style="color:#c03060;">*</span></label>
                                <input type="text" class="form-input" id="lastName" placeholder="Kullanıcı soyadı"
                                    required oninput="updatePreview()">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">T.C. Kimlik No <span
                                        style="color:#c03060;">*</span></label>
                                <input type="text" class="form-input" id="userTc" placeholder="11 haneli T.C. numarası"
                                    maxlength="11" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Telefon <span style="color:#c03060;">*</span></label>
                                <input type="text" class="form-input" id="userPhone" placeholder="0___ ___ __ __"
                                    required>
                            </div>
                        </div>
                    </div>

                    <!-- Hesap Bilgileri -->
                    <div class="card mb-3" style="padding:22px;">
                        <div class="section-label mb-4">
                            <i class="bi bi-at me-2" style="color:#1b84ff;"></i>Hesap Bilgileri
                        </div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">E-posta (Giriş) <span
                                        style="color:#c03060;">*</span></label>
                                <input type="email" class="form-input" id="userEmail" placeholder="ornek@vcargo.com.tr"
                                    required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Kullanıcı Adı</label>
                                <div style="position:relative;">
                                    <input type="text" class="form-input" id="userUsername"
                                        placeholder="Boş bırakılırsa e-postadan oluşturulur">
                                    <button type="button" onclick="generateUsername()" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);
                                       background:none;border:none;color:var(--accent-blue);font-size:.75rem;
                                       cursor:pointer;font-weight:600;">Oluştur</button>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Şifre <span style="color:#c03060;">*</span></label>
                                <div style="position:relative;">
                                    <input type="password" class="form-input" id="userPassword"
                                        placeholder="En az 8 karakter" required oninput="checkPasswordStrength()">
                                    <button type="button" onclick="togglePwd('userPassword', this)"
                                        style="position:absolute;right:8px;top:50%;transform:translateY(-50%);
                                       background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:.85rem;">
                                        <i class="bi bi-eye-slash"></i>
                                    </button>
                                </div>
                                <!-- Şifre güç göstergesi -->
                                <div style="margin-top:6px;display:flex;gap:3px;" id="pwdBars">
                                    <div class="pwd-bar" id="pb1"></div>
                                    <div class="pwd-bar" id="pb2"></div>
                                    <div class="pwd-bar" id="pb3"></div>
                                    <div class="pwd-bar" id="pb4"></div>
                                </div>
                                <div id="pwdLabel" style="font-size:.7rem;color:var(--text-muted);margin-top:3px;">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Şifre Tekrar <span style="color:#c03060;">*</span></label>
                                <div style="position:relative;">
                                    <input type="password" class="form-input" id="userPasswordConfirm"
                                        placeholder="Şifreyi tekrar girin" required>
                                    <button type="button" onclick="togglePwd('userPasswordConfirm', this)"
                                        style="position:absolute;right:8px;top:50%;transform:translateY(-50%);
                                       background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:.85rem;">
                                        <i class="bi bi-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Organizasyon — dinamik bölüm -->
                    <div class="card mb-3" style="padding:22px;" id="orgSection">
                        <div class="section-label mb-4">
                            <i class="bi bi-diagram-3 me-2" style="color:#1b84ff;"></i>Organizasyon Bağlantısı
                        </div>
                        <div class="row g-3">

                            <!-- Şubeye bağlı roller için -->
                            <div class="col-12 col-md-6" id="fieldBranch">
                                <label class="form-label-sm">Şube <span style="color:#c03060;">*</span></label>
                                <select class="form-input" id="userBranch" onchange="updatePreview()">
                                    <option value="">Şube seçiniz...</option>
                                    <?php foreach ($branches as $b): ?>
                                        <option value="<?= $b['id'] ?>">
                                            <?= $b['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Bölge müdürü için -->
                            <div class="col-12 col-md-6" id="fieldRegion" style="display:none;">
                                <label class="form-label-sm">Bölge <span style="color:#c03060;">*</span></label>
                                <select class="form-input" id="userRegion" onchange="updatePreview()">
                                    <option value="">Bölge seçiniz...</option>
                                    <?php foreach ($regions as $r): ?>
                                        <option value="<?= $r['id'] ?>">
                                            <?= $r['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Başlangıç Tarihi</label>
                                <input type="date" class="form-input" id="userStart" value="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label-sm">Dahili Not</label>
                                <textarea class="form-input" id="userNote" rows="2"
                                    placeholder="Ek bilgi veya açıklama..."></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ═══ Sağ Kolon — Önizleme ═══ -->
                <div class="col-12 col-lg-4">
                    <div class="card mb-3" style="padding:20px;position:sticky;top:80px;">
                        <div class="section-label mb-3">Kullanıcı Özeti</div>

                        <!-- Avatar önizleme -->
                        <div class="d-flex flex-column align-items-center mb-4"
                            style="padding:16px;background:var(--body-bg);border-radius:8px;">
                            <div id="prev-avatar"
                                style="width:64px;height:64px;border-radius:50%;background:#0e8045;display:flex;align-items:center;justify-content:center;margin-bottom:10px;">
                                <span id="prev-initials" style="color:#fff;font-size:1.4rem;font-weight:800;">?</span>
                            </div>
                            <div id="prev-name" style="font-size:.92rem;font-weight:700;color:var(--text-dark);">Ad
                                Soyad</div>
                            <div id="prev-role-label" style="font-size:.75rem;margin-top:3px;">
                                <span class="status-badge" style="background:#e7f9f0;color:#0e8045;">Personel</span>
                            </div>
                        </div>

                        <!-- Bilgi satırları -->
                        <div class="d-flex flex-column gap-2 mb-4" style="font-size:.78rem;">
                            <div class="d-flex justify-content-between">
                                <span style="color:var(--text-muted);">Şube / Bölge</span>
                                <span id="prev-branch" style="font-weight:600;">—</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span style="color:var(--text-muted);">E-posta</span>
                                <span id="prev-email" style="font-weight:600;font-size:.72rem;">—</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span style="color:var(--text-muted);">Başlangıç</span>
                                <span id="prev-start" style="font-weight:600;">
                                    <?= date('d.m.Y') ?>
                                </span>
                            </div>
                        </div>

                        <!-- Durum -->
                        <div class="mb-3">
                            <label class="form-label-sm mb-2">Başlangıç Durumu</label>
                            <select class="form-input" id="userStatus">
                                <option value="active">Aktif — Hemen erişim aç</option>
                                <option value="passive">Pasif — Şifre belirlenmesini bekle</option>
                            </select>
                        </div>

                        <!-- İlk girişte şifre değiştirme -->
                        <label
                            style="display:flex;align-items:center;gap:8px;font-size:.8rem;cursor:pointer;margin-bottom:16px;">
                            <input type="checkbox" id="forcePasswordChange" checked style="accent-color:#1b84ff;">
                            İlk girişte şifre değiştirmeye zorla
                        </label>

                        <button type="button" onclick="submitUser()"
                            class="btn-primary-sm w-100 d-flex align-items-center justify-content-center gap-2"
                            style="padding:10px;">
                            <i class="bi bi-check-lg"></i> Kullanıcıyı Kaydet
                        </button>
                        <button type="button" onclick="resetForm()"
                            class="btn-outline-secondary-sm w-100 d-flex align-items-center justify-content-center gap-2 mt-2"
                            style="padding:9px;">
                            <i class="bi bi-x-lg"></i> Formu Temizle
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- Toast -->
    <div id="ucToast" style="position:fixed;bottom:24px;right:24px;z-index:9999;padding:12px 20px;
     border-radius:6px;font-size:.82rem;font-weight:600;background:#1b84ff;color:#fff;
     opacity:0;transition:opacity .3s;pointer-events:none;"></div>

    <style>
        .role-card {
            display: block;
            cursor: pointer;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 12px 14px;
            transition: border-color .15s, background .15s;
            background: var(--card-bg);
            user-select: none;
        }

        .role-card:hover {
            background: var(--body-bg);
        }

        .role-card.active {
            border-color: #1b84ff;
            background: #e8f1ff;
        }

        body.dark .role-card.active {
            background: rgba(27, 132, 255, .12);
        }

        .pwd-bar {
            flex: 1;
            height: 4px;
            border-radius: 2px;
            background: var(--border-color);
            transition: background .2s;
        }
    </style>

    <script>
        var selectedRole = 'branch_staff';

        var roleData = {
            admin: { label: 'Süper Admin', color: '#c03060', bg: '#fff0f2', avatarBg: '#c03060' },
            region_manager: { label: 'Bölge Müdürü', color: '#8e24aa', bg: '#f3e5f5', avatarBg: '#8e24aa' },
            branch_manager: { label: 'Şube Müdürü', color: '#1b84ff', bg: '#e8f1ff', avatarBg: '#1b84ff' },
            branch_staff: { label: 'Personel', color: '#0e8045', bg: '#e7f9f0', avatarBg: '#0e8045' },
            accountant: { label: 'Muhasebe', color: '#e08b00', bg: '#fff8ec', avatarBg: '#e08b00' },
            courier: { label: 'Kurye', color: '#546e7a', bg: '#f1f3f4', avatarBg: '#546e7a' },
        };

        /* ── Rol Seç ── */
        function selectRole(key) {
            selectedRole = key;
            document.getElementById('userRole').value = key;
            document.querySelectorAll('.role-card').forEach(function (c) { c.classList.remove('active'); });
            document.getElementById('rc-' + key).classList.add('active');

            // Alan görünürlüğü
            var showBranch = ['branch_manager', 'branch_staff', 'accountant', 'courier'].includes(key);
            var showRegion = key === 'region_manager';
            document.getElementById('fieldBranch').style.display = showBranch ? '' : 'none';
            document.getElementById('fieldRegion').style.display = showRegion ? '' : 'none';
            document.getElementById('orgSection').style.display = (key !== 'admin') ? '' : 'none';

            updatePreview();
        }

        /* ── Önizleme ── */
        function updatePreview() {
            var first = document.getElementById('firstName').value.trim();
            var last = document.getElementById('lastName').value.trim();
            var email = document.getElementById('userEmail').value.trim();
            var rd = roleData[selectedRole] || roleData.branch_staff;

            var fullName = (first + ' ' + last).trim() || 'Ad Soyad';
            var initials = (first ? first[0] : '?') + (last ? last[0] : '');
            initials = initials.toUpperCase();

            document.getElementById('prev-initials').textContent = initials;
            document.getElementById('prev-name').textContent = fullName;
            document.getElementById('prev-avatar').style.background = rd.avatarBg;
            document.getElementById('prev-email').textContent = email || '—';
            document.getElementById('prev-role-label').innerHTML =
                '<span class="status-badge" style="background:' + rd.bg + ';color:' + rd.color + ';">' + rd.label + '</span>';

            // Şube / Bölge
            var branchSel = document.getElementById('userBranch');
            var regionSel = document.getElementById('userRegion');
            var org = '—';
            if (selectedRole === 'region_manager') {
                org = regionSel.options[regionSel.selectedIndex]?.text !== 'Bölge seçiniz...' ? regionSel.options[regionSel.selectedIndex]?.text : '—';
            } else if (selectedRole !== 'admin') {
                org = branchSel.options[branchSel.selectedIndex]?.text !== 'Şube seçiniz...' ? branchSel.options[branchSel.selectedIndex]?.text : '—';
            } else {
                org = 'Tüm Sistem';
            }
            document.getElementById('prev-branch').textContent = org;

            // Tarih
            var startVal = document.getElementById('userStart').value;
            if (startVal) {
                var parts = startVal.split('-');
                document.getElementById('prev-start').textContent = parts[2] + '.' + parts[1] + '.' + parts[0];
            }
        }

        /* ── Kullanıcı Adı Oluştur ── */
        function generateUsername() {
            var first = document.getElementById('firstName').value.trim().toLowerCase();
            var last = document.getElementById('lastName').value.trim().toLowerCase();
            if (!first || !last) { showToast('Önce ad ve soyad giriniz!', 'error'); return; }
            var un = (first.charAt(0) + last).replace(/\s+/g, '').replace(/ğ/g, 'g').replace(/ü/g, 'u')
                .replace(/ş/g, 's').replace(/ı/g, 'i').replace(/ö/g, 'o').replace(/ç/g, 'c');
            document.getElementById('userUsername').value = un;
        }

        /* ── Şifre Göster / Gizle ── */
        function togglePwd(id, btn) {
            var inp = document.getElementById(id);
            var icon = btn.querySelector('i');
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.className = 'bi bi-eye';
            } else {
                inp.type = 'password';
                icon.className = 'bi bi-eye-slash';
            }
        }

        /* ── Şifre Gücü ── */
        function checkPasswordStrength() {
            var val = document.getElementById('userPassword').value;
            var score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            var colors = ['', '#c03060', '#e08b00', '#1b84ff', '#0e8045'];
            var labels = ['', 'Çok zayıf', 'Orta', 'Güçlü', 'Çok güçlü'];
            for (var i = 1; i <= 4; i++) {
                document.getElementById('pb' + i).style.background = i <= score ? colors[score] : 'var(--border-color)';
            }
            document.getElementById('pwdLabel').textContent = val.length ? labels[score] : '';
            document.getElementById('pwdLabel').style.color = colors[score];
        }

        /* ── Submit ── */
        function submitUser(e) {
            if (e) e.preventDefault();
            var first = document.getElementById('firstName').value.trim();
            var last = document.getElementById('lastName').value.trim();
            if (!first || !last) { showToast('Ad ve soyad zorunludur!', 'error'); return; }
            if (!document.getElementById('userEmail').value.trim()) { showToast('E-posta zorunludur!', 'error'); return; }
            if (!document.getElementById('userTc').value.trim()) { showToast('T.C. kimlik no zorunludur!', 'error'); return; }
            if (!document.getElementById('userPhone').value.trim()) { showToast('Telefon zorunludur!', 'error'); return; }

            var pwd = document.getElementById('userPassword').value;
            var pwd2 = document.getElementById('userPasswordConfirm').value;
            if (!pwd) { showToast('Şifre zorunludur!', 'error'); return; }
            if (pwd.length < 8) { showToast('Şifre en az 8 karakter olmalı!', 'error'); return; }
            if (pwd !== pwd2) { showToast('Şifreler eşleşmiyor!', 'error'); return; }

            var btn = document.querySelector('[onclick="submitUser()"]');
            if (btn) { btn.disabled = true; btn.textContent = 'Kaydediliyor...'; }

            var data = {
                full_name:  first + ' ' + last,
                email:      document.getElementById('userEmail').value.trim(),
                phone:      document.getElementById('userPhone').value.trim(),
                password:   pwd,
                role:       document.getElementById('userRole')?.value || selectedRole,
                branch_id:  document.getElementById('userBranch')?.value || '',
                username:   document.getElementById('userEmail').value.trim(),
            };

            fetch('api.php?action=users.create', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(function(res) {
                if (res.success) {
                    showToast('✓ ' + first + ' ' + last + ' başarıyla eklendi!', 'success');
                    setTimeout(function() { window.location.href = '?page=users'; }, 1800);
                } else {
                    showToast('Hata: ' + (res.error || 'Bilinmeyen'), 'error');
                    if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-check-lg"></i> Kullanıcıyı Kaydet'; }
                }
            })
            .catch(function() {
                showToast('Sunucu hatası!', 'error');
                if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-check-lg"></i> Kullanıcıyı Kaydet'; }
            });
        }

        /* ── Reset ── */
        function resetForm() {
            if (!confirm('Formu temizlemek istiyor musunuz?')) return;
            document.getElementById('userForm').reset();
            selectRole('branch_staff');
            document.querySelectorAll('.pwd-bar').forEach(function (b) { b.style.background = 'var(--border-color)'; });
            document.getElementById('pwdLabel').textContent = '';
            updatePreview();
            showToast('Form temizlendi.', 'info');
        }

        /* ── Toast ── */
        function showToast(msg, type) {
            var t = document.getElementById('ucToast');
            t.style.background = { success: '#0e8045', error: '#c03060', info: '#1b84ff' }[type] || '#1b84ff';
            t.textContent = msg;
            t.style.opacity = '1';
            setTimeout(function () { t.style.opacity = '0'; }, 3200);
        }

        /* ── Dinleyiciler ── */
        ['firstName', 'lastName', 'userEmail', 'userStart'].forEach(function (id) {
            document.getElementById(id).addEventListener('input', updatePreview);
            document.getElementById(id).addEventListener('change', updatePreview);
        });
        document.getElementById('userBranch').addEventListener('change', updatePreview);
        document.getElementById('userRegion').addEventListener('change', updatePreview);
    </script>
</main>