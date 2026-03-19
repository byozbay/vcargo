<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Yeni Emanet Kaydı</div>
            <div class="breadcrumb">
                <a href="?page=dashboard">Ana Sayfa</a>
                <span class="sep">›</span>
                <a href="?page=storage">Emanet Listesi</a>
                <span class="sep">›</span>
                <span>Yeni Kayıt</span>
            </div>
        </div>
        <a href="?page=storage" class="btn-outline-secondary-sm d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Geri Dön
        </a>
    </div>

    <div style="padding:18px 26px 40px;">
        <form id="storageForm" onsubmit="submitStorage(event)">
            <div class="row g-3">

                <!-- ══ SOL KOLON ══ -->
                <div class="col-12 col-lg-8">

                    <!-- ── Emanet Tipi Seçimi ── -->
                    <div class="card mb-3">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div
                                style="width:32px;height:32px;background:#e8f1ff;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-archive" style="color:#1b84ff;"></i>
                            </div>
                            <div class="section-label">Emanet Tipi</div>
                        </div>

                        <div class="row g-2">
                            <!-- Kargo Emaneti -->
                            <div class="col-12 col-md-6">
                                <label class="storage-type-card" id="card-kargo" onclick="selectType('kargo')">
                                    <input type="radio" name="storageType" value="kargo" checked
                                        style="display:none;" />
                                    <div style="text-align:center;padding:8px 0;">
                                        <div
                                            style="width:48px;height:48px;background:#e8f1ff;border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                                            <i class="bi bi-box-seam-fill" style="color:#1b84ff;font-size:1.3rem;"></i>
                                        </div>
                                        <div style="font-size:.88rem;font-weight:700;">Kargo Emaneti</div>
                                        <div style="font-size:.75rem;color:var(--text-muted);margin-top:4px;">Gelen
                                            kargo şubede bekliyor</div>
                                    </div>
                                </label>
                            </div>
                            <!-- Yolcu Bagajı -->
                            <div class="col-12 col-md-6">
                                <label class="storage-type-card" id="card-bagaj" onclick="selectType('bagaj')">
                                    <input type="radio" name="storageType" value="bagaj" style="display:none;" />
                                    <div style="text-align:center;padding:8px 0;">
                                        <div
                                            style="width:48px;height:48px;background:#f3e5f5;border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                                            <i class="bi bi-luggage-fill" style="color:#8e24aa;font-size:1.3rem;"></i>
                                        </div>
                                        <div style="font-size:.88rem;font-weight:700;">Yolcu Bagajı</div>
                                        <div style="font-size:.75rem;color:var(--text-muted);margin-top:4px;">Bavul /
                                            çanta terminalde</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- ── Kargo Bağlantısı (sadece kargo tipi) ── -->
                    <div id="kargoLinkBlock" class="card mb-3">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div
                                style="width:32px;height:32px;background:#e7f9f0;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-link-45deg" style="color:#0e8045;"></i>
                            </div>
                            <div class="section-label">Kargo Bağlantısı</div>
                        </div>
                        <div class="row g-2">
                            <div class="col-12 col-md-9">
                                <label class="form-label-sm">Takip Numarası</label>
                                <div style="position:relative;">
                                    <i class="bi bi-upc-scan"
                                        style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#a3aab8;"></i>
                                    <input type="text" id="linkedTracking" class="form-input"
                                        style="padding-left:34px;font-family:monospace;" placeholder="TRK-240224-..."
                                        onkeydown="if(event.key==='Enter'){ lookupShipment(); event.preventDefault(); }" />
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label-sm">&nbsp;</label>
                                <button type="button" class="btn-outline-secondary-sm w-100" style="height:37px;"
                                    onclick="lookupShipment()">
                                    <i class="bi bi-search me-1"></i> Bul
                                </button>
                            </div>
                        </div>
                        <!-- Bulunan kargo bilgisi -->
                        <div id="linkedShipmentInfo"
                            style="display:none;margin-top:12px;padding:10px 14px;background:var(--body-bg);border-radius:7px;border:1px solid var(--border-color);">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill" style="color:#0e8045;font-size:.9rem;"></i>
                                <div style="font-size:.82rem;font-weight:600;" id="linkedInfo">—</div>
                            </div>
                        </div>
                    </div>

                    <!-- ── Sahibi / Müşteri Bilgileri ── -->
                    <div class="card mb-3">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div
                                style="width:32px;height:32px;background:#fff8ec;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-person-fill" style="color:#e08b00;"></i>
                            </div>
                            <div class="section-label" id="ownerLabel">Alıcı / Müşteri Bilgileri</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Ad Soyad <span style="color:#f31260;">*</span></label>
                                <input type="text" id="ownerName" class="form-input" placeholder="Tam ad" required />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Telefon <span style="color:#f31260;">*</span></label>
                                <input type="tel" id="ownerPhone" class="form-input" placeholder="0 5xx xxx xx xx"
                                    required />
                            </div>
                            <div class="col-12 col-md-6" id="idNoBlock">
                                <label class="form-label-sm">T.C. / Kimlik No</label>
                                <input type="text" id="ownerIdNo" class="form-input" maxlength="11"
                                    placeholder="Opsiyonel" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">E-posta</label>
                                <input type="email" id="ownerEmail" class="form-input" placeholder="ornek@mail.com" />
                            </div>
                        </div>
                    </div>

                    <!-- ── Eşya & Konum Bilgileri ── -->
                    <div class="card mb-3">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div
                                style="width:32px;height:32px;background:#f3e5f5;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-geo-alt-fill" style="color:#8e24aa;"></i>
                            </div>
                            <div class="section-label">Eşya & Konum</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <label class="form-label-sm">Eşya Tipi</label>
                                <select id="itemType" class="form-input">
                                    <option value="koli">Koli</option>
                                    <option value="bavul">Bavul</option>
                                    <option value="canta">Çanta</option>
                                    <option value="parca">Parça Eşya</option>
                                    <option value="diger">Diğer</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label-sm">Adet</label>
                                <input type="number" id="itemQty" class="form-input" value="1" min="1" />
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label-sm">Ağırlık (kg)</label>
                                <input type="number" id="itemWeight" class="form-input" placeholder="0.0" min="0"
                                    step="0.1" />
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label-sm">Raf / Dolap No <span
                                        style="color:#f31260;">*</span></label>
                                <input type="text" id="locationCode" class="form-input" placeholder="A-01" required
                                    style="font-family:monospace;font-weight:700;" />
                            </div>
                            <div class="col-12">
                                <label class="form-label-sm">Açıklama / Not</label>
                                <input type="text" id="itemDesc" class="form-input"
                                    placeholder="Kırmızı bavul, marka etiketi yok..." />
                            </div>
                        </div>
                    </div>

                    <!-- ── Süre & Tarife ── -->
                    <div class="card">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div
                                style="width:32px;height:32px;background:#e7f9f0;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-clock-history" style="color:#0e8045;"></i>
                            </div>
                            <div class="section-label">Süre & Tarife</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">Ücretsiz Süre (saat)</label>
                                <input type="number" id="freeHours" class="form-input" value="4" min="0" step="1"
                                    oninput="recalcFee()" />
                                <div style="font-size:.71rem;color:var(--text-muted);margin-top:3px;">Şube / bölge
                                    ayarından gelir</div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">Saatlik Ücret (₺)</label>
                                <input type="number" id="hourlyRate" class="form-input" value="2.00" min="0" step="0.50"
                                    oninput="recalcFee()" />
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">Tahmini Süre (saat)</label>
                                <input type="number" id="estHours" class="form-input" placeholder="0" min="0" step="1"
                                    oninput="recalcFee()" />
                            </div>
                        </div>

                        <!-- Tahmini ücret önizleme -->
                        <div id="feePreview"
                            style="margin-top:14px;display:none;padding:10px 14px;background:var(--body-bg);border-radius:8px;border:1px solid var(--border-color);">
                            <div class="d-flex align-items-center justify-content-between">
                                <span style="font-size:.8rem;color:var(--text-muted);">Tahmini Emanet Ücreti</span>
                                <span id="feePreviewAmt"
                                    style="font-weight:700;font-size:.95rem;color:var(--accent-blue);">₺0.00</span>
                            </div>
                            <div style="font-size:.72rem;color:var(--text-muted);margin-top:4px;" id="feePreviewNote">
                            </div>
                        </div>
                    </div>

                </div><!-- /col-8 -->

                <!-- ══ SAĞ KOLON ══ -->
                <div class="col-12 col-lg-4">

                    <!-- Giriş Zamanı -->
                    <div class="card mb-3">
                        <div class="section-label mb-3">Giriş Bilgileri</div>
                        <div class="d-flex flex-column gap-3">
                            <div>
                                <label class="form-label-sm">Giriş Tarihi & Saati <span
                                        style="color:#f31260;">*</span></label>
                                <input type="datetime-local" id="entryTime" class="form-input"
                                    value="<?= date('Y-m-d\TH:i') ?>" required />
                            </div>
                            <div>
                                <label class="form-label-sm">Kayıt Yapan Personel</label>
                                <input type="text" class="form-input"
                                    value="<?= htmlspecialchars($_SESSION['user_name'] ?? 'Personel') ?>" disabled
                                    style="background:var(--body-bg);color:var(--text-muted);" />
                            </div>
                        </div>
                    </div>

                    <!-- QR / Fiş Önizleme -->
                    <div class="card mb-3" style="text-align:center;">
                        <div class="section-label mb-3">Fiş Önizleme</div>
                        <div style="border:2px dashed var(--border-color);border-radius:8px;padding:20px 16px;">
                            <div id="qrPlaceholder"
                                style="width:80px;height:80px;background:var(--body-bg);border-radius:8px;margin:0 auto 10px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-qr-code"
                                    style="font-size:2.2rem;color:var(--text-muted);opacity:.4;"></i>
                            </div>
                            <div style="font-size:.75rem;color:var(--text-muted);">Kayıt sonrası QR kod oluşturulacak
                            </div>
                            <div style="font-size:.72rem;color:var(--text-muted);margin-top:6px;">Müşteriye verilecek
                                fiş otomatik hazırlanır</div>
                        </div>
                    </div>

                    <!-- Aksiyon -->
                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn-primary-sm w-100"
                            style="height:44px;font-size:.9rem;border-radius:8px;">
                            <i class="bi bi-check2-circle me-1"></i> Kaydet &amp; Fiş Bas
                        </button>
                        <button type="button"
                            class="btn-outline-secondary-sm w-100 d-flex align-items-center justify-content-center gap-1"
                            style="height:38px;font-size:.84rem;border-radius:8px;" onclick="saveOnly()">
                            <i class="bi bi-floppy"></i> Sadece Kaydet
                        </button>
                        <button type="reset" class="btn-outline-secondary-sm w-100"
                            style="height:36px;font-size:.82rem;border-radius:8px;color:var(--text-muted);">
                            <i class="bi bi-x-lg me-1"></i> Temizle
                        </button>
                    </div>

                </div><!-- /col-4 -->

            </div>
        </form>
    </div><!-- /body -->

</main>

<!-- ── Stiller ── -->
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

    /* Tip Seçim Kartları */
    .storage-type-card {
        display: block;
        cursor: pointer;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        padding: 16px 12px;
        transition: border-color .15s, background .15s;
        height: 100%;
    }

    .storage-type-card:hover {
        background: var(--body-bg);
    }

    .storage-type-card.active {
        border-color: var(--accent-blue);
        background: #f0f6ff;
    }

    body.dark .storage-type-card.active {
        background: rgba(27, 132, 255, .12);
    }
</style>

<script>
    var currentType = 'kargo';

    /* ── Tip Seçimi ── */
    function selectType(type) {
        currentType = type;
        document.getElementById('card-kargo').classList.toggle('active', type === 'kargo');
        document.getElementById('card-bagaj').classList.toggle('active', type === 'bagaj');
        document.getElementById('kargoLinkBlock').style.display = (type === 'kargo') ? 'block' : 'none';
        document.getElementById('ownerLabel').textContent = (type === 'bagaj') ? 'Yolcu Bilgileri' : 'Alıcı / Müşteri Bilgileri';
        document.getElementById('idNoBlock').style.display = (type === 'bagaj') ? 'block' : 'none';

        /* Bagaj için saatlik ücret farklı */
        if (type === 'bagaj') {
            document.getElementById('freeHours').value = '2';
            document.getElementById('hourlyRate').value = '3.00';
        } else {
            document.getElementById('freeHours').value = '4';
            document.getElementById('hourlyRate').value = '2.00';
        }
        recalcFee();
    }

    /* ── Kargo Takip Ara ── */
    var mockShipments = {
        'TRK-240224-001': { receiver: 'Ahmet Yılmaz', phone: '0532 xxx xx xx', city: 'Ankara' },
        'TRK-240224-002': { receiver: 'Fatma Kaya', phone: '0541 xxx xx xx', city: 'İzmir' },
        'TRK-240224-004': { receiver: 'Caner Yıldız', phone: '0551 xxx xx xx', city: 'Antalya' },
    };

    function lookupShipment() {
        var val = document.getElementById('linkedTracking').value.trim().toUpperCase();
        if (!val) return;
        var s = mockShipments[val];
        var infoEl = document.getElementById('linkedShipmentInfo');
        var infoTxt = document.getElementById('linkedInfo');

        if (s) {
            document.getElementById('ownerName').value = s.receiver;
            document.getElementById('ownerPhone').value = s.phone;
            infoTxt.textContent = val + ' → ' + s.receiver + ' · ' + s.city;
            infoEl.style.display = 'block';
            showToast('Kargo bulundu, bilgiler dolduruldu.', 'success');
        } else {
            infoEl.style.display = 'none';
            showToast('Kargo bulunamadı: ' + val, 'error');
        }
    }

    /* ── Tahmini Ücret Hesapla ── */
    function recalcFee() {
        var freeH = parseFloat(document.getElementById('freeHours').value) || 0;
        var rate = parseFloat(document.getElementById('hourlyRate').value) || 0;
        var est = parseFloat(document.getElementById('estHours').value) || 0;

        if (est <= 0) { document.getElementById('feePreview').style.display = 'none'; return; }

        var paidH = Math.max(0, est - freeH);
        var fee = paidH * rate;
        document.getElementById('feePreview').style.display = 'block';
        document.getElementById('feePreviewAmt').textContent = '₺' + fee.toFixed(2);
        document.getElementById('feePreviewNote').textContent =
            est + ' saat tahmini süre — ' + freeH + ' saat ücretsiz — ' + paidH.toFixed(1) + ' saat × ₺' + rate.toFixed(2);
    }

    /* ── Kaydet ── */
    function submitStorage(e) {
        e.preventDefault();
        showToast('Emanet kaydı oluşturuldu! Fiş hazırlanıyor...', 'success');
        /* TODO: AJAX */
    }
    function saveOnly() {
        showToast('Emanet kaydedildi.', 'success');
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

    /* İlk yüklemede kargo tipini aktif et */
    selectType('kargo');
</script>