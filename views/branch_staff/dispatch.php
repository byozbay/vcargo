<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Kargo Sevk</div>
            <div class="breadcrumb">
                <a href="?page=dashboard">Ana Sayfa</a>
                <span class="sep">›</span>
                <a href="?page=shipment">Kargo İşlemleri</a>
                <span class="sep">›</span>
                <span>Kargo Sevk</span>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="?page=manifest" class="btn-outline-secondary-sm d-flex align-items-center gap-1">
                <i class="bi bi-card-list"></i> Manifesto Bas
            </a>
        </div>
    </div>

    <div style="padding:18px 26px 40px;">
        <div class="row g-3">

            <!-- ══ SOL: Sefer Seçimi + Kargo Ekle ══ -->
            <div class="col-12 col-lg-8">

                <!-- ── 1. Sefer Bilgileri ── -->
                <div class="card mb-3">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <div
                            style="width:32px;height:32px;background:#e8f1ff;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-bus-front" style="color:#1b84ff;"></i>
                        </div>
                        <div class="section-label">Sefer Bilgileri</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label-sm">Otobüs Firması <span style="color:#f31260;">*</span></label>
                            <select id="busCompany" class="form-input" onchange="onCompanyChange()">
                                <option value="">Firma seçin...</option>
                                <option value="metro">Metro Turizm</option>
                                <option value="pamukkale">Pamukkale Turizm</option>
                                <option value="uludag">Uludağ Turizm</option>
                                <option value="kamil">Kamil Koç</option>
                                <option value="obilet">OBİLET / Varan</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label-sm">Plaka <span style="color:#f31260;">*</span></label>
                            <input type="text" id="busPlate" class="form-input" placeholder="34 ABC 001"
                                oninput="this.value = this.value.toUpperCase()" />
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label-sm">Şoför Adı</label>
                            <input type="text" id="driverName" class="form-input" placeholder="Ad Soyad" />
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label-sm">Şoför Telefonu</label>
                            <input type="tel" id="driverPhone" class="form-input" placeholder="0 5xx xxx xx xx" />
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label-sm">Sefer Saati</label>
                            <input type="time" id="departureTime" class="form-input" value="<?= date('H:i') ?>" />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label-sm">Varış Güzergahı</label>
                            <input type="text" id="busRoute" class="form-input"
                                placeholder="İstanbul → Ankara → Sivas" />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label-sm">Komisyon Oranı (%)</label>
                            <div style="position:relative;">
                                <input type="number" id="commissionRate" class="form-input" value="15" min="0" max="100"
                                    step="0.5" style="padding-right:40px;" oninput="recalcAll()" />
                                <span
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:.8rem;color:var(--text-muted);">%</span>
                            </div>
                            <div style="font-size:.71rem;color:var(--text-muted);margin-top:3px;">Firma komisyonu total
                                ücretten düşülür</div>
                        </div>
                    </div>
                </div>

                <!-- ── 2. Kargo Barkod Okutma ── -->
                <div class="card mb-3">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <div
                            style="width:32px;height:32px;background:#e7f9f0;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-upc-scan" style="color:#0e8045;"></i>
                        </div>
                        <div class="section-label">Kargo Ekle</div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-12 col-md-8">
                            <div style="position:relative;">
                                <i class="bi bi-upc-scan"
                                    style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#a3aab8;"></i>
                                <input type="text" id="barcodeInput" class="form-input" style="padding-left:36px;"
                                    placeholder="Takip no veya barkod okut / yaz..."
                                    onkeydown="if(event.key==='Enter'){ addShipment(); this.value=''; }" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <button class="btn-primary-sm w-100" style="height:38px;" onclick="addShipment()">
                                <i class="bi bi-plus-lg me-1"></i> Ekle
                            </button>
                        </div>
                    </div>
                    <div style="font-size:.75rem;color:var(--text-muted);">
                        <i class="bi bi-info-circle me-1"></i> Barkod tarayıcı ile okutabilir veya takip numarasını
                        yazıp Enter'a basabilirsiniz.
                    </div>
                </div>

                <!-- ── 3. Sevk Listesi ── -->
                <div class="card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="section-label">Sevk Listesi</div>
                            <div class="section-sub" id="shipmentCountLabel">0 kargo eklendi</div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn-outline-secondary-sm d-flex align-items-center gap-1"
                                style="font-size:.78rem;" onclick="clearList()">
                                <i class="bi bi-x-lg"></i> Listeyi Temizle
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="orders-table" id="dispatchTable">
                            <thead>
                                <tr>
                                    <th style="width:36px;">#</th>
                                    <th>Takip No</th>
                                    <th>Alıcı</th>
                                    <th>Varış</th>
                                    <th>Ücret</th>
                                    <th>Net Ödeme</th>
                                    <th style="width:50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="dispatchBody">
                                <tr id="emptyRow">
                                    <td colspan="7" class="text-center"
                                        style="padding:32px;color:var(--text-muted);font-size:.82rem;">
                                        <i class="bi bi-inbox"
                                            style="font-size:1.8rem;display:block;margin-bottom:8px;opacity:.4;"></i>
                                        Henüz kargo eklenmedi. Barkod okutun veya takip no girin.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div><!-- /col-lg-8 -->

            <!-- ══ SAĞ: Özet & Onay ══ -->
            <div class="col-12 col-lg-4">

                <!-- Sefer Özeti -->
                <div class="card mb-3" style="background:var(--body-bg);">
                    <div class="section-label mb-3">Sefer & Ödeme Özeti</div>
                    <div class="d-flex flex-column gap-2" style="font-size:.83rem;">
                        <div class="d-flex justify-content-between">
                            <span style="color:var(--text-muted);">Kargo Adedi</span>
                            <span id="sumCount" style="font-weight:600;">0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span style="color:var(--text-muted);">Toplam Kargo Ücreti</span>
                            <span id="sumTotal" style="font-weight:600;color:var(--text-muted);">₺0.00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span style="color:var(--text-muted);">Firma Komisyonu</span>
                            <span id="sumCommission" style="font-weight:600;color:#f31260;">-₺0.00</span>
                        </div>
                        <hr style="border-color:var(--border-color);margin:4px 0;" />
                        <div class="d-flex justify-content-between">
                            <span style="font-weight:700;">Şoföre Ödenecek Net</span>
                            <span id="sumNet"
                                style="font-weight:700;font-size:1.05rem;color:var(--accent-blue);">₺0.00</span>
                        </div>
                    </div>

                    <!-- Nakit / Kart ödeme -->
                    <div class="mt-3">
                        <label class="form-label-sm mb-2">Şoföre Ödeme Yöntemi</label>
                        <div class="d-flex gap-2">
                            <label class="method-btn active" id="pay-cash" onclick="selectPay('cash', this)"><i
                                    class="bi bi-cash-coin"></i> Nakit</label>
                            <label class="method-btn" id="pay-card" onclick="selectPay('card', this)"><i
                                    class="bi bi-credit-card"></i> Kart</label>
                        </div>
                    </div>
                </div>

                <!-- Aktif Seferler (bugün) -->
                <div class="card mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="section-label">Bugünkü Aktif Seferler</div>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <?php
                        $activeBuses = [
                            ['plate' => '34 ABC 001', 'company' => 'Metro Turizm', 'route' => 'Ankara', 'time' => '08:00', 'count' => 12],
                            ['plate' => '35 XY 002', 'company' => 'Pamukkale', 'route' => 'İzmir', 'time' => '10:30', 'count' => 8],
                            ['plate' => '06 KA 777', 'company' => 'Uludağ Turizm', 'route' => 'Bursa', 'time' => '15:00', 'count' => 5],
                        ];
                        foreach ($activeBuses as $bus): ?>
                            <div class="d-flex align-items-center gap-2 p-2"
                                style="border:1px solid var(--border-color);border-radius:7px;cursor:pointer;"
                                onclick="fillBus('<?= $bus['plate'] ?>', '<?= $bus['company'] ?>', '<?= $bus['time'] ?>')"
                                onmouseover="this.style.background='var(--body-bg)'" onmouseout="this.style.background=''">
                                <div
                                    style="width:34px;height:34px;background:#e8f1ff;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="bi bi-bus-front" style="color:#1b84ff;font-size:.9rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div style="font-size:.8rem;font-weight:600;">
                                        <?= $bus['plate'] ?> →
                                        <?= $bus['route'] ?>
                                    </div>
                                    <div style="font-size:.72rem;color:var(--text-muted);">
                                        <?= $bus['company'] ?> ·
                                        <?= $bus['time'] ?>
                                    </div>
                                </div>
                                <span class="status-badge" style="background:#e8f1ff;color:#1b84ff;font-size:.7rem;">
                                    <?= $bus['count'] ?> kargo
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Aksiyon -->
                <div class="d-flex flex-column gap-2">
                    <button onclick="confirmDispatch()" class="btn-primary-sm w-100"
                        style="height:42px;font-size:.9rem;border-radius:8px;">
                        <i class="bi bi-send me-1"></i> Sevki Onayla &amp; Teslim Et
                    </button>
                    <button onclick="printManifest()" class="btn-outline-secondary-sm w-100"
                        style="height:38px;font-size:.84rem;border-radius:8px;">
                        <i class="bi bi-card-list me-1"></i> Manifesto Bas
                    </button>
                </div>

            </div><!-- /col-lg-4 -->

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

    .status-sevkte {
        background: #e8f1ff;
        color: #145dc0;
    }

    .status-teslim {
        background: #e7f9f0;
        color: #0e8045;
    }

    .status-iptal {
        background: #fff0f2;
        color: #c03060;
    }
</style>

<script>
    /* ── Mock kargo verisi ── */
    var mockShipments = {
        'TRK-240224-001': { receiver: 'Ahmet Yılmaz', city: 'Ankara', price: 85 },
        'TRK-240224-002': { receiver: 'Fatma Kaya', city: 'İzmir', price: 120 },
        'TRK-240224-003': { receiver: 'Ali Öztürk', city: 'Bursa', price: 65 },
        'TRK-240224-004': { receiver: 'ABC Lojistik', city: 'Antalya', price: 145 },
        'TRK-240224-005': { receiver: 'Kemal Şahin', city: 'Konya', price: 95 },
        'TRK-240224-006': { receiver: 'Meral Güneş', city: 'Adana', price: 110 },
    };

    var dispatchList = []; /* { trackNo, receiver, city, price } */

    /* ── Kargo Ekle ── */
    function addShipment() {
        var val = document.getElementById('barcodeInput').value.trim().toUpperCase();
        if (!val) return;

        if (dispatchList.find(x => x.trackNo === val)) {
            showToast('Bu kargo zaten listede!', 'warn'); return;
        }
        var s = mockShipments[val];
        if (!s) {
            showToast('Kargo bulunamadı: ' + val, 'error'); return;
        }
        dispatchList.push({ trackNo: val, ...s });
        document.getElementById('barcodeInput').value = '';
        renderDispatchTable();
        recalcAll();
    }

    /* ── Tabloyu Yenile ── */
    function renderDispatchTable() {
        var body = document.getElementById('dispatchBody');
        if (dispatchList.length === 0) {
            body.innerHTML = '<tr id="emptyRow"><td colspan="7" class="text-center" style="padding:32px;color:var(--text-muted);font-size:.82rem;"><i class="bi bi-inbox" style="font-size:1.8rem;display:block;margin-bottom:8px;opacity:.4;"></i>Henüz kargo eklenmedi.</td></tr>';
            document.getElementById('shipmentCountLabel').textContent = '0 kargo eklendi';
            return;
        }
        var comm = parseFloat(document.getElementById('commissionRate').value) || 0;
        body.innerHTML = dispatchList.map(function (s, i) {
            var net = (s.price * (1 - comm / 100)).toFixed(2);
            return '<tr>' +
                '<td style="font-size:.78rem;color:var(--text-muted);">' + (i + 1) + '</td>' +
                '<td><span style="font-family:monospace;font-size:.79rem;color:var(--accent-blue);font-weight:600;">' + s.trackNo + '</span></td>' +
                '<td><div style="font-size:.81rem;font-weight:500;">' + s.receiver + '</div></td>' +
                '<td style="font-size:.81rem;">' + s.city + '</td>' +
                '<td style="font-weight:600;font-size:.83rem;">₺' + s.price.toFixed(2) + '</td>' +
                '<td style="font-weight:600;font-size:.83rem;color:#0e8045;">₺' + net + '</td>' +
                '<td><button class="icon-btn-circle" onclick="removeShipment(' + i + ')" title="Kaldır"><i class="bi bi-x" style="font-size:.8rem;color:#c03060;"></i></button></td>' +
                '</tr>';
        }).join('');
        document.getElementById('shipmentCountLabel').textContent = dispatchList.length + ' kargo eklendi';
    }

    /* ── Kargo Kaldır ── */
    function removeShipment(idx) {
        dispatchList.splice(idx, 1);
        renderDispatchTable();
        recalcAll();
    }

    /* ── Listeyi Temizle ── */
    function clearList() {
        if (dispatchList.length === 0) return;
        if (!confirm('Sevk listesi temizlensin mi?')) return;
        dispatchList = [];
        renderDispatchTable();
        recalcAll();
    }

    /* ── Toplam Hesapla ── */
    function recalcAll() {
        var comm = parseFloat(document.getElementById('commissionRate').value) || 0;
        var total = dispatchList.reduce(function (s, x) { return s + x.price; }, 0);
        var commission = total * comm / 100;
        var net = total - commission;

        document.getElementById('sumCount').textContent = dispatchList.length;
        document.getElementById('sumTotal').textContent = '₺' + total.toFixed(2);
        document.getElementById('sumCommission').textContent = '-₺' + commission.toFixed(2);
        document.getElementById('sumNet').textContent = '₺' + net.toFixed(2);

        /* komisyon oranı değişince tabloyu da yenile */
        if (dispatchList.length > 0) renderDispatchTable();
    }

    /* ── Aktif Seferden Otomatik Doldur ── */
    function fillBus(plate, company, time) {
        document.getElementById('busPlate').value = plate;
        document.getElementById('departureTime').value = time;
        /* Firma eşleştir */
        var map = { 'Metro Turizm': 'metro', 'Pamukkale': 'pamukkale', 'Uludağ Turizm': 'uludag' };
        var sel = document.getElementById('busCompany');
        for (var i = 0; i < sel.options.length; i++) {
            if (sel.options[i].text === company) { sel.selectedIndex = i; break; }
        }
        showToast(plate + ' seferi seçildi.', 'success');
    }

    /* ── Sevki Onayla ── */
    function confirmDispatch() {
        if (dispatchList.length === 0) { showToast('Sevk listesi boş!', 'error'); return; }
        if (!document.getElementById('busPlate').value) { showToast('Plaka giriniz!', 'error'); return; }
        showToast(dispatchList.length + ' kargo başarıyla sevk edildi. Manifesto hazırlanıyor...', 'success');
        /* TODO: AJAX */
    }

    /* ── Manifesto Bas ── */
    function printManifest() {
        if (dispatchList.length === 0) { showToast('Manifesto için önce kargo ekleyin!', 'error'); return; }
        window.print(); /* TODO: gerçek manifesto PDF */
    }

    /* ── Ödeme Yöntemi ── */
    function selectPay(m, el) {
        document.querySelectorAll('.method-btn').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
    }

    /* ── Toast ── */
    function showToast(msg, type) {
        var colors = { success: '#0e8045', error: '#c03060', warn: '#e08b00' };
        var t = document.createElement('div');
        t.style.cssText = 'position:fixed;bottom:24px;right:24px;padding:12px 20px;border-radius:8px;font-size:.83rem;font-weight:600;z-index:9999;box-shadow:0 4px 16px rgba(0,0,0,.18);color:#fff;background:' + (colors[type] || '#333') + ';transition:opacity .3s;';
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(function () { t.style.opacity = '0'; setTimeout(function () { t.remove(); }, 300); }, 3200);
    }
</script>