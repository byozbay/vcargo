<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Kargo Teslim</div>
            <div class="breadcrumb">
                <a href="?page=dashboard">Ana Sayfa</a>
                <span class="sep">›</span>
                <a href="?page=shipment">Kargo İşlemleri</a>
                <span class="sep">›</span>
                <span>Kargo Teslim</span>
            </div>
        </div>
    </div>

    <div style="padding:18px 26px 40px;">
        <div class="row g-3">

            <!-- ══ SOL: Barkod Okutma + Kargo Detay ══ -->
            <div class="col-12 col-lg-8">

                <!-- ── Barkod Okutma ── -->
                <div class="card mb-3">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <div
                            style="width:32px;height:32px;background:#e8f1ff;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-upc-scan" style="color:#1b84ff;"></i>
                        </div>
                        <div class="section-label">Barkod / Takip Numarası</div>
                    </div>

                    <div class="row g-2">
                        <div class="col-12 col-md-9">
                            <div style="position:relative;">
                                <i class="bi bi-upc-scan"
                                    style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#a3aab8;font-size:1rem;"></i>
                                <input type="text" id="barcodeInput" class="form-input"
                                    style="padding-left:38px;font-size:.92rem;height:46px;font-family:monospace;"
                                    placeholder="Barkod okutun veya takip numarası girin..."
                                    onkeydown="if(event.key==='Enter') loadShipment()" autofocus />
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <button onclick="loadShipment()" class="btn-primary-sm w-100"
                                style="height:46px;font-size:.9rem;border-radius:8px;">
                                <i class="bi bi-search me-1"></i> Sorgula
                            </button>
                        </div>
                    </div>
                    <div style="font-size:.74rem;color:var(--text-muted);margin-top:8px;">
                        <i class="bi bi-lightbulb me-1"></i>
                        Hızlı test: <button class="btn-outline-secondary-sm" style="font-size:.72rem;padding:2px 8px;"
                            onclick="quickLoad('TRK-240224-002')">TRK-240224-002 (Emanette)</button>
                        <button class="btn-outline-secondary-sm ms-1" style="font-size:.72rem;padding:2px 8px;"
                            onclick="quickLoad('TRK-240224-004')">TRK-240224-004 (C.O.D)</button>
                    </div>
                </div>

                <!-- ── Kargo Bilgi Kartı (gizli başlar) ── -->
                <div id="shipmentCard" style="display:none;">
                    <div class="card mb-3">
                        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-4">
                            <div>
                                <div
                                    style="font-size:.72rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;">
                                    Takip Numarası</div>
                                <div style="font-family:monospace;font-size:1rem;font-weight:700;color:var(--accent-blue);"
                                    id="sc-trackNo"></div>
                            </div>
                            <span id="sc-status" class="status-badge status-emanette"
                                style="font-size:.8rem;padding:5px 14px;"></span>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <div class="form-label-sm">Gönderici</div>
                                <div style="font-size:.84rem;font-weight:600;" id="sc-sender"></div>
                                <div style="font-size:.73rem;color:var(--text-muted);" id="sc-senderCity"></div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-label-sm">Alıcı</div>
                                <div style="font-size:.84rem;font-weight:600;" id="sc-receiver"></div>
                                <div style="font-size:.73rem;color:var(--text-muted);" id="sc-receiverPhone"></div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-label-sm">Varış</div>
                                <div style="font-size:.84rem;font-weight:600;" id="sc-city"></div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-label-sm">Ağırlık</div>
                                <div style="font-size:.84rem;font-weight:600;" id="sc-weight"></div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-label-sm">Kargo Ücreti</div>
                                <div style="font-size:.84rem;font-weight:700;color:var(--accent-blue);" id="sc-price">
                                </div>
                            </div>
                        </div>

                        <!-- Emanet Ücreti Uyarısı -->
                        <div id="storageWarning"
                            style="display:none;padding:12px 14px;border-radius:8px;background:#fff8ec;border:1px solid #fde68a;margin-bottom:16px;">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-exclamation-triangle-fill"
                                    style="color:#e08b00;flex-shrink:0;margin-top:2px;"></i>
                                <div style="font-size:.82rem;">
                                    <strong>Emanet Süresi Uyarısı:</strong>
                                    <span id="storageMsg"></span>
                                </div>
                            </div>
                        </div>

                        <!-- C.O.D Uyarısı -->
                        <div id="codWarning"
                            style="display:none;padding:12px 14px;border-radius:8px;background:#f0f6ff;border:1px solid #bfdbfe;margin-bottom:16px;">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-info-circle-fill"
                                    style="color:#1b84ff;flex-shrink:0;margin-top:2px;"></i>
                                <div style="font-size:.82rem;">
                                    <strong>Kapıda Ödeme (C.O.D):</strong>
                                    <span id="codMsg"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Ücret Özeti -->
                        <div id="paymentSummary"
                            style="background:var(--body-bg);border-radius:8px;padding:14px 16px;margin-bottom:16px;">
                            <div class="d-flex flex-column gap-2" style="font-size:.83rem;">
                                <div class="d-flex justify-content-between">
                                    <span style="color:var(--text-muted);">Kargo Ücreti</span>
                                    <span id="sum-cargo" style="font-weight:600;color:var(--text-muted);">₺0.00</span>
                                </div>
                                <div class="d-flex justify-content-between" id="sum-storage-row"
                                    style="display:none!important;">
                                    <span style="color:var(--text-muted);">Emanet Ücreti <span id="sum-storageDur"
                                            style="font-size:.71rem;"></span></span>
                                    <span id="sum-storage" style="font-weight:600;color:#e08b00;">₺0.00</span>
                                </div>
                                <div class="d-flex justify-content-between" id="sum-cod-row"
                                    style="display:none!important;">
                                    <span style="color:var(--text-muted);">C.O.D (Tahsil edilecek)</span>
                                    <span id="sum-cod" style="font-weight:600;color:#1b84ff;">₺0.00</span>
                                </div>
                                <hr style="border-color:var(--border-color);margin:4px 0;" />
                                <div class="d-flex justify-content-between">
                                    <span style="font-weight:700;">Alıcıdan Tahsil</span>
                                    <span id="sum-total"
                                        style="font-weight:700;font-size:1.05rem;color:var(--accent-blue);">₺0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tahsilat Yöntemi -->
                        <div id="collectionBlock">
                            <label class="form-label-sm mb-2">Tahsilat Yöntemi</label>
                            <div class="d-flex gap-2 mb-3">
                                <label class="method-btn active" id="col-cash"
                                    onclick="selectCollection('cash',this)"><i class="bi bi-cash-coin"></i>
                                    Nakit</label>
                                <label class="method-btn" id="col-card" onclick="selectCollection('card',this)"><i
                                        class="bi bi-credit-card"></i> Kredi Kartı</label>
                                <label class="method-btn" id="col-split" onclick="selectCollection('split',this)"><i
                                        class="bi bi-intersect"></i> Parçalı</label>
                            </div>
                            <!-- Parçalı -->
                            <div id="splitBlock" style="display:none;" class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label-sm">Nakit (₺)</label>
                                    <input type="number" id="splitCash" class="form-input" placeholder="0.00" min="0"
                                        step="0.01" />
                                </div>
                                <div class="col-6">
                                    <label class="form-label-sm">Kart (₺)</label>
                                    <input type="number" id="splitCard" class="form-input" placeholder="0.00" min="0"
                                        step="0.01" />
                                </div>
                            </div>
                            <!-- Emanet İndirimi (sadece müdür yetkisi) -->
                            <div id="discountBlock" style="display:none;margin-bottom:12px;">
                                <label class="form-label-sm mb-2">Emanet İndirimi (Müdür Yetkisi)</label>
                                <div class="row g-2 align-items-center">
                                    <div class="col-7">
                                        <input type="number" id="discountAmount" class="form-input" placeholder="0.00"
                                            min="0" step="0.01" oninput="applyDiscount()" />
                                    </div>
                                    <div class="col-5">
                                        <button class="btn-outline-secondary-sm w-100"
                                            style="height:36px;font-size:.78rem;" onclick="requestManagerApproval()">
                                            <i class="bi bi-shield-lock me-1"></i> Müdür Onayı
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Not -->
                        <div>
                            <label class="form-label-sm mb-1">Teslim Notu (opsiyonel)</label>
                            <input type="text" id="deliveryNote" class="form-input"
                                placeholder="Alıcı imzaladı, komşuya verildi vb..." />
                        </div>
                    </div>
                </div>

                <!-- Bulunamadı -->
                <div id="notFoundCard" style="display:none;">
                    <div class="card text-center" style="padding:40px 20px;">
                        <div style="font-size:2.5rem;margin-bottom:10px;opacity:.35;">📦</div>
                        <div style="font-weight:700;margin-bottom:6px;">Kargo Bulunamadı</div>
                        <div style="font-size:.82rem;color:var(--text-muted);">Girdiğiniz takip numarasına ait kayıt
                            bulunamadı.</div>
                    </div>
                </div>

            </div><!-- /col-8 -->

            <!-- ══ SAĞ: Teslim Et + Son Teslimler ══ -->
            <div class="col-12 col-lg-4">

                <!-- Teslim Et -->
                <div class="card mb-3">
                    <div class="section-label mb-3">Teslim İşlemi</div>
                    <div class="d-flex flex-column gap-2">
                        <button id="btnDeliver" onclick="confirmDelivery()" class="btn-primary-sm w-100"
                            style="height:46px;font-size:.92rem;border-radius:8px;" disabled>
                            <i class="bi bi-check2-circle me-1"></i> Teslimi Onayla
                        </button>
                        <button onclick="printLabel()"
                            class="btn-outline-secondary-sm w-100 d-flex align-items-center justify-content-center gap-1"
                            style="height:38px;font-size:.84rem;border-radius:8px;" id="btnLabel" disabled>
                            <i class="bi bi-upc"></i> Barkod Etiket Bas
                        </button>
                        <button onclick="printReceipt()"
                            class="btn-outline-secondary-sm w-100 d-flex align-items-center justify-content-center gap-1"
                            style="height:38px;font-size:.84rem;border-radius:8px;" id="btnReceipt" disabled>
                            <i class="bi bi-receipt"></i> Fiş Bas
                        </button>
                    </div>
                </div>

                <!-- Son Teslimler -->
                <div class="card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="section-label">Son Teslimler</div>
                        <span style="font-size:.75rem;color:var(--text-muted);">Bu oturum</span>
                    </div>
                    <div id="recentDeliveries">
                        <div
                            style="font-size:.8rem;color:var(--text-muted);text-align:center;padding:20px 0;opacity:.6;">
                            <i class="bi bi-check2-all" style="font-size:1.4rem;display:block;margin-bottom:6px;"></i>
                            Henüz teslim yapılmadı
                        </div>
                    </div>
                </div>

            </div><!-- /col-4 -->

        </div><!-- /row -->
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

    .method-btn {
        flex: 1;
        text-align: center;
        padding: 7px 4px;
        border: 1.5px solid var(--border-color);
        border-radius: 7px;
        font-size: .78rem;
        font-weight: 600;
        cursor: pointer;
        color: var(--text-muted);
        background: var(--body-bg);
        transition: all .15s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .method-btn.active {
        border-color: var(--accent-blue);
        background: #e8f1ff;
        color: var(--accent-blue);
    }

    body.dark .method-btn.active {
        background: rgba(27, 132, 255, .15);
    }

    .status-emanette {
        background: #fff8ec;
        color: #e08b00;
    }

    .status-sevkte {
        background: #e8f1ff;
        color: #145dc0;
    }

    .status-teslim {
        background: #e7f9f0;
        color: #0e8045;
    }
</style>

<script>
    /* ── Mock Veri ── */
    var mockShipments = {
        'TRK-240224-002': {
            trackNo: 'TRK-240224-002', status: 'emanette', statusLabel: 'Emanette',
            sender: 'Fatma Kaya', senderCity: 'İstanbul',
            receiver: 'Ayşe Çelik', receiverPhone: '0555 xxx xx xx',
            city: 'İzmir', weight: '5 kg', price: 120,
            cod: 0,
            storageHours: 26, /* 26 saat emanette */
            storageFreeHours: 4, /* 4 saat ücretsiz */
            storageRatePerHour: 2 /* saatlik ücret */
        },
        'TRK-240224-004': {
            trackNo: 'TRK-240224-004', status: 'emanette', statusLabel: 'Emanette',
            sender: 'ABC Lojistik Ltd.', senderCity: 'İstanbul',
            receiver: 'Caner Yıldız', receiverPhone: '0551 xxx xx xx',
            city: 'Antalya', weight: '3 kg', price: 0, /* Cari — kargo bedeli 0 */
            cod: 145, /* C.O.D tutarı */
            storageHours: 2,
            storageFreeHours: 4,
            storageRatePerHour: 2
        }
    };

    var currentShipment = null;
    var deliveredList = [];

    /* ── Kargo Yükle ── */
    function loadShipment() {
        var val = document.getElementById('barcodeInput').value.trim().toUpperCase();
        if (!val) return;

        var s = mockShipments[val] || null;
        document.getElementById('shipmentCard').style.display = 'none';
        document.getElementById('notFoundCard').style.display = 'none';

        if (!s) { document.getElementById('notFoundCard').style.display = 'block'; return; }

        currentShipment = s;
        fillCard(s);
        document.getElementById('shipmentCard').style.display = 'block';
        document.getElementById('btnDeliver').disabled = false;
        document.getElementById('btnLabel').disabled = false;
        document.getElementById('btnReceipt').disabled = false;
    }

    function quickLoad(no) {
        document.getElementById('barcodeInput').value = no;
        loadShipment();
    }

    /* ── Kartı Doldur ── */
    function fillCard(s) {
        document.getElementById('sc-trackNo').textContent = s.trackNo;
        document.getElementById('sc-sender').textContent = s.sender;
        document.getElementById('sc-senderCity').textContent = s.senderCity;
        document.getElementById('sc-receiver').textContent = s.receiver;
        document.getElementById('sc-receiverPhone').textContent = s.receiverPhone;
        document.getElementById('sc-city').textContent = s.city;
        document.getElementById('sc-weight').textContent = s.weight;
        document.getElementById('sc-price').textContent = '₺' + s.price.toFixed(2);

        var statusEl = document.getElementById('sc-status');
        statusEl.textContent = s.statusLabel;
        statusEl.className = 'status-badge status-' + s.status;

        /* Emanet ücreti hesapla */
        var storageHours = Math.max(0, s.storageHours - s.storageFreeHours);
        var storageFee = storageHours > 0 ? storageHours * s.storageRatePerHour : 0;

        if (s.storageHours > s.storageFreeHours) {
            document.getElementById('storageWarning').style.display = 'block';
            document.getElementById('storageMsg').textContent =
                ' Kargo ' + s.storageHours + ' saattir emanette. ' + storageHours +
                ' ücretli saat × ₺' + s.storageRatePerHour + ' = ₺' + storageFee.toFixed(2);
            document.getElementById('sum-storage-row').style.display = 'flex';
            document.getElementById('sum-storage').textContent = '₺' + storageFee.toFixed(2);
            document.getElementById('sum-storageDur').textContent = '(' + storageHours + ' saat)';
            document.getElementById('discountBlock').style.display = 'block';
        } else {
            document.getElementById('storageWarning').style.display = 'none';
            document.getElementById('sum-storage-row').style.display = 'none';
            document.getElementById('discountBlock').style.display = 'none';
        }

        /* C.O.D */
        if (s.cod > 0) {
            document.getElementById('codWarning').style.display = 'block';
            document.getElementById('codMsg').textContent =
                ' Bu kargo kapıda ödemeli. Alıcıdan ₺' + s.cod.toFixed(2) + ' tahsil edilmesi gerekmektedir.';
            document.getElementById('sum-cod-row').style.display = 'flex';
            document.getElementById('sum-cod').textContent = '₺' + s.cod.toFixed(2);
        } else {
            document.getElementById('codWarning').style.display = 'none';
            document.getElementById('sum-cod-row').style.display = 'none';
        }

        document.getElementById('sum-cargo').textContent = '₺' + s.price.toFixed(2);
        var total = s.price + storageFee + s.cod;
        document.getElementById('sum-total').textContent = '₺' + total.toFixed(2);
    }

    /* ── İndirim Uygula ── */
    function applyDiscount() {
        if (!currentShipment) return;
        var discount = parseFloat(document.getElementById('discountAmount').value) || 0;
        var storageHours = Math.max(0, currentShipment.storageHours - currentShipment.storageFreeHours);
        var storageFee = Math.max(0, storageHours * currentShipment.storageRatePerHour - discount);
        var total = currentShipment.price + storageFee + currentShipment.cod;
        document.getElementById('sum-storage').textContent = '₺' + storageFee.toFixed(2);
        document.getElementById('sum-total').textContent = '₺' + total.toFixed(2);
    }

    /* ── Müdür Onayı (mock) ── */
    function requestManagerApproval() {
        showToast('Müdür onay talebi gönderildi.', 'info');
    }

    /* ── Tahsilat Yöntemi ── */
    function selectCollection(m, el) {
        document.querySelectorAll('.method-btn').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('splitBlock').style.display = (m === 'split') ? 'block' : 'none';
    }

    /* ── Teslimi Onayla ── */
    function confirmDelivery() {
        if (!currentShipment) return;
        deliveredList.unshift(currentShipment);
        renderRecentDeliveries();

        showToast('✓ ' + currentShipment.trackNo + ' başarıyla teslim edildi!', 'success');

        /* Temizle */
        currentShipment = null;
        document.getElementById('barcodeInput').value = '';
        document.getElementById('shipmentCard').style.display = 'none';
        document.getElementById('btnDeliver').disabled = true;
        document.getElementById('btnLabel').disabled = true;
        document.getElementById('btnReceipt').disabled = true;
        document.getElementById('barcodeInput').focus();
    }

    /* ── Son Teslimler ── */
    function renderRecentDeliveries() {
        var el = document.getElementById('recentDeliveries');
        el.innerHTML = deliveredList.slice(0, 6).map(function (s) {
            var now = new Date();
            return '<div class="d-flex align-items-center gap-2 mb-2">' +
                '<div style="width:30px;height:30px;background:#e7f9f0;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">' +
                '<i class="bi bi-check2" style="color:#0e8045;font-size:.9rem;"></i>' +
                '</div>' +
                '<div class="flex-grow-1">' +
                '<div style="font-size:.79rem;font-weight:600;font-family:monospace;color:var(--accent-blue);">' + s.trackNo + '</div>' +
                '<div style="font-size:.72rem;color:var(--text-muted);">' + s.receiver + ' · ' + now.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' }) + '</div>' +
                '</div>' +
                '<span class="status-badge status-teslim" style="font-size:.7rem;">Teslim</span>' +
                '</div>';
        }).join('');
    }

    function printLabel() { showToast('Barkod etiket gönderiliyor...', 'info'); }
    function printReceipt() { showToast('Fiş yazdırılıyor...', 'info'); }

    /* ── Toast ── */
    function showToast(msg, type) {
        var colors = { success: '#0e8045', error: '#c03060', info: '#1b84ff', warn: '#e08b00' };
        var t = document.createElement('div');
        t.style.cssText = 'position:fixed;bottom:24px;right:24px;padding:12px 20px;border-radius:8px;font-size:.83rem;font-weight:600;z-index:9999;box-shadow:0 4px 16px rgba(0,0,0,.18);color:#fff;background:' + (colors[type] || '#333') + ';transition:opacity .3s;max-width:360px;';
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(function () { t.style.opacity = '0'; setTimeout(function () { t.remove(); }, 300); }, 3200);
    }
</script>