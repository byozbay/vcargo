<?php ?>
<main class="main-content">

    <div class="page-header">
        <div>
            <div class="page-title">Sistem Ayarları</div>
            <div class="breadcrumb">
                <a href="?page=dashboard" style="color:var(--text-muted);">Dashboard</a>
                <span class="sep">·</span>
                <span style="color:var(--text-muted);">Sistem Ayarları</span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn-outline-secondary-sm d-flex align-items-center gap-1" onclick="testSmtp()">
                <i class="bi bi-envelope-check"></i> SMTP Testi
            </button>
            <button class="btn-primary-sm d-flex align-items-center gap-1" onclick="saveSettings()">
                <i class="bi bi-check-lg"></i> Ayarları Kaydet
            </button>
        </div>
    </div>

    <div style="padding:18px 26px 40px;">
        <div class="row g-3">

            <!-- ═══ Sol — Tab Menü ═══ -->
            <div class="col-12 col-lg-3">
                <div class="card" style="padding:10px;">
                    <?php foreach ([
                        ['general', 'bi-gear-fill', 'Genel Bilgiler'],
                        ['security', 'bi-shield-lock-fill', 'Güvenlik'],
                        ['notif', 'bi-bell-fill', 'Bildirimler & SMS'],
                        ['smtp', 'bi-envelope-fill', 'E-posta (SMTP)'],
                        ['print', 'bi-printer-fill', 'Yazıcı & Barkod'],
                        ['backup', 'bi-cloud-arrow-up-fill', 'Yedekleme'],
                        ['audit', 'bi-journal-text', 'Denetim & Log'],
                        ['danger', 'bi-exclamation-triangle-fill', 'Tehlikeli Alan'],
                    ] as [$key, $icon, $label]):
                        $isFirst = ($key === 'general');
                        ?>
                        <button class="settings-menu-item <?= $isFirst ? 'active' : '' ?>" id="smenu-<?= $key ?>"
                            onclick="switchSettings('<?= $key ?>')">
                            <i class="bi <?= $icon ?> me-2"></i>
                            <?= $label ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ═══ Sağ — Panel ═══ -->
            <div class="col-12 col-lg-9">

                <!-- ── Genel ── -->
                <div class="settings-pane" id="spane-general">
                    <div class="card" style="padding:24px;">
                        <div class="section-label mb-4"><i class="bi bi-building me-2" style="color:#1b84ff;"></i>Şirket
                            & Sistem Bilgileri</div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Şirket / Platform Adı <span
                                        style="color:#c03060;">*</span></label>
                                <input type="text" class="form-input" value="vCargo Lojistik A.Ş.">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Vergi No</label>
                                <input type="text" class="form-input" value="1234567890" maxlength="10">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Vergi Dairesi</label>
                                <input type="text" class="form-input" value="Çankaya Vergi Dairesi">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Merkez Telefon</label>
                                <input type="text" class="form-input" value="0212 555 00 00">
                            </div>
                            <div class="col-12">
                                <label class="form-label-sm">Merkez Adresi</label>
                                <textarea class="form-input"
                                    rows="2">İstanbul Otogar, Esenler, İstanbul, 34230</textarea>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Destek E-posta</label>
                                <input type="email" class="form-input" value="destek@vcargo.com.tr">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Sistem Dili</label>
                                <select class="form-input">
                                    <option selected>Türkçe</option>
                                    <option>English</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Saat Dilimi</label>
                                <select class="form-input">
                                    <option selected>Europe/Istanbul (UTC+3)</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Para Birimi</label>
                                <select class="form-input">
                                    <option selected>TRY — Türk Lirası (₺)</option>
                                    <option>USD — Dolar ($)</option>
                                    <option>EUR — Euro (€)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Güvenlik ── -->
                <div class="settings-pane" id="spane-security" style="display:none;">
                    <div class="card" style="padding:24px;">
                        <div class="section-label mb-4"><i class="bi bi-shield-lock me-2"
                                style="color:#1b84ff;"></i>Güvenlik Ayarları</div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Oturum Güven Süresi (dk)</label>
                                <input type="number" class="form-input" value="120" min="5">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Maks. Başarısız Giriş</label>
                                <input type="number" class="form-input" value="5" min="1">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Hesap Kilitleme Süresi (dk)</label>
                                <input type="number" class="form-input" value="15" min="1">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Minimum Şifre Uzunluğu</label>
                                <input type="number" class="form-input" value="8" min="6">
                            </div>
                            <div class="col-12">
                                <label class="form-label-sm mb-2">Şifre Gereksinimleri</label>
                                <?php foreach ([
                                    ['pwd_upper', 'Büyük harf zorunlu'],
                                    ['pwd_number', 'Rakam zorunlu'],
                                    ['pwd_special', 'Özel karakter zorunlu'],
                                ] as [$id, $label]): ?>
                                    <label
                                        style="display:flex;align-items:center;gap:8px;font-size:.82rem;margin-bottom:8px;cursor:pointer;">
                                        <input type="checkbox" id="<?= $id ?>" checked style="accent-color:#1b84ff;">
                                        <?= $label ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label-sm mb-2">İki Faktörlü Doğrulama</label>
                                <?php foreach ([
                                    ['2fa_admin', 'Admin rolü için zorunlu'],
                                    ['2fa_manager', 'Şube müdürü için zorunlu'],
                                ] as [$id, $label]): ?>
                                    <label
                                        style="display:flex;align-items:center;gap:8px;font-size:.82rem;margin-bottom:8px;cursor:pointer;">
                                        <input type="checkbox" id="<?= $id ?>" style="accent-color:#1b84ff;">
                                        <?= $label ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label-sm">İzinli IP Aralıkları (Super Admin girişi)</label>
                                <textarea class="form-input" rows="3"
                                    placeholder="Her satıra bir IP veya CIDR, ör: 192.168.1.0/24"></textarea>
                                <div style="font-size:.72rem;color:var(--text-muted);margin-top:3px;">Boş bırakılırsa
                                    tüm IP'lere açık.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Bildirimler ── -->
                <div class="settings-pane" id="spane-notif" style="display:none;">
                    <div class="card mb-3" style="padding:24px;">
                        <div class="section-label mb-4"><i class="bi bi-phone me-2" style="color:#1b84ff;"></i>SMS
                            Gateway Ayarları</div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">SMS Sağlayıcı</label>
                                <select class="form-input">
                                    <option>Netgsm</option>
                                    <option>Iletimerkezi</option>
                                    <option>Twilio</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">API Kullanıcısı</label>
                                <input type="text" class="form-input" placeholder="API kullanıcı adı">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">API Şifresi / Token</label>
                                <input type="password" class="form-input" placeholder="••••••••">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Gönderici Başlığı</label>
                                <input type="text" class="form-input" value="vCargo" maxlength="11">
                            </div>
                        </div>
                    </div>
                    <div class="card" style="padding:24px;">
                        <div class="section-label mb-4"><i class="bi bi-bell me-2" style="color:#1b84ff;"></i>Otomatik
                            Bildirim Tetikleyicileri</div>
                        <?php foreach ([
                            ['Kargo oluşturulduğunda (gönderici)', true],
                            ['Kargo şubeye ulaştığında (alıcı)', true],
                            ['Kargo teslim edildiğinde (gönderici)', false],
                            ['Emanet süresi dolmak üzere (alıcı)', true],
                            ['Cari hesap limit aşımında', true],
                            ['Yeni kullanıcı oluşturulduğunda', true],
                        ] as [$label, $checked]): ?>
                            <label
                                style="display:flex;align-items:center;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--border-color);font-size:.82rem;cursor:pointer;">
                                <?= $label ?>
                                <input type="checkbox" <?= $checked ? 'checked' : '' ?>
                                style="accent-color:#1b84ff;width:16px;height:16px;">
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- ── SMTP ── -->
                <div class="settings-pane" id="spane-smtp" style="display:none;">
                    <div class="card" style="padding:24px;">
                        <div class="section-label mb-4"><i class="bi bi-envelope me-2"
                                style="color:#1b84ff;"></i>E-posta (SMTP) Ayarları</div>
                        <div class="row g-3">
                            <div class="col-12 col-md-8">
                                <label class="form-label-sm">SMTP Sunucu</label>
                                <input type="text" class="form-input" value="smtp.gmail.com">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">Port</label>
                                <input type="number" class="form-input" value="587">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">E-posta (Kullanıcı Adı)</label>
                                <input type="email" class="form-input" value="sistem@vcargo.com.tr">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Şifre / App Token</label>
                                <input type="password" class="form-input" placeholder="••••••••••••">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Şifreleme</label>
                                <select class="form-input">
                                    <option selected>TLS</option>
                                    <option>SSL</option>
                                    <option>Yok</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Gönderici Adı</label>
                                <input type="text" class="form-input" value="vCargo Sistem">
                            </div>
                            <div class="col-12">
                                <label class="form-label-sm">Test E-posta Adresi</label>
                                <div class="d-flex gap-2">
                                    <input type="email" class="form-input" id="testEmail" placeholder="test@example.com"
                                        style="flex:1;">
                                    <button onclick="testSmtp()"
                                        class="btn-outline-secondary-sm d-flex align-items-center gap-1"
                                        style="white-space:nowrap;">
                                        <i class="bi bi-send"></i> Gönder
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Yazıcı ── -->
                <div class="settings-pane" id="spane-print" style="display:none;">
                    <div class="card" style="padding:24px;">
                        <div class="section-label mb-4"><i class="bi bi-printer me-2" style="color:#1b84ff;"></i>Termal
                            Yazıcı & Barkod Ayarları</div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Barkod Formatı</label>
                                <select class="form-input">
                                    <option selected>Code 128</option>
                                    <option>QR Code</option>
                                    <option>Code 39</option>
                                    <option>EAN-13</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Etiket Boyutu</label>
                                <select class="form-input">
                                    <option selected>100mm × 70mm</option>
                                    <option>100mm × 50mm</option>
                                    <option>80mm × 60mm</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Çıktı Formatı</label>
                                <select class="form-input">
                                    <option selected>ZPL (Zebra)</option>
                                    <option>PDF</option>
                                    <option>ESC/POS</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Varsayılan Kopya Sayısı</label>
                                <input type="number" class="form-input" value="2" min="1" max="5">
                            </div>
                            <div class="col-12">
                                <label class="form-label-sm mb-2">Etiket Elemanları</label>
                                <?php foreach ([
                                    'Gönderici Adı / Soyadı',
                                    'Alıcı Adı / Soyadı',
                                    'Takip Numarası',
                                    'Ağırlık & Boyut',
                                    'Ödeme Tipi',
                                    'Şube Logosu',
                                ] as $item): ?>
                                    <label style="display:flex;gap:8px;font-size:.82rem;margin-bottom:7px;cursor:pointer;">
                                        <input type="checkbox" checked style="accent-color:#1b84ff;">
                                        <?= $item ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Yedekleme ── -->
                <div class="settings-pane" id="spane-backup" style="display:none;">
                    <div class="card mb-3" style="padding:24px;">
                        <div class="section-label mb-4"><i class="bi bi-cloud-arrow-up me-2"
                                style="color:#1b84ff;"></i>Otomatik Yedekleme</div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Yedekleme Sıklığı</label>
                                <select class="form-input">
                                    <option>Saatlik</option>
                                    <option selected>Günlük</option>
                                    <option>Haftalık</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Yedek Saklama Süresi (gün)</label>
                                <input type="number" class="form-input" value="30" min="1">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Yedekleme Hedefi</label>
                                <select class="form-input">
                                    <option selected>Yerel Sunucu</option>
                                    <option>Amazon S3</option>
                                    <option>Google Drive</option>
                                    <option>FTP / SFTP</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Son Başarılı Yedek</label>
                                <input type="text" class="form-input" value="24.02.2026 03:00" readonly
                                    style="background:var(--body-bg);cursor:default;">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button onclick="manualBackup()" class="btn-primary-sm d-flex align-items-center gap-2">
                                <i class="bi bi-cloud-arrow-up"></i> Şimdi Yedekle
                            </button>
                        </div>
                    </div>
                    <div class="card" style="padding:20px;">
                        <div class="section-label mb-3">Son Yedekler</div>
                        <?php foreach ([
                            ['24.02.2026 03:00', '47.2 MB', 'Başarılı'],
                            ['23.02.2026 03:00', '46.8 MB', 'Başarılı'],
                            ['22.02.2026 03:00', '45.1 MB', 'Başarılı'],
                        ] as [$date, $size, $status]): ?>
                            <div class="d-flex align-items-center justify-content-between py-2"
                                style="border-bottom:1px solid var(--border-color);font-size:.81rem;">
                                <div>
                                    <span style="font-weight:600;">
                                        <?= $date ?>
                                    </span>
                                    <span style="color:var(--text-muted);margin-left:12px;">
                                        <?= $size ?>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="status-badge" style="background:#e7f9f0;color:#0e8045;">
                                        <?= $status ?>
                                    </span>
                                    <button class="icon-btn-circle" title="İndir"
                                        style="width:26px;height:26px;font-size:.78rem;">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- ── Denetim ── -->
                <div class="settings-pane" id="spane-audit" style="display:none;">
                    <div class="card" style="padding:24px;">
                        <div class="section-label mb-4"><i class="bi bi-journal-text me-2"
                                style="color:#1b84ff;"></i>Denetim Log Ayarları</div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Log Saklama Süresi (gün)</label>
                                <input type="number" class="form-input" value="365" min="30">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Log Düzeyi</label>
                                <select class="form-input">
                                    <option>Sadece Kritik</option>
                                    <option selected>Normal (Önerilen)</option>
                                    <option>Ayrıntılı (Debug)</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label-sm mb-2">Kayıt Alınacak Olaylar</label>
                                <?php foreach ([
                                    ['Kullanıcı giriş/çıkışları', true],
                                    ['Başarısız giriş denemeleri', true],
                                    ['Kargo kayıt ve düzenleme', true],
                                    ['Finansal işlemler', true],
                                    ['Kullanıcı yönetimi değişiklikleri', true],
                                    ['Fiyat/ayar değişiklikleri', true],
                                    ['Veri dışa aktarma işlemleri', false],
                                ] as [$label, $checked]): ?>
                                    <label style="display:flex;gap:8px;font-size:.82rem;margin-bottom:8px;cursor:pointer;">
                                        <input type="checkbox" <?= $checked ? 'checked' : '' ?>
                                        style="accent-color:#1b84ff;">
                                        <?= $label ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="?page=audit_log"
                                class="btn-outline-secondary-sm d-inline-flex align-items-center gap-2">
                                <i class="bi bi-journal-text"></i> Log Kayıtlarını Görüntüle
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ── Tehlikeli Alan ── -->
                <div class="settings-pane" id="spane-danger" style="display:none;">
                    <div class="card" style="padding:24px;border-left:4px solid #c03060;">
                        <div class="section-label mb-2" style="color:#c03060;">
                            <i class="bi bi-exclamation-triangle me-2"></i>Tehlikeli Alan
                        </div>
                        <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:24px;">
                            Bu işlemler geri alınamaz. Lütfen dikkatli olunuz.
                        </div>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ([
                                ['Test Verilerini Sil', 'bi-trash3', 'Sistem içindeki tüm demo/test verilerini temizler.'],
                                ['Önbelleği Temizle', 'bi-arrow-repeat', 'Tüm sistem önbelleğini ve geçici dosyaları siler.'],
                                ['Oturumları Kapat', 'bi-box-arrow-left', 'Tüm aktif kullanıcı oturumlarını sonlandırır.'],
                                ['Bakım Modunu Aç/Kapat', 'bi-cone-striped', 'Sistemi bakım moduna alır, kullanıcılar giriş yapamaz.'],
                            ] as [$label, $icon, $desc]): ?>
                                <div class="d-flex align-items-center justify-content-between p-3 flex-wrap gap-2"
                                    style="border:1px solid var(--border-color);border-radius:8px;">
                                    <div>
                                        <div style="font-size:.83rem;font-weight:700;">
                                            <?= $label ?>
                                        </div>
                                        <div style="font-size:.74rem;color:var(--text-muted);margin-top:2px;">
                                            <?= $desc ?>
                                        </div>
                                    </div>
                                    <button onclick="dangerAction('<?= $label ?>')" style="padding:6px 14px;border:2px solid #c03060;border-radius:6px;
                                       background:none;color:#c03060;font-size:.78rem;font-weight:700;
                                       cursor:pointer;white-space:nowrap;">
                                        <i class="bi <?= $icon ?> me-1"></i>
                                        <?= $label === 'Bakım Modunu Aç/Kapat' ? 'Değiştir' : 'Çalıştır' ?>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div><!-- /col -->
        </div><!-- /row -->
    </div>

    <!-- Toast -->
    <div id="settingsToast" style="position:fixed;bottom:24px;right:24px;z-index:9999;padding:12px 20px;
     border-radius:6px;font-size:.82rem;font-weight:600;background:#1b84ff;color:#fff;
     opacity:0;transition:opacity .3s;pointer-events:none;"></div>

    <style>
        .settings-menu-item {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 9px 12px;
            border: none;
            border-radius: 6px;
            font-size: .8rem;
            font-weight: 600;
            color: var(--text-muted);
            background: none;
            cursor: pointer;
            text-align: left;
            margin-bottom: 2px;
            transition: background .12s, color .12s;
        }

        .settings-menu-item:hover {
            background: var(--body-bg);
            color: var(--text-dark);
        }

        .settings-menu-item.active {
            background: #e8f1ff;
            color: #1b84ff;
        }

        body.dark .settings-menu-item.active {
            background: rgba(27, 132, 255, .15);
        }
    </style>

    <script>
        function switchSettings(key) {
            document.querySelectorAll('.settings-menu-item').forEach(function (m) { m.classList.remove('active'); });
            document.querySelectorAll('.settings-pane').forEach(function (p) { p.style.display = 'none'; });
            document.getElementById('smenu-' + key).classList.add('active');
            document.getElementById('spane-' + key).style.display = '';
        }

        function saveSettings() { showToast('✓ Ayarlar başarıyla kaydedildi.', 'success'); }

        function testSmtp() {
            var email = document.getElementById('testEmail');
            if (email && !email.value.trim()) { showToast('Test e-posta adresi giriniz!', 'error'); return; }
            showToast('✓ Test e-postası gönderildi.', 'success');
        }

        function manualBackup() {
            showToast('Yedekleme başlatıldı... Lütfen bekleyin.', 'info');
        }

        function dangerAction(label) {
            if (!confirm('⚠ ' + label + ' işlemini gerçekleştirmek istediğinizden emin misiniz?')) return;
            showToast('✓ İşlem tamamlandı: ' + label, 'success');
        }

        function showToast(msg, type) {
            var t = document.getElementById('settingsToast');
            t.style.background = { success: '#0e8045', error: '#c03060', info: '#1b84ff' }[type] || '#1b84ff';
            t.textContent = msg;
            t.style.opacity = '1';
            setTimeout(function () { t.style.opacity = '0'; }, 3200);
        }
    </script>