<main class="main-content">

<!-- ── Page Header ── -->
<div class="page-header">
    <div>
        <div class="page-title">Yeni Kargo Kaydı</div>
        <div class="breadcrumb">
            <a href="?page=dashboard">Ana Sayfa</a>
            <span class="sep">›</span>
            <a href="?page=shipment">Kargo Listesi</a>
            <span class="sep">›</span>
            <span>Yeni Kayıt</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="?page=shipment" class="btn-outline-secondary-sm d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<div style="padding:18px 26px 40px;">
<form id="shipmentForm" onsubmit="submitShipment(event)">

    <div class="row g-3">

        <!-- ══ SOL KOLON ══ -->
        <div class="col-12 col-lg-8">

            <!-- ── 1. Gönderici Bilgileri ── -->
            <div class="card mb-3">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div style="width:32px;height:32px;background:#e8f1ff;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person-fill" style="color:#1b84ff;"></i>
                    </div>
                    <div class="section-label">Gönderici Bilgileri</div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label-sm">T.C. / Vergi No <span style="color:#f31260;">*</span></label>
                        <div style="position:relative;">
                            <input type="text" id="senderIdNo" class="form-input" maxlength="11"
                                   placeholder="11 haneli T.C. veya Vergi No" required
                                   oninput="autoFillSender(this.value)" />
                            <i class="bi bi-search" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#a3aab8;font-size:.85rem;pointer-events:none;"></i>
                        </div>
                        <div style="font-size:.71rem;color:var(--text-muted);margin-top:3px;">Kayıtlı müşteri ise otomatik doldurulur</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-sm">Ad Soyad / Firma Adı <span style="color:#f31260;">*</span></label>
                        <input type="text" id="senderName" class="form-input" placeholder="Gönderici tam adı" required />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-sm">Telefon <span style="color:#f31260;">*</span></label>
                        <input type="tel" id="senderPhone" class="form-input" placeholder="0 5xx xxx xx xx" required />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-sm">Şehir</label>
                        <input type="text" id="senderCity" class="form-input" placeholder="İstanbul" />
                    </div>
                </div>
            </div>

            <!-- ── 2. Alıcı Bilgileri ── -->
            <div class="card mb-3">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div style="width:32px;height:32px;background:#e7f9f0;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person-check-fill" style="color:#0e8045;"></i>
                    </div>
                    <div class="section-label">Alıcı Bilgileri</div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label-sm">T.C. / Vergi No <span style="color:#f31260;">*</span></label>
                        <div style="position:relative;">
                            <input type="text" id="receiverIdNo" class="form-input" maxlength="11"
                                   placeholder="11 haneli T.C. veya Vergi No" required
                                   oninput="autoFillReceiver(this.value)" />
                            <i class="bi bi-search" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#a3aab8;font-size:.85rem;pointer-events:none;"></i>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-sm">Ad Soyad / Firma Adı <span style="color:#f31260;">*</span></label>
                        <input type="text" id="receiverName" class="form-input" placeholder="Alıcı tam adı" required />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-sm">Telefon <span style="color:#f31260;">*</span></label>
                        <input type="tel" id="receiverPhone" class="form-input" placeholder="0 5xx xxx xx xx" required />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-sm">Varış Şehri <span style="color:#f31260;">*</span></label>
                        <select id="receiverCity" class="form-input" required>
                            <option value="">Şehir seçin...</option>
                            <?php
                            $base = new BaseModel();
                            $dbCities = $base->query("SELECT city_id, name, plate_code FROM cities WHERE is_active = 1 ORDER BY name");
                            foreach ($dbCities as $c):
                            ?>
                            <option value="<?= $c['city_id'] ?>"><?= htmlspecialchars($c['name']) ?> (<?= $c['plate_code'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label-sm">Adres (opsiyonel)</label>
                        <input type="text" id="receiverAddress" class="form-input" placeholder="Mahalle, sokak, bina no..." />
                    </div>
                </div>
            </div>

            <!-- ── 3. Kargo Bilgileri ── -->
            <div class="card mb-3">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div style="width:32px;height:32px;background:#fff8ec;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-box-seam-fill" style="color:#e08b00;"></i>
                    </div>
                    <div class="section-label">Kargo Bilgileri</div>
                </div>

                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <label class="form-label-sm">Ağırlık (kg)</label>
                        <input type="number" id="weight" class="form-input" placeholder="0.00" min="0" step="0.1"
                               oninput="calculatePrice()" />
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label-sm">Desi</label>
                        <input type="number" id="desi" class="form-input" placeholder="0" min="0"
                               oninput="calculatePrice()" />
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label-sm">Adet</label>
                        <input type="number" id="quantity" class="form-input" value="1" min="1"
                               oninput="calculatePrice()" />
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label-sm">Kargo Tipi</label>
                        <select id="cargoType" class="form-input">
                            <option value="standart">Standart</option>
                            <option value="kırılgan">Kırılgan</option>
                            <option value="soğuk_zincir">Soğuk Zincir</option>
                            <option value="değerli">Değerli Eşya</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label-sm">İçerik / Açıklama</label>
                        <input type="text" id="description" class="form-input" placeholder="Kargo içeriğini kısaca belirtin..." />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-sm">Beyan Değeri (₺)</label>
                        <input type="number" id="declaredValue" class="form-input" placeholder="0.00" min="0" step="0.01" />
                    </div>
                </div>
            </div>

            <!-- ── 4. Ek Hizmetler ── -->
            <div class="card">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div style="width:32px;height:32px;background:#f3e5f5;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-stars" style="color:#8e24aa;"></i>
                    </div>
                    <div class="section-label">Ek Hizmetler</div>
                </div>

                <div class="row g-2">

                    <div class="col-12 col-md-6">
                        <label class="service-option" id="opt-sms">
                            <input type="checkbox" id="svc_sms" onchange="calculatePrice()"/>
                            <div class="service-option-content">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div style="width:30px;height:30px;background:#e8f1ff;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="bi bi-chat-dots-fill" style="color:#1b84ff;font-size:.85rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:.82rem;font-weight:600;">SMS Bildirim</div>
                                        <div style="font-size:.72rem;color:var(--text-muted);">Alıcıya otomatik SMS</div>
                                    </div>
                                </div>
                                <span class="svc-price">+₺5</span>
                            </div>
                        </label>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="service-option" id="opt-courier">
                            <input type="checkbox" id="svc_courier" onchange="calculatePrice()"/>
                            <div class="service-option-content">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div style="width:30px;height:30px;background:#e7f9f0;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="bi bi-bicycle" style="color:#0e8045;font-size:.85rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:.82rem;font-weight:600;">Kurye Alım</div>
                                        <div style="font-size:.72rem;color:var(--text-muted);">Adresten kurye ile alım</div>
                                    </div>
                                </div>
                                <span class="svc-price">+₺30</span>
                            </div>
                        </label>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="service-option" id="opt-insurance">
                            <input type="checkbox" id="svc_insurance" onchange="calculatePrice()"/>
                            <div class="service-option-content">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div style="width:30px;height:30px;background:#fff8ec;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="bi bi-shield-check" style="color:#e08b00;font-size:.85rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:.82rem;font-weight:600;">Sigorta</div>
                                        <div style="font-size:.72rem;color:var(--text-muted);">Beyan değerinin %1'i</div>
                                    </div>
                                </div>
                                <span class="svc-price">+₺—</span>
                            </div>
                        </label>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="service-option" id="opt-vip">
                            <input type="checkbox" id="svc_vip" onchange="calculatePrice()"/>
                            <div class="service-option-content">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div style="width:30px;height:30px;background:#f3e5f5;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="bi bi-telephone-fill" style="color:#8e24aa;font-size:.85rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:.82rem;font-weight:600;">VIP Telefon Bildirim</div>
                                        <div style="font-size:.72rem;color:var(--text-muted);">Kişisel arama ile bildirim</div>
                                    </div>
                                </div>
                                <span class="svc-price">+₺15</span>
                            </div>
                        </label>
                    </div>

                </div>
            </div>

        </div><!-- /col-lg-8 -->

        <!-- ══ SAĞ KOLON — Ödeme & Özet ══ -->
        <div class="col-12 col-lg-4">

            <!-- Ödeme Tipi -->
            <div class="card mb-3">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div style="width:32px;height:32px;background:#e7f9f0;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-wallet2" style="color:#0e8045;"></i>
                    </div>
                    <div class="section-label">Ödeme Bilgileri</div>
                </div>

                <!-- Ödeme Yöntemi -->
                <label class="form-label-sm mb-2">Ödeme Tipi <span style="color:#f31260;">*</span></label>
                <div class="d-flex flex-column gap-2 mb-3">
                    <label class="payment-radio">
                        <input type="radio" name="paymentType" value="sender" checked onchange="togglePaymentMethod()" />
                        <span class="payment-radio-label">
                            <i class="bi bi-person" style="color:#1b84ff;"></i>
                            <span>Gönderici Öder</span>
                        </span>
                    </label>
                    <label class="payment-radio">
                        <input type="radio" name="paymentType" value="cod" onchange="togglePaymentMethod()" />
                        <span class="payment-radio-label">
                            <i class="bi bi-person-check" style="color:#e08b00;"></i>
                            <span>Alıcı Öder (C.O.D)</span>
                        </span>
                    </label>
                    <label class="payment-radio">
                        <input type="radio" name="paymentType" value="account" onchange="togglePaymentMethod()" />
                        <span class="payment-radio-label">
                            <i class="bi bi-building" style="color:#8e24aa;"></i>
                            <span>Cari Hesap</span>
                        </span>
                    </label>
                </div>

                <!-- Ödeme Yöntemi (Nakit / Kart) — sadece Gönderici Öder seçilince -->
                <div id="paymentMethodBlock">
                    <label class="form-label-sm mb-2">Tahsilat Yöntemi</label>
                    <div class="d-flex gap-2">
                        <label class="method-btn active" id="btn-cash" onclick="selectMethod('cash', this)">
                            <i class="bi bi-cash-coin"></i> Nakit
                        </label>
                        <label class="method-btn" id="btn-card" onclick="selectMethod('card', this)">
                            <i class="bi bi-credit-card"></i> Kredi Kartı
                        </label>
                        <label class="method-btn" id="btn-split" onclick="selectMethod('split', this)">
                            <i class="bi bi-intersect"></i> Parçalı
                        </label>
                    </div>
                    <!-- Parçalı ödeme alanı -->
                    <div id="splitBlock" style="display:none;margin-top:12px;" class="row g-2">
                        <div class="col-6">
                            <label class="form-label-sm">Nakit (₺)</label>
                            <input type="number" id="splitCash" class="form-input" placeholder="0.00" min="0" step="0.01" />
                        </div>
                        <div class="col-6">
                            <label class="form-label-sm">Kart (₺)</label>
                            <input type="number" id="splitCard" class="form-input" placeholder="0.00" min="0" step="0.01" />
                        </div>
                    </div>
                </div>

            </div>

            <!-- Fiyat Özeti -->
            <div class="card mb-3" style="background:var(--body-bg);">
                <div class="section-label mb-3">Fiyat Özeti</div>
                <div class="d-flex flex-column gap-2" style="font-size:.83rem;">
                    <div class="d-flex justify-content-between">
                        <span style="color:var(--text-muted);">Kargo Ücreti</span>
                        <span id="basePrice" style="font-weight:600;color:var(--text-muted);">₺0.00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span style="color:var(--text-muted);">Ek Hizmetler</span>
                        <span id="extraPrice" style="font-weight:600;color:var(--text-muted);">₺0.00</span>
                    </div>
                    <hr style="border-color:var(--border-color);margin:6px 0;" />
                    <div class="d-flex justify-content-between">
                        <span style="font-weight:700;color:var(--text-dark);">TOPLAM</span>
                        <span id="totalPrice" style="font-weight:700;font-size:1.05rem;color:var(--accent-blue);">₺0.00</span>
                    </div>
                </div>
            </div>

            <!-- Aksiyon Butonlar -->
            <div class="d-flex flex-column gap-2">
                <button type="submit" class="btn-primary-sm w-100" style="height:42px;font-size:.9rem;border-radius:8px;">
                    <i class="bi bi-check2-circle me-1"></i> Kaydet &amp; Barkod Bas
                </button>
                <button type="button" class="btn-outline-secondary-sm w-100" style="height:42px;font-size:.85rem;border-radius:8px;"
                        onclick="saveOnly()">
                    <i class="bi bi-floppy me-1"></i> Sadece Kaydet
                </button>
                <button type="reset" class="btn-outline-secondary-sm w-100" style="height:38px;font-size:.82rem;border-radius:8px;color:var(--text-muted);"
                        onclick="resetForm()">
                    <i class="bi bi-x-lg me-1"></i> Temizle
                </button>
            </div>

        </div><!-- /col-lg-4 -->

    </div><!-- /row -->

</form>
</div><!-- /body -->

</main>

<!-- ── Bileşene Özel Stiller ── -->


<script>
/* ── Ödeme tipi değişimi ── */
function togglePaymentMethod() {
    var type = document.querySelector('input[name="paymentType"]:checked').value;
    document.getElementById('paymentMethodBlock').style.display = (type === 'sender') ? 'block' : 'none';
    calculatePrice();
}

/* ── Tahsilat yöntemi ── */
var selectedMethod = 'cash';
function selectMethod(method, el) {
    selectedMethod = method;
    document.querySelectorAll('.method-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('splitBlock').style.display = (method === 'split') ? 'block' : 'none';
}

/* ── Fiyat Hesaplama (mock tarife) ── */
function calculatePrice() {
    var weight  = parseFloat(document.getElementById('weight').value)   || 0;
    var desi    = parseFloat(document.getElementById('desi').value)      || 0;
    var qty     = parseInt(document.getElementById('quantity').value)    || 1;
    var decVal  = parseFloat(document.getElementById('declaredValue').value) || 0;

    /* Temel ücret: max(ağırlık, desi) * tarife */
    var chargeable = Math.max(weight, desi);
    var base = chargeable <= 1 ? 50 : 50 + (chargeable - 1) * 12;
    base *= qty;

    /* Ek hizmetler */
    var extras = 0;
    if (document.getElementById('svc_sms').checked)      extras += 5;
    if (document.getElementById('svc_courier').checked)  extras += 30;
    if (document.getElementById('svc_vip').checked)      extras += 15;
    if (document.getElementById('svc_insurance').checked) {
        var ins = decVal * 0.01;
        extras += ins;
        document.querySelector('#opt-insurance .svc-price').textContent = '+₺' + ins.toFixed(2);
    } else {
        document.querySelector('#opt-insurance .svc-price').textContent = '+₺—';
    }

    document.getElementById('basePrice').textContent  = '₺' + base.toFixed(2);
    document.getElementById('extraPrice').textContent = '₺' + extras.toFixed(2);
    document.getElementById('totalPrice').textContent = '₺' + (base + extras).toFixed(2);
}

/* ── Kayıt & Barkod ── */
function submitShipment(e) {
    e.preventDefault();
    var paymentTypeMap = {'sender': 'SENDER_PAYS', 'cod': 'RECEIVER_PAYS', 'account': 'ACCOUNT'};
    var paymentMethodMap = {'cash': 'CASH', 'card': 'CARD', 'split': 'SPLIT'};
    var paymentType = document.querySelector('input[name="paymentType"]:checked').value;

    var data = {
        sender_name:       document.getElementById('senderName').value,
        sender_phone:      document.getElementById('senderPhone').value,
        sender_tc_no:      document.getElementById('senderIdNo').value,
        receiver_name:     document.getElementById('receiverName').value,
        receiver_phone:    document.getElementById('receiverPhone').value,
        receiver_tc_no:    document.getElementById('receiverIdNo').value,
        destination_city_id: document.getElementById('receiverCity').value,
        piece_count:       parseInt(document.getElementById('quantity').value) || 1,
        weight:            parseFloat(document.getElementById('weight').value) || 0,
        desi:              parseFloat(document.getElementById('desi').value) || 0,
        content_description: document.getElementById('description').value,
        cargo_fee:         parseFloat(document.getElementById('basePrice').textContent.replace('₺','')) || 0,
        service_fee:       parseFloat(document.getElementById('extraPrice').textContent.replace('₺','')) || 0,
        total_fee:         parseFloat(document.getElementById('totalPrice').textContent.replace('₺','')) || 0,
        payment_type:      paymentTypeMap[paymentType] || 'SENDER_PAYS',
        payment_method:    paymentMethodMap[selectedMethod] || 'CASH',
        payment_status:    paymentType === 'sender' ? 'paid' : 'pending',
        sms_notify:        document.getElementById('svc_sms').checked ? 1 : 0,
        courier_pickup:    document.getElementById('svc_courier').checked ? 1 : 0,
        vip_notify:        document.getElementById('svc_vip').checked ? 1 : 0,
    };

    fetch('api.php?action=shipments.create', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showToast('Kargo kaydedildi! Takip No: ' + res.shipment.tracking_no, 'success');
            setTimeout(() => { window.location.href = '?page=shipment'; }, 2000);
        } else {
            showToast('Hata: ' + (res.error || 'Bilinmeyen hata'), 'error');
        }
    })
    .catch(() => showToast('Sunucu hatası oluştu!', 'error'));
}
function saveOnly() {
    submitShipment(new Event('submit'));
}
function resetForm() {
    document.getElementById('shipmentForm').reset();
    document.getElementById('basePrice').textContent  = '₺0.00';
    document.getElementById('extraPrice').textContent = '₺0.00';
    document.getElementById('totalPrice').textContent = '₺0.00';
}

/* ── Müşteri Otomatik Doldurma ── */
function autoFillSender(val) {
    if (val.length === 11) {
        fetch('api.php?action=accounts.list&search=' + val)
        .then(r => r.json())
        .then(list => {
            if (list.length > 0) {
                document.getElementById('senderName').value = list[0].name || '';
                document.getElementById('senderPhone').value = list[0].phone || '';
                showToast('Kayıtlı müşteri bulundu!', 'success');
            }
        });
    }
}
function autoFillReceiver(val) {
    if (val.length === 11) {
        fetch('api.php?action=accounts.list&search=' + val)
        .then(r => r.json())
        .then(list => {
            if (list.length > 0) {
                document.getElementById('receiverName').value = list[0].name || '';
                document.getElementById('receiverPhone').value = list[0].phone || '';
                showToast('Kayıtlı alıcı bulundu!', 'success');
            }
        });
    }
}

/* ── Toast bildirim ── */
function showToast(msg, type) {
    var toast = document.createElement('div');
    toast.style.cssText = 'position:fixed;bottom:24px;right:24px;padding:12px 20px;border-radius:8px;font-size:.84rem;font-weight:600;z-index:9999;box-shadow:0 4px 16px rgba(0,0,0,.15);transition:opacity .3s;color:#fff;background:' + (type==='success' ? '#0e8045' : '#c03060');
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
}
</script>
