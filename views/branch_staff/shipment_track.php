<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Kargo Sorgula</div>
            <div class="breadcrumb">
                <a href="?page=dashboard">Ana Sayfa</a>
                <span class="sep">›</span>
                <span>Global Kargo Sorgulama</span>
            </div>
        </div>
    </div>

    <div style="padding:18px 26px 40px;">

        <!-- ══ ARAMA KARTI ══ -->
        <div class="card mb-4" style="padding:28px;">
            <div class="text-center mb-4">
                <div
                    style="width:52px;height:52px;background:#e8f1ff;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                    <i class="bi bi-search" style="color:#1b84ff;font-size:1.4rem;"></i>
                </div>
                <div style="font-size:1rem;font-weight:700;color:var(--text-dark);">Global Kargo Sorgulama</div>
                <div style="font-size:.8rem;color:var(--text-muted);margin-top:4px;">Takip no, telefon numarası veya
                    gönderici / alıcı adıyla sorgulayabilirsiniz</div>
            </div>

            <!-- Arama Formu -->
            <div class="row g-2 justify-content-center">
                <div class="col-12 col-md-5">
                    <div style="position:relative;">
                        <i class="bi bi-upc-scan"
                            style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#a3aab8;font-size:1rem;"></i>
                        <input type="text" id="trackInput" class="form-input"
                            style="padding-left:40px;font-size:.9rem;height:44px;"
                            placeholder="TRK-240224-001 veya 053x.." onkeydown="if(event.key==='Enter') doSearch()" />
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <select id="searchType" class="form-input" style="height:44px;font-size:.86rem;">
                        <option value="tracking">Takip Numarası</option>
                        <option value="phone">Telefon No</option>
                        <option value="sender">Gönderici Adı</option>
                        <option value="receiver">Alıcı Adı</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <button onclick="doSearch()" class="btn-primary-sm w-100"
                        style="height:44px;font-size:.9rem;border-radius:8px;">
                        <i class="bi bi-search me-1"></i> Sorgula
                    </button>
                </div>
            </div>

            <!-- Hızlı Butonlar -->
            <div class="d-flex flex-wrap gap-2 justify-content-center mt-3">
                <span style="font-size:.75rem;color:var(--text-muted);align-self:center;">Hızlı sorgula:</span>
                <button class="btn-outline-secondary-sm" style="font-size:.75rem;padding:4px 10px;"
                    onclick="quickSearch('TRK-240224-001')">TRK-240224-001</button>
                <button class="btn-outline-secondary-sm" style="font-size:.75rem;padding:4px 10px;"
                    onclick="quickSearch('TRK-240224-002')">TRK-240224-002</button>
                <button class="btn-outline-secondary-sm" style="font-size:.75px;padding:4px 10px;"
                    onclick="quickSearch('TRK-240224-003')">TRK-240224-003</button>
            </div>
        </div>

        <!-- ══ SONUÇ ALANI (başlangıçta gizli) ══ -->
        <div id="trackResult" style="display:none;">

            <!-- ── Kargo Özet Kartı ── -->
            <div class="card mb-3" style="padding:20px;">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
                    <div>
                        <div
                            style="font-size:.72rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;">
                            Takip Numarası</div>
                        <div style="font-family:monospace;font-size:1.1rem;font-weight:700;color:var(--accent-blue);"
                            id="res-trackNo">TRK-240224-001</div>
                    </div>
                    <span class="status-badge status-sevkte" id="res-status"
                        style="font-size:.82rem;padding:5px 14px;">Sevkte</span>
                </div>

                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="form-label-sm">Gönderici</div>
                        <div style="font-size:.84rem;font-weight:600;" id="res-sender">Ahmet Yılmaz</div>
                        <div style="font-size:.73rem;color:var(--text-muted);" id="res-senderPhone">0532 xxx xx xx</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="form-label-sm">Alıcı</div>
                        <div style="font-size:.84rem;font-weight:600;" id="res-receiver">Mehmet Demir</div>
                        <div style="font-size:.73rem;color:var(--text-muted);" id="res-receiverPhone">0505 xxx xx xx
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="form-label-sm">Kalkış</div>
                        <div style="font-size:.84rem;font-weight:600;" id="res-from">İstanbul</div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="form-label-sm">Varış</div>
                        <div style="font-size:.84rem;font-weight:600;" id="res-to">Ankara</div>
                    </div>
                    <div class="col-6 col-md-1">
                        <div class="form-label-sm">Ağırlık</div>
                        <div style="font-size:.84rem;font-weight:600;" id="res-weight">2 kg</div>
                    </div>
                    <div class="col-6 col-md-1">
                        <div class="form-label-sm">Ücret</div>
                        <div style="font-size:.84rem;font-weight:600;color:var(--accent-blue);" id="res-price">₺85</div>
                    </div>
                </div>
            </div>

            <!-- ── Timeline ── -->
            <div class="card mb-3" style="padding:20px;">
                <div class="section-label mb-4">Kargo Hareketleri</div>
                <div class="track-timeline" id="res-timeline">

                    <div class="track-step done">
                        <div class="track-dot"><i class="bi bi-check-lg"></i></div>
                        <div class="track-content">
                            <div class="track-title">Kargo Kabul Edildi</div>
                            <div class="track-meta">İstanbul Otogar Şubesi · 24.02.2026 08:14</div>
                        </div>
                    </div>

                    <div class="track-step done">
                        <div class="track-dot"><i class="bi bi-check-lg"></i></div>
                        <div class="track-content">
                            <div class="track-title">Sevke Hazırlandı</div>
                            <div class="track-meta">İstanbul Otogar Şubesi · 24.02.2026 09:00</div>
                        </div>
                    </div>

                    <div class="track-step active">
                        <div class="track-dot"><i class="bi bi-bus-front"></i></div>
                        <div class="track-content">
                            <div class="track-title">Yolda — 34 ABC 001 (Metro Turizm)</div>
                            <div class="track-meta">Tahmini varış: 24.02.2026 14:30</div>
                        </div>
                    </div>

                    <div class="track-step pending">
                        <div class="track-dot"><i class="bi bi-arrow-down"></i></div>
                        <div class="track-content">
                            <div class="track-title">Şubeye İndi / Emanette</div>
                            <div class="track-meta">Bekleniyor</div>
                        </div>
                    </div>

                    <div class="track-step pending">
                        <div class="track-dot"><i class="bi bi-person-check"></i></div>
                        <div class="track-content">
                            <div class="track-title">Teslim Edildi</div>
                            <div class="track-meta">Bekleniyor</div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ── Aynı Kişiye Ait Diğer Kargolar ── -->
            <div class="card" style="padding:20px;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="section-label">Gönderiye Ait Diğer Kargolar</div>
                        <div class="section-sub">Aynı gönderici / alıcının son 10 kargosu</div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Takip No</th>
                                <th>Tarih</th>
                                <th>Varış</th>
                                <th>Ücret</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span
                                        style="font-family:monospace;font-size:.79rem;color:var(--accent-blue);font-weight:600;">TRK-240223-018</span>
                                </td>
                                <td style="font-size:.79rem;color:var(--text-muted);">23.02.2026</td>
                                <td style="font-size:.81rem;">Ankara</td>
                                <td style="font-weight:600;font-size:.83rem;">₺75</td>
                                <td><span class="status-badge status-teslim">Teslim Edildi</span></td>
                            </tr>
                            <tr>
                                <td><span
                                        style="font-family:monospace;font-size:.79rem;color:var(--accent-blue);font-weight:600;">TRK-240220-005</span>
                                </td>
                                <td style="font-size:.79rem;color:var(--text-muted);">20.02.2026</td>
                                <td style="font-size:.81rem;">İzmir</td>
                                <td style="font-weight:600;font-size:.83rem;">₺110</td>
                                <td><span class="status-badge status-teslim">Teslim Edildi</span></td>
                            </tr>
                            <tr>
                                <td><span
                                        style="font-family:monospace;font-size:.79rem;color:var(--accent-blue);font-weight:600;">TRK-240215-033</span>
                                </td>
                                <td style="font-size:.79rem;color:var(--text-muted);">15.02.2026</td>
                                <td style="font-size:.81rem;">Bursa</td>
                                <td style="font-weight:600;font-size:.83rem;">₺60</td>
                                <td><span class="status-badge status-iptal">İptal</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div><!-- /#trackResult -->

        <!-- ══ Bulunamadı (başlangıçta gizli) ══ -->
        <div id="trackNotFound" style="display:none;">
            <div class="card text-center" style="padding:48px 24px;">
                <div style="font-size:3rem;margin-bottom:12px;opacity:.35;">📦</div>
                <div style="font-size:.95rem;font-weight:700;color:var(--text-dark);margin-bottom:6px;">Kargo Bulunamadı
                </div>
                <div style="font-size:.82rem;color:var(--text-muted);">Girdiğiniz bilgiye ait kargo kaydı sistemde
                    mevcut değil.<br>Takip numarasını kontrol edip tekrar deneyin.</div>
            </div>
        </div>

    </div><!-- /body -->

</main>

<!-- ── Timeline & Form Stilleri ── -->
<style>
    .form-label-sm {
        display: block;
        font-size: .72rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .03em;
        margin-bottom: 3px;
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

    /* Timeline */
    .track-timeline {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .track-step {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding-bottom: 24px;
        position: relative;
    }

    .track-step:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 32px;
        bottom: 0;
        width: 2px;
        background: var(--border-color);
    }

    .track-step.done::before {
        background: #17c964;
    }

    .track-step.active::before {
        background: linear-gradient(#1b84ff, var(--border-color));
    }

    .track-dot {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: .8rem;
        border: 2px solid var(--border-color);
        background: var(--card-bg);
        color: var(--text-muted);
        position: relative;
        z-index: 1;
    }

    .track-step.done .track-dot {
        border-color: #17c964;
        background: #17c964;
        color: #fff;
    }

    .track-step.active .track-dot {
        border-color: #1b84ff;
        background: #1b84ff;
        color: #fff;
        animation: pulse-dot 1.6s ease infinite;
    }

    @keyframes pulse-dot {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(27, 132, 255, .4);
        }

        50% {
            box-shadow: 0 0 0 6px rgba(27, 132, 255, .0);
        }
    }

    .track-step.pending .track-dot {
        border-color: var(--border-color);
        background: var(--body-bg);
        color: #b0b7c3;
    }

    .track-content {
        padding-top: 4px;
    }

    .track-title {
        font-size: .84rem;
        font-weight: 600;
        color: var(--text-dark);
    }

    .track-meta {
        font-size: .75rem;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .track-step.pending .track-title {
        color: var(--text-muted);
        font-weight: 500;
    }

    /* Badges */
    .status-sevkte {
        background: #e8f1ff;
        color: #145dc0;
    }

    .status-teslim {
        background: #e7f9f0;
        color: #0e8045;
    }

    .status-emanette {
        background: #fff8ec;
        color: #e08b00;
    }

    .status-iptal {
        background: #fff0f2;
        color: #c03060;
    }
</style>

<script>
    /* ── Mock veri ── */
    var mockData = {
        'TRK-240224-001': {
            trackNo: 'TRK-240224-001', status: 'sevkte', statusLabel: 'Sevkte',
            sender: 'Ahmet Yılmaz', senderPhone: '0532 xxx xx xx',
            receiver: 'Mehmet Demir', receiverPhone: '0505 xxx xx xx',
            from: 'İstanbul', to: 'Ankara', weight: '2 kg', price: '₺85',
            steps: [
                { state: 'done', icon: 'bi-check-lg', title: 'Kargo Kabul Edildi', meta: 'İstanbul Otogar · 24.02.2026 08:14' },
                { state: 'done', icon: 'bi-check-lg', title: 'Sevke Hazırlandı', meta: 'İstanbul Otogar · 24.02.2026 09:00' },
                { state: 'active', icon: 'bi-bus-front', title: 'Yolda — 34 ABC 001 (Metro)', meta: 'Tahmini varış: 24.02.2026 14:30' },
                { state: 'pending', icon: 'bi-arrow-down', title: 'Şubeye İndi / Emanette', meta: 'Bekleniyor' },
                { state: 'pending', icon: 'bi-person-check', title: 'Teslim Edildi', meta: 'Bekleniyor' },
            ]
        },
        'TRK-240224-002': {
            trackNo: 'TRK-240224-002', status: 'emanette', statusLabel: 'Emanette',
            sender: 'Fatma Kaya', senderPhone: '0541 xxx xx xx',
            receiver: 'Ayşe Çelik', receiverPhone: '0555 xxx xx xx',
            from: 'İstanbul', to: 'İzmir', weight: '5 kg', price: '₺120',
            steps: [
                { state: 'done', icon: 'bi-check-lg', title: 'Kargo Kabul Edildi', meta: 'İstanbul Otogar · 24.02.2026 09:30' },
                { state: 'done', icon: 'bi-check-lg', title: 'Sevke Hazırlandı', meta: 'İstanbul Otogar · 24.02.2026 10:00' },
                { state: 'done', icon: 'bi-check-lg', title: 'Yolda — 35 XY 002 (Pamukkale)', meta: '24.02.2026 10:30' },
                { state: 'active', icon: 'bi-archive', title: 'Şubeye İndi — Emanette', meta: 'İzmir Otogar · 24.02.2026 16:45' },
                { state: 'pending', icon: 'bi-person-check', title: 'Teslim Edildi', meta: 'Bekleniyor' },
            ]
        },
        'TRK-240224-003': {
            trackNo: 'TRK-240224-003', status: 'teslim', statusLabel: 'Teslim Edildi',
            sender: 'Ali Öztürk', senderPhone: '0533 xxx xx xx',
            receiver: 'Hasan Aydın', receiverPhone: '0544 xxx xx xx',
            from: 'İstanbul', to: 'Bursa', weight: '1 kg', price: '₺65',
            steps: [
                { state: 'done', icon: 'bi-check-lg', title: 'Kargo Kabul Edildi', meta: 'İstanbul Otogar · 24.02.2026 10:05' },
                { state: 'done', icon: 'bi-check-lg', title: 'Sevke Hazırlandı', meta: 'İstanbul Otogar · 24.02.2026 10:30' },
                { state: 'done', icon: 'bi-check-lg', title: 'Yolda — 06 KA 777 (Uludağ)', meta: '24.02.2026 11:00' },
                { state: 'done', icon: 'bi-check-lg', title: 'Şubeye İndi / Emanette', meta: 'Bursa Otogar · 24.02.2026 13:30' },
                { state: 'done', icon: 'bi-person-check', title: 'Teslim Edildi', meta: 'Bursa Otogar · 24.02.2026 14:10' },
            ]
        }
    };

    function doSearch() {
        var val = document.getElementById('trackInput').value.trim().toUpperCase();
        var type = document.getElementById('searchType').value;

        var result = null;

        if (type === 'tracking') {
            result = mockData[val] || null;
        } else {
            /* Diğer tip aramalarda mock olarak ilk kaydı döndür */
            if (val.length >= 3) result = mockData['TRK-240224-001'];
        }

        if (result) {
            renderResult(result);
        } else {
            document.getElementById('trackResult').style.display = 'none';
            document.getElementById('trackNotFound').style.display = 'block';
        }
    }

    function renderResult(d) {
        document.getElementById('trackNotFound').style.display = 'none';
        document.getElementById('trackResult').style.display = 'block';

        document.getElementById('res-trackNo').textContent = d.trackNo;
        document.getElementById('res-sender').textContent = d.sender;
        document.getElementById('res-senderPhone').textContent = d.senderPhone;
        document.getElementById('res-receiver').textContent = d.receiver;
        document.getElementById('res-receiverPhone').textContent = d.receiverPhone;
        document.getElementById('res-from').textContent = d.from;
        document.getElementById('res-to').textContent = d.to;
        document.getElementById('res-weight').textContent = d.weight;
        document.getElementById('res-price').textContent = d.price;

        var statusEl = document.getElementById('res-status');
        statusEl.textContent = d.statusLabel;
        statusEl.className = 'status-badge status-' + d.status;
        /* override font size */
        statusEl.style.cssText = 'font-size:.82rem;padding:5px 14px;';

        /* Timeline */
        var tl = document.getElementById('res-timeline');
        tl.innerHTML = d.steps.map(function (s) {
            return '<div class="track-step ' + s.state + '">' +
                '  <div class="track-dot"><i class="bi ' + s.icon + '"></i></div>' +
                '  <div class="track-content">' +
                '    <div class="track-title">' + s.title + '</div>' +
                '    <div class="track-meta">' + s.meta + '</div>' +
                '  </div>' +
                '</div>';
        }).join('');

        /* Smooth scroll */
        document.getElementById('trackResult').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function quickSearch(no) {
        document.getElementById('trackInput').value = no;
        document.getElementById('searchType').value = 'tracking';
        doSearch();
    }
</script>