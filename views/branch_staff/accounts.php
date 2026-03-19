<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Cari Hesaplar</div>
            <div class="breadcrumb">
                <a href="?page=dashboard">Ana Sayfa</a>
                <span class="sep">›</span>
                <span>Finans · Cari Müşteriler</span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="?page=accounts_create" class="btn-primary-sm d-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i> Yeni Cari
            </a>
            <button onclick="exportAccounts()" class="btn-outline-secondary-sm d-flex align-items-center gap-1">
                <i class="bi bi-download"></i> Dışa Aktar
            </button>
        </div>
    </div>

    <div style="padding:18px 26px 40px;">

        <!-- ══ KPI ══ -->
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="card" style="padding:14px 16px;">
                    <div class="card-sm-label">Toplam Cari</div>
                    <div class="stat-value" style="font-size:1.5rem;">—</div>\n                    <div style="font-size:.73rem;color:var(--text-muted);margin-top:2px;">Aktif hesap</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card" style="padding:14px 16px;">
                    <div class="card-sm-label">Toplam Alacak</div>
                    <div class="stat-value" style="font-size:1.5rem;color:#c03060;">₺18.640</div>
                    <div style="font-size:.73rem;color:var(--text-muted);margin-top:2px;">Tahsil edilmemiş</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card" style="padding:14px 16px;">
                    <div class="card-sm-label">Bu Ay Tahsilat</div>
                    <div class="stat-value" style="font-size:1.5rem;color:#0e8045;">₺9.200</div>
                    <div style="font-size:.73rem;color:var(--text-muted);margin-top:2px;">Şubat 2026</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card" style="padding:14px 16px;">
                    <div class="card-sm-label">Limit Aşan</div>
                    <div class="stat-value" style="font-size:1.5rem;color:#e08b00;">3</div>
                    <div style="font-size:.73rem;color:#e08b00;margin-top:2px;">Kredi limiti doldu</div>
                </div>
            </div>
        </div>

        <!-- ══ Filtre ══ -->
        <div class="card mb-3" style="padding:13px 20px;">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-5">
                    <label class="form-label-sm">Ara (Firma / Yetkili / Telefon)</label>
                    <div style="position:relative;">
                        <i class="bi bi-search"
                            style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#a3aab8;font-size:.82rem;"></i>
                        <input type="text" id="acSearch" oninput="filterAccounts()" class="form-input"
                            style="padding-left:32px;" placeholder="ABC Lojistik..." />
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label-sm">Hesap Durumu</label>
                    <select id="acStatus" class="form-input" onchange="filterAccounts()">
                        <option value="">Tümü</option>
                        <option value="ok">Normal</option>
                        <option value="warn">Uyarı (%80+)</option>
                        <option value="over">Limit Aşıldı</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label-sm">Tür</label>
                    <select id="acType" class="form-input" onchange="filterAccounts()">
                        <option value="">Tümü</option>
                        <option value="kurumsal">Kurumsal</option>
                        <option value="bireysel">Bireysel</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <button class="btn-outline-secondary-sm w-100" style="height:36px;font-size:.8rem;"
                        onclick="filterAccounts()">
                        <i class="bi bi-funnel me-1"></i> Filtrele
                    </button>
                </div>
            </div>
        </div>

        <!-- ══ Tablo ══ -->
        <div class="card">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <div class="section-label">Cari Hesap Listesi</div>
                    <div class="section-sub" id="acCountLabel">24 cari</div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Firma / Müşteri</th>
                            <th>Tür</th>
                            <th>Yetkili</th>
                            <th>Telefon</th>
                            <th>Toplam Borç</th>
                            <th>Kredi Limiti</th>
                            <th>Kullanım</th>
                            <th>Son İşlem</th>
                            <th>Durum</th>
                            <th style="width:80px;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody id="acBody"></tbody>
                </table>
            </div>
        </div>

    </div>

</main>

<!-- ── Cari Detay Yan Paneli ── -->
<div id="acDrawer"
    style="display:none;position:fixed;top:0;right:0;width:380px;height:100vh;background:var(--card-bg);box-shadow:-8px 0 32px rgba(0,0,0,.2);z-index:9990;overflow-y:auto;padding:24px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="section-label" id="drawer-title">Cari Detay</div>
        <button onclick="closeDrawer()"
            style="border:none;background:none;cursor:pointer;font-size:1.2rem;color:var(--text-muted);">&times;</button>
    </div>

    <div id="drawer-content"><!-- JS doldurur --></div>
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

    /* Limit Bar */
    .limit-bar {
        height: 5px;
        background: var(--border-color);
        border-radius: 3px;
        margin-top: 4px;
        overflow: hidden;
    }

    .limit-fill {
        height: 100%;
        border-radius: 3px;
    }
</style>

<script>
    var accounts = [];

    function loadAccounts() {
        fetch('api.php?action=accounts.list', {
            method:'POST', headers:{'Content-Type':'application/json'}, body:'{}'
        })
        .then(r => r.json())
        .then(function(res) {
            if (!res.success) { showToast('Hata: '+(res.error||'?'), 'error'); return; }
            var kpi = res.kpi || {};
            document.getElementById('kpiTotal').textContent   = kpi.total || 0;
            document.getElementById('kpiDebt').textContent    = '₺'+(parseFloat(kpi.total_debt)||0).toLocaleString('tr-TR',{minimumFractionDigits:0});
            document.getElementById('kpiMonthly').textContent = '₺'+(parseFloat(kpi.monthly_paid)||0).toLocaleString('tr-TR',{minimumFractionDigits:0});
            document.getElementById('kpiOver').textContent    = kpi.over_limit || 0;
            accounts = (res.accounts || []).map(function(a) {
                return {
                    id:      a.id,
                    name:    a.name,
                    type:    a.type === 'CORPORATE' ? 'kurumsal' : 'bireysel',
                    contact: a.contact || '—',
                    phone:   a.phone,
                    debt:    parseFloat(a.debt)||0,
                    limit:   parseFloat(a.limit)||1,
                    lastTx:  a.lastTx || '—',
                };
            });
            filterAccounts();
        })
        .catch(function() { showToast('Bağlantı hatası!', 'error'); });
    }

    function getStatus(debt, limit) {
        var ratio = debt / limit;
        if (debt > limit) return 'over';
        if (ratio >= 0.80) return 'warn';
        return 'ok';
    }

    function statusBadge(s) {
        var m = { ok: { bg: '#e7f9f0', col: '#0e8045', lbl: 'Normal' }, warn: { bg: '#fff8ec', col: '#e08b00', lbl: 'Uyarı' }, over: { bg: '#fff0f2', col: '#c03060', lbl: 'Limit Aşıldı' } };
        var d = m[s];
        return '<span class="status-badge" style="background:' + d.bg + ';color:' + d.col + ';font-size:.72rem;">' + d.lbl + '</span>';
    }

    function limitBar(debt, limit) {
        var pct = Math.min(100, Math.round(debt / limit * 100));
        var color = pct >= 100 ? '#c03060' : pct >= 80 ? '#e08b00' : '#0e8045';
        return '<div style="font-size:.73rem;color:var(--text-muted);">' + pct + '%</div>' +
            '<div class="limit-bar"><div class="limit-fill" style="width:' + pct + '%;background:' + color + ';"></div></div>';
    }

    function renderAccounts(data) {
        document.getElementById('acCountLabel').textContent = data.length + ' cari';
        var body = document.getElementById('acBody');
        if (!data.length) {
            body.innerHTML = '<tr><td colspan="10" class="text-center" style="padding:32px;color:var(--text-muted);font-size:.82rem;">Kayıt bulunamadı.</td></tr>';
            return;
        }
        body.innerHTML = data.map(function (a) {
            var status = getStatus(a.debt, a.limit);
            var typeLbl = a.type === 'kurumsal'
                ? '<span class="status-badge" style="background:#e8f1ff;color:#1b84ff;font-size:.7rem;"><i class="bi bi-building me-1"></i>Kurumsal</span>'
                : '<span class="status-badge" style="background:#f3e5f5;color:#8e24aa;font-size:.7rem;"><i class="bi bi-person me-1"></i>Bireysel</span>';
            var debtColor = a.debt > a.limit ? '#c03060' : a.debt / a.limit >= 0.8 ? '#e08b00' : 'var(--text-dark)';

            return '<tr>' +
                '<td><div style="font-size:.83rem;font-weight:600;">' + a.name + '</div></td>' +
                '<td>' + typeLbl + '</td>' +
                '<td style="font-size:.8rem;">' + a.contact + '</td>' +
                '<td style="font-size:.78rem;color:var(--text-muted);">' + a.phone + '</td>' +
                '<td style="font-weight:700;font-size:.84rem;color:' + debtColor + ';">₺' + a.debt.toLocaleString('tr-TR') + '</td>' +
                '<td style="font-size:.82rem;">₺' + a.limit.toLocaleString('tr-TR') + '</td>' +
                '<td style="min-width:90px;">' + limitBar(a.debt, a.limit) + '</td>' +
                '<td style="font-size:.77rem;color:var(--text-muted);">' + a.lastTx + '</td>' +
                '<td>' + statusBadge(status) + '</td>' +
                '<td><div class="d-flex gap-1">' +
                '<button class="icon-btn-circle" title="Detay" onclick="openDrawer(' + a.id + ')"><i class="bi bi-eye" style="font-size:.8rem;"></i></button>' +
                '<button class="icon-btn-circle" title="Tahsilat Ekle"><i class="bi bi-cash-coin" style="font-size:.8rem;color:#0e8045;"></i></button>' +
                '</div></td>' +
                '</tr>';
        }).join('');
    }

    function filterAccounts() {
        var search = document.getElementById('acSearch').value.toLowerCase();
        var status = document.getElementById('acStatus').value;
        var type = document.getElementById('acType').value;

        renderAccounts(accounts.filter(function (a) {
            var s = getStatus(a.debt, a.limit);
            return (!search || a.name.toLowerCase().includes(search) || a.contact.toLowerCase().includes(search) || a.phone.includes(search)) &&
                (!status || s === status) &&
                (!type || a.type === type);
        }));
    }

    function openDrawer(id) {
        var a = accounts.find(function (x) { return x.id === id; });
        if (!a) return;
        var used = Math.round(a.debt / a.limit * 100);
        var color = used >= 100 ? '#c03060' : used >= 80 ? '#e08b00' : '#0e8045';

        document.getElementById('drawer-title').textContent = a.name;
        document.getElementById('drawer-content').innerHTML =
            '<div class="d-flex flex-column gap-3">' +
            '<div class="d-flex gap-3">' +
            '<div style="flex:1;background:var(--body-bg);border-radius:8px;padding:12px;text-align:center;">' +
            '<div style="font-size:.7rem;color:var(--text-muted);margin-bottom:4px;">BORÇ</div>' +
            '<div style="font-weight:800;font-size:1.1rem;color:#c03060;">₺' + a.debt.toLocaleString('tr-TR') + '</div>' +
            '</div>' +
            '<div style="flex:1;background:var(--body-bg);border-radius:8px;padding:12px;text-align:center;">' +
            '<div style="font-size:.7rem;color:var(--text-muted);margin-bottom:4px;">LİMİT</div>' +
            '<div style="font-weight:800;font-size:1.1rem;">₺' + a.limit.toLocaleString('tr-TR') + '</div>' +
            '</div>' +
            '</div>' +
            '<div>' +
            '<div style="font-size:.72rem;font-weight:600;color:var(--text-muted);margin-bottom:6px;">KREDİ KULLANIMI — %' + used + '</div>' +
            '<div style="height:8px;background:var(--border-color);border-radius:4px;overflow:hidden;">' +
            '<div style="height:8px;width:' + Math.min(100, used) + '%;background:' + color + ';border-radius:4px;transition:width .4s;"></div>' +
            '</div>' +
            '</div>' +
            '<hr style="border-color:var(--border-color);" />' +
            '<div style="font-size:.82rem;"><strong>Yetkili:</strong> ' + a.contact + '</div>' +
            '<div style="font-size:.82rem;"><strong>Telefon:</strong> ' + a.phone + '</div>' +
            '<div style="font-size:.82rem;"><strong>Son İşlem:</strong> ' + a.lastTx + '</div>' +
            '<hr style="border-color:var(--border-color);" />' +
            '<div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);margin-bottom:8px;">Son Hareketler</div>' +
            '<div style="font-size:.8rem;color:var(--text-muted);text-align:center;padding:12px 0;opacity:.6;">Hareket verisi yükleniyor...</div>' +
            '<div class="d-flex flex-column gap-2 mt-2">' +
            '<button class="btn-primary-sm" style="height:38px;">₺ Tahsilat Ekle</button>' +
            '<a href="?page=shipment&account=' + id + '" class="btn-outline-secondary-sm d-flex align-items-center justify-content-center gap-1" style="height:36px;font-size:.82rem;"><i class="bi bi-box-seam"></i> Sevkiyatlar</a>' +
            '</div>' +
            '</div>';

        document.getElementById('acDrawer').style.display = 'block';
    }

    function closeDrawer() { document.getElementById('acDrawer').style.display = 'none'; }

    function exportAccounts() { showToast('Dışa aktarılıyor...', 'info'); }

    function showToast(msg, type) {
        var colors = { success: '#0e8045', info: '#1b84ff', error: '#c03060' };
        var t = document.createElement('div');
        t.style.cssText = 'position:fixed;bottom:24px;right:24px;padding:11px 18px;border-radius:8px;font-size:.82rem;font-weight:600;z-index:9999;box-shadow:0 4px 14px rgba(0,0,0,.18);color:#fff;background:' + (colors[type] || '#333') + ';transition:opacity .3s;';
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(function () { t.style.opacity = '0'; setTimeout(function () { t.remove(); }, 300); }, 3000);
    }

    loadAccounts();
</script>