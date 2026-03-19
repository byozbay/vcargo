<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Emanet Listesi</div>
            <div class="breadcrumb">
                <a href="?page=dashboard">Ana Sayfa</a>
                <span class="sep">›</span>
                <span>Emanet & Depo</span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="?page=storage_create" class="btn-primary-sm d-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i> Yeni Emanet Kaydı
            </a>
            <a href="?page=delivery" class="btn-outline-secondary-sm d-flex align-items-center gap-1">
                <i class="bi bi-check2-circle"></i> Kargo Teslim
            </a>
        </div>
    </div>

    <div style="padding:18px 26px 40px;">

        <!-- ══ ROW 1 — KPI Kartları ══ -->
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="card" style="padding:14px 16px;">
                    <div class="card-sm-label">Toplam Emanet</div>
                    <div class="stat-value" style="font-size:1.5rem;" id="kpiTotal">—</div>
                    <div style="font-size:.73rem;color:var(--text-muted);margin-top:2px;">Aktif kayıt</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card" style="padding:14px 16px;">
                    <div class="card-sm-label">Ücretli Süreye Giren</div>
                    <div class="stat-value" style="font-size:1.5rem;color:#e08b00;" id="kpiPaid">—</div>
                    <div style="font-size:.73rem;color:#e08b00;margin-top:2px;">4 saat üstü</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card" style="padding:14px 16px;">
                    <div class="card-sm-label">Bekleyen Bakım Ücret</div>
                    <div class="stat-value" style="font-size:1.5rem;color:#f31260;" id="kpiPending">—</div>
                    <div style="font-size:.73rem;color:var(--text-muted);margin-top:2px;">Tahsil edilmedi</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card" style="padding:14px 16px;">
                    <div class="card-sm-label">Bugün Teslim Edilen</div>
                    <div class="stat-value" style="font-size:1.5rem;color:#0e8045;" id="kpiDelivered">—</div>
                    <div style="font-size:.73rem;color:var(--text-muted);margin-top:2px;">Emanetten çıktı</div>
                </div>
            </div>
        </div>

        <!-- ══ Filtre Satırı ══ -->
        <div class="card mb-3" style="padding:14px 20px;">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label-sm">Ara (Takip No / Alıcı)</label>
                    <div style="position:relative;">
                        <i class="bi bi-search"
                            style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#a3aab8;font-size:.82rem;"></i>
                        <input type="text" id="storageSearch" class="form-input" style="padding-left:32px;"
                            placeholder="TRK-... veya alıcı adı" oninput="filterStorage()" />
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label-sm">Süre Durumu</label>
                    <select id="filterSure" class="form-input" onchange="filterStorage()">
                        <option value="">Tümü</option>
                        <option value="free">Ücretsiz Sürede</option>
                        <option value="paid">Ücretli Süreye Girdi</option>
                        <option value="critical">Kritik (24h+)</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label-sm">Tip</label>
                    <select id="filterType" class="form-input" onchange="filterStorage()">
                        <option value="">Tümü</option>
                        <option value="kargo">Kargo Emaneti</option>
                        <option value="bagaj">Yolcu Bagajı</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label-sm">Sırala</label>
                    <select id="sortBy" class="form-input" onchange="filterStorage()">
                        <option value="hours_desc">Süre (Uzun→Kısa)</option>
                        <option value="hours_asc">Süre (Kısa→Uzun)</option>
                        <option value="fee_desc">Ücret (Yüksek→Düşük)</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <button class="btn-outline-secondary-sm w-100" style="height:36px;" onclick="refreshTimes()">
                        <i class="bi bi-arrow-clockwise me-1"></i> Yenile
                    </button>
                </div>
            </div>
        </div>

        <!-- ══ Emanet Tablosu ══ -->
        <div class="card">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <div class="section-label">Aktif Emanetler</div>
                    <div class="section-sub" id="storageCountLabel">Yükleniyor...</div>
                </div>
                <div class="d-flex gap-2">
                    <span class="status-badge" style="background:#fff8ec;color:#e08b00;font-size:.72rem;">
                        <i class="bi bi-exclamation-circle me-1"></i>Sarı: Ücretli süre
                    </span>
                    <span class="status-badge" style="background:#fff0f2;color:#c03060;font-size:.72rem;">
                        <i class="bi bi-exclamation-triangle me-1"></i>Kırmızı: 24h+ kritik
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="orders-table" id="storageTable">
                    <thead>
                        <tr>
                            <th>Takip / Kayıt No</th>
                            <th>Tip</th>
                            <th>Alıcı / Sahibi</th>
                            <th>Telefon</th>
                            <th>Raf / Konum</th>
                            <th>Giriş Zamanı</th>
                            <th>Süre</th>
                            <th>Emanet Ücreti</th>
                            <th>Durum</th>
                            <th style="width:80px;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody id="storageBody">
                        <!-- JS ile doldurulacak -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex align-items-center justify-content-between mt-3 flex-wrap gap-2">
                <div style="font-size:.78rem;color:var(--text-muted);" id="paginationInfo"></div>
                <div class="d-flex gap-1">
                    <button class="icon-btn-circle" disabled><i class="bi bi-chevron-left"
                            style="font-size:.75rem;"></i></button>
                    <button
                        style="width:30px;height:30px;border-radius:50%;border:none;background:var(--accent-blue);color:#fff;font-size:.78rem;font-weight:700;cursor:pointer;">1</button>
                    <button class="icon-btn-circle" style="font-size:.78rem;">2</button>
                    <button class="icon-btn-circle" style="font-size:.78rem;">3</button>
                    <button class="icon-btn-circle"><i class="bi bi-chevron-right"
                            style="font-size:.75rem;"></i></button>
                </div>
            </div>
        </div>

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
        padding: 7px 11px;
        font-size: .83rem;
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

    /* Satır renklendirme */
    tr.row-paid td:first-child {
        border-left: 3px solid #e08b00;
    }

    tr.row-critical td:first-child {
        border-left: 3px solid #c03060;
    }

    tr.row-paid {
        background: rgba(255, 248, 236, .45);
    }

    tr.row-critical {
        background: rgba(255, 240, 242, .45);
    }

    body.dark tr.row-paid {
        background: rgba(224, 139, 0, .08);
    }

    body.dark tr.row-critical {
        background: rgba(192, 48, 96, .08);
    }

    /* Süre göstergesi */
    .hour-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: .75rem;
        font-weight: 700;
    }

    .hour-free {
        background: #e7f9f0;
        color: #0e8045;
    }

    .hour-paid {
        background: #fff8ec;
        color: #e08b00;
    }

    .hour-critical {
        background: #fff0f2;
        color: #c03060;
    }
</style>

<script>
    /* ── Real Data from API ── */
    var now = Date.now();
    var storageData = [];

    function loadStorage() {
        fetch('api.php?action=storage.list', {
            method:'POST', headers:{'Content-Type':'application/json'}, body:'{}'
        })
        .then(r => r.json())
        .then(function(res) {
            if (!res.success) { showToast('Hata: '+(res.error||'?'), 'error'); return; }
            var kpi = res.kpi || {};
            document.getElementById('kpiTotal').textContent = kpi.total || 0;
            document.getElementById('kpiPaid').textContent = kpi.paid || 0;
            document.getElementById('kpiPending').textContent = '₺'+(parseFloat(kpi.pending_fee)||0).toFixed(2);
            document.getElementById('kpiDelivered').textContent = kpi.today_delivered || 0;
            now = Date.now();
            storageData = (res.records || []).map(function(r) {
                return {
                    id:         r.reference_code || String(r.storage_id),
                    storage_id: r.storage_id,
                    type:       r.type === 'baggage' ? 'bagaj' : 'kargo',
                    owner:      r.owner_name,
                    phone:      r.owner_phone,
                    location:   r.location,
                    entryTs:    new Date(r.checked_in_at).getTime(),
                    freeH:      parseFloat(r.free_hours)||4,
                    rateH:      parseFloat(r.rate_per_h)||2,
                    urgency:    r.urgency,
                    fee:        parseFloat(r.fee)||0,
                };
            });
            filterStorage();
        })
        .catch(function() { showToast('Bağlantı hatası!', 'error'); });
    }

    /* Her kayıt için hesaplamalar */
    function calcRow(r) {
        var totalH = (now - r.entryTs) / 3600000;
        var paidH = Math.max(0, totalH - r.freeH);
        var fee = paidH * r.rateH;
        var urgency = totalH >= 24 ? 'critical' : paidH > 0 ? 'paid' : 'free';
        return Object.assign({}, r, { totalH: totalH, paidH: paidH, fee: fee, urgency: urgency });
    }

    function formatHour(h) {
        if (h < 1) return Math.round(h * 60) + ' dk';
        return Math.floor(h) + ' sa ' + Math.round((h % 1) * 60) + ' dk';
    }

    function formatDate(ts) {
        var d = new Date(ts);
        return d.toLocaleDateString('tr-TR', { day: '2-digit', month: '2-digit', year: 'numeric' }) +
            ' ' + d.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
    }

    /* ── Tabloyu Render Et ── */
    function renderTable(data) {
        var body = document.getElementById('storageBody');
        if (!data.length) {
            body.innerHTML = '<tr><td colspan="10" class="text-center" style="padding:32px;color:var(--text-muted);font-size:.82rem;"><i class="bi bi-inbox" style="font-size:1.8rem;display:block;margin-bottom:8px;opacity:.4;"></i>Kayıt bulunamadı.</td></tr>';
            return;
        }

        body.innerHTML = data.map(function (r) {
            var c = calcRow(r);
            var urgCls = 'row-' + c.urgency;
            var hourCls = 'hour-' + c.urgency;
            var hourLbl = formatHour(c.totalH);
            var feeLbl = c.fee > 0 ? '<span style="font-weight:700;color:' + (c.urgency === 'critical' ? '#c03060' : '#e08b00') + ';">₺' + c.fee.toFixed(2) + '</span>' : '<span style="color:#0e8045;font-size:.78rem;">Ücretsiz</span>';

            var typeIcon = r.type === 'bagaj'
                ? '<span class="status-badge" style="background:#f3e5f5;color:#8e24aa;font-size:.7rem;"><i class="bi bi-luggage me-1"></i>Bagaj</span>'
                : '<span class="status-badge" style="background:#e8f1ff;color:#1b84ff;font-size:.7rem;"><i class="bi bi-box-seam me-1"></i>Kargo</span>';

            return '<tr class="' + urgCls + '" data-type="' + r.type + '" data-urgency="' + c.urgency + '">' +
                '<td><span style="font-family:monospace;font-size:.79rem;font-weight:600;color:var(--accent-blue);">' + r.id + '</span></td>' +
                '<td>' + typeIcon + '</td>' +
                '<td><div style="font-size:.82rem;font-weight:500;">' + r.owner + '</div></td>' +
                '<td style="font-size:.79rem;color:var(--text-muted);">' + r.phone + '</td>' +
                '<td><span style="font-size:.8rem;font-weight:700;font-family:monospace;">' + r.location + '</span></td>' +
                '<td style="font-size:.77rem;color:var(--text-muted);">' + formatDate(r.entryTs) + '</td>' +
                '<td><span class="hour-badge ' + hourCls + '">' + hourLbl + '</span></td>' +
                '<td>' + feeLbl + '</td>' +
                '<td><span class="status-badge" style="background:#fff8ec;color:#e08b00;font-size:.73rem;">Emanette</span></td>' +
                '<td>' +
                '<div class="d-flex gap-1">' +
                '<button class="icon-btn-circle" title="Teslim Et" onclick="quickDeliver(' + r.storage_id + ', \'' + r.id + '\')"><i class="bi bi-check2" style="font-size:.8rem;color:#0e8045;"></i></button>' +
                '<button class="icon-btn-circle" title="Detay"><i class="bi bi-eye" style="font-size:.8rem;"></i></button>' +
                '</div>' +
                '</td>' +
                '</tr>';
        }).join('');

        document.getElementById('storageCountLabel').textContent = data.length + ' kayıt';
    }

    /* ── Filtrele & Sırala ── */
    function filterStorage() {
        var search = document.getElementById('storageSearch').value.toLowerCase();
        var sure = document.getElementById('filterSure').value;
        var type = document.getElementById('filterType').value;
        var sortBy = document.getElementById('sortBy').value;

        var filtered = storageData.map(calcRow).filter(function (r) {
            var matchSearch = !search || r.id.toLowerCase().includes(search) || r.owner.toLowerCase().includes(search);
            var matchSure = !sure || r.urgency === sure.replace('paid', 'paid').replace('critical', 'critical').replace('free', 'free');
            var matchType = !type || r.type === type;
            return matchSearch && matchSure && matchType;
        });

        /* Sıralama */
        filtered.sort(function (a, b) {
            if (sortBy === 'hours_desc') return b.totalH - a.totalH;
            if (sortBy === 'hours_asc') return a.totalH - b.totalH;
            if (sortBy === 'fee_desc') return b.fee - a.fee;
            return 0;
        });

        renderTable(filtered);
    }

    /* ── Hızlı Teslim ── */
    function quickDeliver(id) {
        if (!confirm(id + ' emanetini teslim etmek istediğinize emin misiniz?')) return;
        storageData = storageData.filter(function (r) { return r.id !== id; });
        filterStorage();
        showToast('✓ ' + id + ' emanetten çıkarıldı.', 'success');
    }

    /* ── Zamanları Yenile ── */
    function refreshTimes() {
        loadStorage();
        showToast('Süreler yenileniyor...', 'info');
    }

    /* ── Toast ── */
    function showToast(msg, type) {
        var colors = { success: '#0e8045', info: '#1b84ff', error: '#c03060' };
        var t = document.createElement('div');
        t.style.cssText = 'position:fixed;bottom:24px;right:24px;padding:11px 18px;border-radius:8px;font-size:.82rem;font-weight:600;z-index:9999;box-shadow:0 4px 14px rgba(0,0,0,.18);color:#fff;background:' + (colors[type] || '#333') + ';transition:opacity .3s;';
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(function () { t.style.opacity = '0'; setTimeout(function () { t.remove(); }, 300); }, 3000);
    }

    /* ── İlk Yükleme ── */
    loadStorage();

    /* ── 60 saniyede bir otomatik yenile ── */
    setInterval(function () { loadStorage(); }, 60000);
</script>