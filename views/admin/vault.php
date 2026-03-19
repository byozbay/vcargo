<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Kasa Yönetimi</div>
            <div class="breadcrumb">
                <a href="?page=dashboard">Ana Sayfa</a>
                <span class="sep">›</span>
                <span>Finans · Kasa</span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button onclick="openExpenseModal()" class="btn-outline-secondary-sm d-flex align-items-center gap-1">
                <i class="bi bi-dash-circle"></i> Gider Ekle
            </button>
            <button onclick="window.print()" class="btn-outline-secondary-sm d-flex align-items-center gap-1">
                <i class="bi bi-printer"></i> Rapor Yazdır
            </button>
        </div>
    </div>

    <div style="padding:18px 26px 40px;">

        <!-- ── Tarih Filtresi ── -->
        <div class="card mb-3" style="padding:12px 20px;">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-2">
                    <label class="form-label-sm">Başlangıç</label>
                    <input type="date" id="dateFrom" class="form-input" value="<?= date('Y-m-d') ?>" />
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label-sm">Bitiş</label>
                    <input type="date" id="dateTo" class="form-input" value="<?= date('Y-m-d') ?>" />
                </div>
                <div class="col-6 col-md-2">
                    <button class="btn-primary-sm w-100" style="height:36px;font-size:.8rem;" onclick="filterVault()">
                        <i class="bi bi-funnel me-1"></i>Filtrele
                    </button>
                </div>
                <div class="col-auto ms-md-auto d-flex gap-2">
                    <button class="btn-outline-secondary-sm" style="height:36px;font-size:.78rem;"
                        onclick="setRange('today')">Bugün</button>
                    <button class="btn-outline-secondary-sm" style="height:36px;font-size:.78rem;"
                        onclick="setRange('week')">Bu Hafta</button>
                    <button class="btn-outline-secondary-sm" style="height:36px;font-size:.78rem;"
                        onclick="setRange('month')">Bu Ay</button>
                </div>
            </div>
        </div>

        <!-- ══ KPI + Grafik ══ -->
        <div class="row g-3 mb-3">

            <!-- KPI 4'lü sütun -->
            <div class="col-12 col-md-8">
                <div class="row g-3 h-100">
                    <div class="col-6">
                        <div class="card" style="padding:16px 18px;height:100%;">
                            <div class="card-sm-label">Günlük Gelir</div>
                            <div class="stat-value" style="font-size:1.6rem;color:#0e8045;">₺4.830</div>
                            <div style="margin-top:10px;font-size:.78rem;color:var(--text-muted);">
                                <div class="d-flex justify-content-between mb-1"><span>Nakit</span><span
                                        style="font-weight:600;color:#0e8045;">₺3.100</span></div>
                                <div class="d-flex justify-content-between"><span>Kredi Kartı</span><span
                                        style="font-weight:600;color:#1b84ff;">₺1.730</span></div>
                            </div>
                            <div style="margin-top:8px;height:4px;background:var(--border-color);border-radius:2px;">
                                <div
                                    style="height:4px;width:64%;background:#0e8045;border-radius:2px;display:inline-block;">
                                </div>
                                <div
                                    style="height:4px;width:36%;background:#1b84ff;border-radius:2px;display:inline-block;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card" style="padding:16px 18px;height:100%;">
                            <div class="card-sm-label">Günlük Gider</div>
                            <div class="stat-value" style="font-size:1.6rem;color:#c03060;">₺1.650</div>
                            <div style="margin-top:10px;font-size:.78rem;color:var(--text-muted);">
                                <div class="d-flex justify-content-between mb-1"><span>Otobüs Öd.</span><span
                                        style="font-weight:600;color:#c03060;">₺1.400</span></div>
                                <div class="d-flex justify-content-between"><span>Genel Gider</span><span
                                        style="font-weight:600;color:#e08b00;">₺250</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card" style="padding:16px 18px;height:100%;border:2px solid var(--accent-blue);">
                            <div class="card-sm-label">Net Kasa</div>
                            <div class="stat-value" style="font-size:1.6rem;color:var(--accent-blue);">₺3.180</div>
                            <div style="font-size:.73rem;color:var(--text-muted);margin-top:6px;">Gelir − Gider</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card" style="padding:16px 18px;height:100%;">
                            <div class="card-sm-label">İşlem Sayısı</div>
                            <div class="stat-value" style="font-size:1.6rem;">38</div>
                            <div style="font-size:.73rem;color:var(--text-muted);margin-top:6px;">28 gelir · 10 gider
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mini donut grafik -->
            <div class="col-12 col-md-4">
                <div class="card d-flex flex-column align-items-center justify-content-center"
                    style="padding:20px;height:100%;min-height:200px;">
                    <div class="section-label mb-3" style="align-self:flex-start;">Tahsilat Dağılımı</div>
                    <canvas id="vaultDonut" width="160" height="160"></canvas>
                    <div class="d-flex gap-3 mt-3" style="font-size:.77rem;">
                        <span><span
                                style="display:inline-block;width:10px;height:10px;background:#0e8045;border-radius:2px;margin-right:4px;"></span>Nakit
                            64%</span>
                        <span><span
                                style="display:inline-block;width:10px;height:10px;background:#1b84ff;border-radius:2px;margin-right:4px;"></span>Kart
                            36%</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- ══ Hareket Tablosu ══ -->
        <div class="card">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <div class="section-label">Kasa Hareketleri</div>
                    <div class="section-sub">Bugüne ait tüm giriş/çıkışlar</div>
                </div>
                <div class="d-flex gap-2">
                    <select id="txType" class="form-input" style="width:auto;font-size:.8rem;" onchange="filterTx()">
                        <option value="">Tümü</option>
                        <option value="IN">Gelir</option>
                        <option value="OUT">Gider</option>
                    </select>
                    <select id="txMethod" class="form-input" style="width:auto;font-size:.8rem;" onchange="filterTx()">
                        <option value="">Tüm Yöntemler</option>
                        <option value="CASH">Nakit</option>
                        <option value="CARD">Kredi Kartı</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Saat</th>
                            <th>Açıklama</th>
                            <th>Tip</th>
                            <th>Yöntem</th>
                            <th>Tutar</th>
                            <th>Bakiye</th>
                        </tr>
                    </thead>
                    <tbody id="txBody"></tbody>
                </table>
            </div>
        </div>

    </div>

</main>

<!-- ── Gider Modal ── -->
<div id="expenseModal"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9998;align-items:center;justify-content:center;">
    <div
        style="background:var(--card-bg);border-radius:12px;padding:24px;width:420px;max-width:95vw;box-shadow:0 16px 48px rgba(0,0,0,.3);">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="section-label">Gider Ekle</div>
            <button onclick="closeExpenseModal()"
                style="border:none;background:none;cursor:pointer;font-size:1.2rem;color:var(--text-muted);">&times;</button>
        </div>
        <div class="d-flex flex-column gap-3">
            <div>
                <label class="form-label-sm">Gider Türü</label>
                <select class="form-input">
                    <option>Otobüse Ödeme</option>
                    <option>Kira / Fatura</option>
                    <option>Personel Avansı</option>
                    <option>Kırtasiye / Malzeme</option>
                    <option>Diğer</option>
                </select>
            </div>
            <div>
                <label class="form-label-sm">Tutar (₺)</label>
                <input type="number" class="form-input" placeholder="0.00" min="0" step="0.01" />
            </div>
            <div>
                <label class="form-label-sm">Ödeme Yöntemi</label>
                <div class="d-flex gap-2">
                    <label class="method-btn active" id="exp-cash" onclick="selExpMethod('cash',this)"><i
                            class="bi bi-cash"></i> Nakit</label>
                    <label class="method-btn" id="exp-card" onclick="selExpMethod('card',this)"><i
                            class="bi bi-credit-card"></i> Kart</label>
                </div>
            </div>
            <div>
                <label class="form-label-sm">Açıklama</label>
                <input type="text" class="form-input" placeholder="Kısa açıklama..." />
            </div>
            <button onclick="saveExpense()" class="btn-primary-sm w-100" style="height:42px;">
                <i class="bi bi-check2 me-1"></i> Kaydet
            </button>
        </div>
    </div>
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

    .method-btn {
        flex: 1;
        text-align: center;
        padding: 7px 8px;
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
        gap: 5px;
    }

    .method-btn.active {
        border-color: var(--accent-blue);
        background: #e8f1ff;
        color: var(--accent-blue);
    }

    body.dark .method-btn.active {
        background: rgba(27, 132, 255, .15);
    }
</style>

<script>
    /* ── Mock işlemler ── */
    var txData = [
        { time: '08:12', desc: 'Kargo Ücreti – TRK-240224-001', type: 'IN', method: 'CASH', amount: 65.00 },
        { time: '08:35', desc: 'Kargo Ücreti – TRK-240224-002', type: 'IN', method: 'CARD', amount: 90.00 },
        { time: '09:10', desc: 'Kargo Ücreti – TRK-240224-003', type: 'IN', method: 'CASH', amount: 55.00 },
        { time: '09:45', desc: 'Otobüse Ödeme – 34 ABC 001 (Metro)', type: 'OUT', method: 'CASH', amount: 820.00 },
        { time: '10:30', desc: 'Kargo Ücreti – TRK-240224-004', type: 'IN', method: 'CARD', amount: 110.00 },
        { time: '10:55', desc: 'Emanet Ücreti – TRK-240224-002', type: 'IN', method: 'CASH', amount: 44.00 },
        { time: '11:20', desc: 'Kargo Ücreti – TRK-240224-005', type: 'IN', method: 'CASH', amount: 78.00 },
        { time: '12:00', desc: 'Kargo Ücreti – TRK-240224-006', type: 'IN', method: 'CARD', amount: 95.00 },
        { time: '12:40', desc: 'Kırtasiye Gideri', type: 'OUT', method: 'CASH', amount: 75.00 },
        { time: '13:15', desc: 'Kargo Ücreti – TRK-240224-007', type: 'IN', method: 'CASH', amount: 120.00 },
    ];

    function renderTx(data) {
        var balance = 2000; /* Açılış bakiyesi */
        var rows = data.map(function (tx) {
            balance += (tx.type === 'IN' ? tx.amount : -tx.amount);
            var typeHtml = tx.type === 'IN'
                ? '<span class="status-badge" style="background:#e7f9f0;color:#0e8045;font-size:.72rem;"><i class="bi bi-arrow-down-left me-1"></i>Gelir</span>'
                : '<span class="status-badge" style="background:#fff0f2;color:#c03060;font-size:.72rem;"><i class="bi bi-arrow-up-right me-1"></i>Gider</span>';
            var methodHtml = tx.method === 'CASH'
                ? '<span class="status-badge" style="background:#fff8ec;color:#e08b00;font-size:.72rem;"><i class="bi bi-cash me-1"></i>Nakit</span>'
                : '<span class="status-badge" style="background:#e8f1ff;color:#1b84ff;font-size:.72rem;"><i class="bi bi-credit-card me-1"></i>Kart</span>';
            var amtColor = tx.type === 'IN' ? '#0e8045' : '#c03060';
            var amtSign = tx.type === 'IN' ? '+' : '−';

            return '<tr>' +
                '<td style="font-size:.78rem;color:var(--text-muted);">' + tx.time + '</td>' +
                '<td style="font-size:.82rem;">' + tx.desc + '</td>' +
                '<td>' + typeHtml + '</td>' +
                '<td>' + methodHtml + '</td>' +
                '<td style="font-weight:700;font-size:.85rem;color:' + amtColor + ';">' + amtSign + '₺' + tx.amount.toFixed(2) + '</td>' +
                '<td style="font-size:.82rem;font-weight:600;font-family:monospace;">₺' + balance.toFixed(2) + '</td>' +
                '</tr>';
        });
        document.getElementById('txBody').innerHTML = rows.join('') ||
            '<tr><td colspan="6" class="text-center" style="padding:28px;color:var(--text-muted);font-size:.82rem;">Kayıt bulunamadı.</td></tr>';
    }

    function filterTx() {
        var type = document.getElementById('txType').value;
        var method = document.getElementById('txMethod').value;
        renderTx(txData.filter(function (tx) {
            return (!type || tx.type === type) && (!method || tx.method === method);
        }));
    }

    function filterVault() { filterTx(); showToast('Filtre uygulandı.', 'info'); }
    function setRange(r) { showToast('Tarih aralığı: ' + r, 'info'); }

    /* ── Gider Modal ── */
    function openExpenseModal() { document.getElementById('expenseModal').style.display = 'flex'; }
    function closeExpenseModal() { document.getElementById('expenseModal').style.display = 'none'; }
    function saveExpense() { closeExpenseModal(); showToast('Gider kaydedildi.', 'success'); }
    function selExpMethod(m, el) {
        document.querySelectorAll('#expenseModal .method-btn').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
    }

    /* ── Donut Grafik ── */
    window.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('vaultDonut').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Nakit', 'Kredi Kartı'],
                datasets: [{ data: [64, 36], backgroundColor: ['#0e8045', '#1b84ff'], borderWidth: 0, hoverOffset: 4 }]
            },
            options: {
                cutout: '72%',
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: function (c) { return c.label + ': %' + c.raw; } } } }
            }
        });
    });

    /* ── Toast ── */
    function showToast(msg, type) {
        var colors = { success: '#0e8045', info: '#1b84ff', error: '#c03060' };
        var t = document.createElement('div');
        t.style.cssText = 'position:fixed;bottom:24px;right:24px;padding:11px 18px;border-radius:8px;font-size:.82rem;font-weight:600;z-index:9999;box-shadow:0 4px 14px rgba(0,0,0,.18);color:#fff;background:' + (colors[type] || '#333') + ';transition:opacity .3s;';
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(function () { t.style.opacity = '0'; setTimeout(function () { t.remove(); }, 300); }, 3000);
    }

    renderTx(txData);
</script>