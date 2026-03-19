<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Seferler</div>
            <div class="breadcrumb">
                <a href="?page=dashboard">Ana Sayfa</a>
                <span class="sep">›</span>
                <span>Sefer Yönetimi</span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
           
            <a href="?page=dispatch" class="btn-outline-secondary-sm d-flex align-items-center gap-1">
                <i class="bi bi-send"></i> Kargo Sevk
            </a>
        </div>
    </div>

    <div style="padding:18px 26px 40px;">

        <!-- ══ ROW 1 — KPI Kartları ══ -->
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="card" style="padding:14px 16px;">
                    <div class="card-sm-label">Bugünkü Seferler</div>
                    <div class="stat-value" style="font-size:1.5rem;">8</div>
                    <div style="font-size:.73rem;color:var(--text-muted);margin-top:2px;">Toplam planlı</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card" style="padding:14px 16px;">
                    <div class="card-sm-label">Aktif / Yolda</div>
                    <div class="stat-value" style="font-size:1.5rem;color:#1b84ff;">3</div>
                    <div style="font-size:.73rem;color:#1b84ff;margin-top:2px;">Sefer devam ediyor</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card" style="padding:14px 16px;">
                    <div class="card-sm-label">Toplam Kargo</div>
                    <div class="stat-value" style="font-size:1.5rem;color:#0e8045;">67</div>
                    <div style="font-size:.73rem;color:var(--text-muted);margin-top:2px;">Bu gün sevk edilen</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card" style="padding:14px 16px;">
                    <div class="card-sm-label">Şoföre Ödenen Net</div>
                    <div class="stat-value" style="font-size:1.5rem;color:#e08b00;">₺3.650</div>
                    <div style="font-size:.73rem;color:var(--text-muted);margin-top:2px;">Komisyon düşüldü</div>
                </div>
            </div>
        </div>

        <!-- ══ Filtre ══ -->
        <div class="card mb-3" style="padding:14px 20px;">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label-sm">Ara (Plaka / Firma / Güzergah)</label>
                    <div style="position:relative;">
                        <i class="bi bi-search"
                            style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#a3aab8;font-size:.82rem;"></i>
                        <input type="text" id="voyageSearch" class="form-input" style="padding-left:32px;"
                            placeholder="34 ABC 001 veya Metro..." oninput="filterVoyages()" />
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label-sm">Durum</label>
                    <select id="filterStatus" class="form-input" onchange="filterVoyages()">
                        <option value="">Tümü</option>
                        <option value="bekliyor">Bekliyor</option>
                        <option value="yolda">Yolda</option>
                        <option value="tamamlandi">Tamamlandı</option>
                        <option value="iptal">İptal</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label-sm">Tarih</label>
                    <input type="date" id="filterDate" class="form-input" value="<?= date('Y-m-d') ?>"
                        onchange="filterVoyages()" />
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label-sm">Firma</label>
                    <select id="filterCompany" class="form-input" onchange="filterVoyages()">
                        <option value="">Tümü</option>
                        <option value="metro">Metro Turizm</option>
                        <option value="pamukkale">Pamukkale</option>
                        <option value="uludag">Uludağ Turizm</option>
                        <option value="kamil">Kamil Koç</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <button class="btn-outline-secondary-sm w-100" style="height:36px;" onclick="filterVoyages()">
                        <i class="bi bi-funnel me-1"></i> Filtrele
                    </button>
                </div>
            </div>
        </div>

        <!-- ══ Sefer Tablosu ══ -->
        <div class="card">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <div class="section-label">Sefer Listesi</div>
                    <div class="section-sub" id="voyageCountLabel">8 sefer</div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Plaka</th>
                            <th>Firma</th>
                            <th>Güzergah</th>
                            <th>Şoför</th>
                            <th>Kalkış Saati</th>
                            <th>Kargo</th>
                            <th>Brüt Tutar</th>
                            <th>Komisyon</th>
                            <th>Net Ödeme</th>
                            <th>Durum</th>
                            <th style="width:80px;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody id="voyageBody"></tbody>
                </table>
            </div>
        </div>

    </div><!-- /body -->
</main>

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

    .v-bekliyor {
        background: #f0f6ff;
        color: #1b84ff;
    }

    .v-yolda {
        background: #e7f9f0;
        color: #0e8045;
    }

    .v-tamamlandi {
        background: #e9ecef;
        color: #5a6272;
    }

    .v-iptal {
        background: #fff0f2;
        color: #c03060;
    }
</style>

<script>
    var voyages = [
        { plate: '34 ABC 001', company: 'metro', companyName: 'Metro Turizm', route: 'İstanbul → Ankara', driver: 'Ahmet Kurt', time: '06:00', cargo: 14, gross: 1190, commRate: 15, status: 'tamamlandi' },
        { plate: '35 XY 002', company: 'pamukkale', companyName: 'Pamukkale', route: 'İstanbul → İzmir', driver: 'Mehmet Acar', time: '08:30', cargo: 9, gross: 960, commRate: 15, status: 'tamamlandi' },
        { plate: '06 KA 777', company: 'uludag', companyName: 'Uludağ Turizm', route: 'İstanbul → Bursa', driver: 'Hasan Yıldız', time: '10:00', cargo: 5, gross: 440, commRate: 12, status: 'yolda' },
        { plate: '34 MT 333', company: 'metro', companyName: 'Metro Turizm', route: 'İstanbul → Sivas → Erzurum', driver: 'Tarık Şahin', time: '11:30', cargo: 17, gross: 1530, commRate: 15, status: 'yolda' },
        { plate: '01 AD 055', company: 'kamil', companyName: 'Kamil Koç', route: 'İstanbul → Adana', driver: 'Barış Ak', time: '13:00', cargo: 8, gross: 760, commRate: 13, status: 'yolda' },
        { plate: '35 PK 100', company: 'pamukkale', companyName: 'Pamukkale', route: 'İstanbul → Denizli → Muğla', driver: 'Caner Demir', time: '15:00', cargo: 6, gross: 540, commRate: 15, status: 'bekliyor' },
        { plate: '34 MTT 44', company: 'metro', companyName: 'Metro Turizm', route: 'İstanbul → Konya → Adana', driver: 'Osman Güler', time: '17:30', cargo: 5, gross: 475, commRate: 15, status: 'bekliyor' },
        { plate: '34 KK 987', company: 'kamil', companyName: 'Kamil Koç', route: 'İstanbul → Gaziantep', driver: 'Volkan Ateş', time: '20:00', cargo: 3, gross: 285, commRate: 13, status: 'bekliyor' },
    ];

    function statusBadge(s) {
        var labels = { bekliyor: 'Bekliyor', yolda: 'Yolda', tamamlandi: 'Tamamlandı', iptal: 'İptal' };
        return '<span class="status-badge v-' + s + '">' + (labels[s] || s) + '</span>';
    }

    function renderVoyages(data) {
        var body = document.getElementById('voyageBody');
        document.getElementById('voyageCountLabel').textContent = data.length + ' sefer';

        if (!data.length) {
            body.innerHTML = '<tr><td colspan="11" class="text-center" style="padding:32px;color:var(--text-muted);font-size:.82rem;"><i class="bi bi-bus-front" style="font-size:1.8rem;display:block;margin-bottom:8px;opacity:.4;"></i>Sefer bulunamadı.</td></tr>';
            return;
        }

        body.innerHTML = data.map(function (v) {
            var commission = (v.gross * v.commRate / 100).toFixed(2);
            var net = (v.gross * (1 - v.commRate / 100)).toFixed(2);

            var actionBtns = v.status === 'bekliyor'
                ? '<button class="icon-btn-circle" title="Sevke Başla" onclick="startVoyage(this)"><i class="bi bi-send" style="font-size:.8rem;color:#1b84ff;"></i></button>'
                : '<button class="icon-btn-circle" title="Manifesto"><i class="bi bi-card-list" style="font-size:.8rem;"></i></button>';

            return '<tr>' +
                '<td><span style="font-family:monospace;font-size:.82rem;font-weight:700;color:var(--text-dark);">' + v.plate + '</span></td>' +
                '<td style="font-size:.81rem;">' + v.companyName + '</td>' +
                '<td><span style="font-size:.79rem;color:var(--text-muted);">' + v.route + '</span></td>' +
                '<td><div style="font-size:.81rem;">' + v.driver + '</div></td>' +
                '<td><span style="font-size:.82rem;font-weight:600;">' + v.time + '</span></td>' +
                '<td><span style="font-size:.82rem;font-weight:600;color:#1b84ff;">' + v.cargo + '</span></td>' +
                '<td style="font-size:.83rem;font-weight:600;">₺' + v.gross.toFixed(2) + '</td>' +
                '<td style="font-size:.82rem;color:#c03060;font-weight:600;">-₺' + commission + ' <span style="font-weight:400;font-size:.72rem;color:var(--text-muted);">(%' + v.commRate + ')</span></td>' +
                '<td style="font-size:.83rem;font-weight:700;color:#0e8045;">₺' + net + '</td>' +
                '<td>' + statusBadge(v.status) + '</td>' +
                '<td><div class="d-flex gap-1">' +
                actionBtns +
                '<button class="icon-btn-circle" title="Detay"><i class="bi bi-eye" style="font-size:.8rem;"></i></button>' +
                '</div></td>' +
                '</tr>';
        }).join('');
    }

    function filterVoyages() {
        var search = document.getElementById('voyageSearch').value.toLowerCase();
        var status = document.getElementById('filterStatus').value;
        var company = document.getElementById('filterCompany').value;

        var filtered = voyages.filter(function (v) {
            var matchSearch = !search || v.plate.toLowerCase().includes(search) || v.companyName.toLowerCase().includes(search) || v.route.toLowerCase().includes(search);
            var matchStatus = !status || v.status === status;
            var matchCompany = !company || v.company === company;
            return matchSearch && matchStatus && matchCompany;
        });
        renderVoyages(filtered);
    }

    function startVoyage(btn) {
        var row = btn.closest('tr');
        var plate = row.cells[0].textContent.trim();
        if (!confirm(plate + ' seferini yola çıkarmak istiyor musunuz?')) return;
        /* TODO: AJAX status update */
        showToast('✓ ' + plate + ' seferi yola çıktı.', 'success');
        row.cells[9].innerHTML = '<span class="status-badge v-yolda">Yolda</span>';
        btn.innerHTML = '<i class="bi bi-card-list" style="font-size:.8rem;"></i>';
        btn.title = 'Manifesto';
    }

    function showToast(msg, type) {
        var colors = { success: '#0e8045', info: '#1b84ff', error: '#c03060' };
        var t = document.createElement('div');
        t.style.cssText = 'position:fixed;bottom:24px;right:24px;padding:11px 18px;border-radius:8px;font-size:.82rem;font-weight:600;z-index:9999;box-shadow:0 4px 14px rgba(0,0,0,.18);color:#fff;background:' + (colors[type] || '#333') + ';transition:opacity .3s;';
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(function () { t.style.opacity = '0'; setTimeout(function () { t.remove(); }, 300); }, 3000);
    }

    renderVoyages(voyages);
</script>