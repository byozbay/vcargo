<?php
/* ── Vault — Real DB KPIs ── */
require_once __DIR__ . '/../../core/autoload.php';
$tx  = new TransactionModel();
$today = date('Y-m-d');
$summary = $tx->getDailySummary($_SESSION['branch_id'] ?? 0, $today);
$totalIn  = (float) ($summary['total_in']  ?? 0);
$totalOut = (float) ($summary['total_out'] ?? 0);
$cashIn   = (float) ($summary['cash_in']   ?? 0);
$cardIn   = (float) ($summary['card_in']   ?? 0);
$net      = $totalIn - $totalOut;
$txCount  = (int)   ($summary['tx_count']  ?? 0);
$inCount  = (int)   ($summary['in_count']  ?? 0);
$outCount = (int)   ($summary['out_count'] ?? 0);
$cashPct  = $totalIn > 0 ? round($cashIn / $totalIn * 100) : 0;
$cardPct  = 100 - $cashPct;
?>
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
            <button onclick="openCloseModal()" class="btn-outline-secondary-sm d-flex align-items-center gap-1" style="border-color:#e08b00;color:#e08b00;">
                <i class="bi bi-lock"></i> Günü Kapat
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
                    <input type="date" id="dateFrom" class="form-input" value="<?= $today ?>" />
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label-sm">Bitiş</label>
                    <input type="date" id="dateTo" class="form-input" value="<?= $today ?>" />
                </div>
                <div class="col-6 col-md-2">
                    <button class="btn-primary-sm w-100" style="height:36px;font-size:.8rem;" onclick="filterVault()">
                        <i class="bi bi-funnel me-1"></i>Filtrele
                    </button>
                </div>
                <div class="col-auto ms-md-auto d-flex gap-2">
                    <button class="btn-outline-secondary-sm" style="height:36px;font-size:.78rem;" onclick="setRange('today')">Bugün</button>
                    <button class="btn-outline-secondary-sm" style="height:36px;font-size:.78rem;" onclick="setRange('week')">Bu Hafta</button>
                    <button class="btn-outline-secondary-sm" style="height:36px;font-size:.78rem;" onclick="setRange('month')">Bu Ay</button>
                </div>
            </div>
        </div>

        <!-- ══ KPI + Grafik ══ -->
        <div class="row g-3 mb-3">

            <!-- KPI 4'lü -->
            <div class="col-12 col-md-8">
                <div class="row g-3 h-100">
                    <div class="col-6">
                        <div class="card" style="padding:16px 18px;height:100%;">
                            <div class="card-sm-label">Günlük Gelir</div>
                            <div class="stat-value" style="font-size:1.6rem;color:#0e8045;">₺<?= number_format($totalIn, 2, ',', '.') ?></div>
                            <div style="margin-top:10px;font-size:.78rem;color:var(--text-muted);">
                                <div class="d-flex justify-content-between mb-1"><span>Nakit</span><span style="font-weight:600;color:#0e8045;">₺<?= number_format($cashIn, 2, ',', '.') ?></span></div>
                                <div class="d-flex justify-content-between"><span>Kredi Kartı</span><span style="font-weight:600;color:#1b84ff;">₺<?= number_format($cardIn, 2, ',', '.') ?></span></div>
                            </div>
                            <div style="margin-top:8px;height:4px;background:var(--border-color);border-radius:2px;">
                                <div style="height:4px;width:<?= $cashPct ?>%;background:#0e8045;border-radius:2px;display:inline-block;"></div>
                                <div style="height:4px;width:<?= $cardPct ?>%;background:#1b84ff;border-radius:2px;display:inline-block;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card" style="padding:16px 18px;height:100%;">
                            <div class="card-sm-label">Günlük Gider</div>
                            <div class="stat-value" style="font-size:1.6rem;color:#c03060;">₺<?= number_format($totalOut, 2, ',', '.') ?></div>
                            <div style="margin-top:10px;font-size:.78rem;color:var(--text-muted);">
                                <div class="d-flex justify-content-between mb-1"><span>Otobüs Öd.</span><span style="font-weight:600;color:#c03060;">₺<?= number_format($summary['driver_out'] ?? 0, 2, ',', '.') ?></span></div>
                                <div class="d-flex justify-content-between"><span>Genel Gider</span><span style="font-weight:600;color:#e08b00;">₺<?= number_format($summary['expense_out'] ?? 0, 2, ',', '.') ?></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card" style="padding:16px 18px;height:100%;border:2px solid var(--accent-blue);">
                            <div class="card-sm-label">Net Kasa</div>
                            <div class="stat-value" style="font-size:1.6rem;color:var(--accent-blue);">₺<?= number_format($net, 2, ',', '.') ?></div>
                            <div style="font-size:.73rem;color:var(--text-muted);margin-top:6px;">Gelir − Gider</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card" style="padding:16px 18px;height:100%;">
                            <div class="card-sm-label">İşlem Sayısı</div>
                            <div class="stat-value" style="font-size:1.6rem;"><?= $txCount ?></div>
                            <div style="font-size:.73rem;color:var(--text-muted);margin-top:6px;"><?= $inCount ?> gelir · <?= $outCount ?> gider</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Donut grafik -->
            <div class="col-12 col-md-4">
                <div class="card d-flex flex-column align-items-center justify-content-center" style="padding:20px;height:100%;min-height:200px;">
                    <div class="section-label mb-3" style="align-self:flex-start;">Tahsilat Dağılımı</div>
                    <canvas id="vaultDonut" width="160" height="160"></canvas>
                    <div class="d-flex gap-3 mt-3" style="font-size:.77rem;">
                        <span><span style="display:inline-block;width:10px;height:10px;background:#0e8045;border-radius:2px;margin-right:4px;"></span>Nakit <?= $cashPct ?>%</span>
                        <span><span style="display:inline-block;width:10px;height:10px;background:#1b84ff;border-radius:2px;margin-right:4px;"></span>Kart <?= $cardPct ?>%</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- ══ Hareket Tablosu ══ -->
        <div class="card">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <div class="section-label">Kasa Hareketleri</div>
                    <div class="section-sub" id="txSubLabel">Yükleniyor...</div>
                </div>
                <div class="d-flex gap-2">
                    <select id="txType" class="form-input" style="width:auto;font-size:.8rem;" onchange="loadTransactions()">
                        <option value="">Tümü</option>
                        <option value="IN">Gelir</option>
                        <option value="OUT">Gider</option>
                    </select>
                    <select id="txMethod" class="form-input" style="width:auto;font-size:.8rem;" onchange="loadTransactions()">
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
                    <tbody id="txBody">
                        <tr><td colspan="6" class="text-center" style="padding:28px;color:var(--text-muted);font-size:.82rem;">Yükleniyor...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</main>

<!-- ── Gider Modal ── -->
<div id="expenseModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9998;align-items:center;justify-content:center;">
    <div style="background:var(--card-bg);border-radius:12px;padding:24px;width:420px;max-width:95vw;box-shadow:0 16px 48px rgba(0,0,0,.3);">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="section-label">Gider Ekle</div>
            <button onclick="closeExpenseModal()" style="border:none;background:none;cursor:pointer;font-size:1.2rem;color:var(--text-muted);">&times;</button>
        </div>
        <div class="d-flex flex-column gap-3">
            <div>
                <label class="form-label-sm">Gider Türü</label>
                <select id="expCategory" class="form-input">
                    <option value="expense_bus">Otobüse Ödeme</option>
                    <option value="expense_rent">Kira / Fatura</option>
                    <option value="expense_staff">Personel Avansı</option>
                    <option value="expense_office">Kırtasiye / Malzeme</option>
                    <option value="expense">Diğer</option>
                </select>
            </div>
            <div>
                <label class="form-label-sm">Tutar (₺)</label>
                <input type="number" id="expAmount" class="form-input" placeholder="0.00" min="0" step="0.01" />
            </div>
            <div>
                <label class="form-label-sm">Ödeme Yöntemi</label>
                <div class="d-flex gap-2">
                    <label class="method-btn active" id="exp-cash" onclick="selExpMethod('CASH',this)"><i class="bi bi-cash"></i> Nakit</label>
                    <label class="method-btn" id="exp-card" onclick="selExpMethod('CARD',this)"><i class="bi bi-credit-card"></i> Kart</label>
                </div>
            </div>
            <div>
                <label class="form-label-sm">Açıklama</label>
                <input type="text" id="expDesc" class="form-input" placeholder="Kısa açıklama..." />
            </div>
            <button onclick="saveExpense()" class="btn-primary-sm w-100" style="height:42px;">
                <i class="bi bi-check2 me-1"></i> Kaydet
            </button>
        </div>
    </div>
</div>

<!-- ── Gün Kapanış Modal ── -->
<div id="closeModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9998;align-items:center;justify-content:center;">
    <div style="background:var(--card-bg);border-radius:12px;padding:28px;width:460px;max-width:95vw;box-shadow:0 16px 48px rgba(0,0,0,.3);">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="section-label" style="color:#e08b00;"><i class="bi bi-lock me-2"></i>Günlük Kasa Kapanışı</div>
            <button onclick="closeCloseModal()" style="border:none;background:none;cursor:pointer;font-size:1.2rem;color:var(--text-muted);">&times;</button>
        </div>
        <div style="background:var(--body-bg);border-radius:8px;padding:16px;margin-bottom:16px;">
            <div class="d-flex justify-content-between mb-2" style="font-size:.84rem;">
                <span style="color:var(--text-muted);">Toplam Gelir</span>
                <span style="font-weight:700;color:#0e8045;">₺<?= number_format($totalIn, 2, ',', '.') ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2" style="font-size:.84rem;">
                <span style="color:var(--text-muted);">Toplam Gider</span>
                <span style="font-weight:700;color:#c03060;">₺<?= number_format($totalOut, 2, ',', '.') ?></span>
            </div>
            <hr style="border-color:var(--border-color);">
            <div class="d-flex justify-content-between" style="font-size:.9rem;">
                <span style="font-weight:700;">Net Kasa</span>
                <span style="font-weight:700;color:var(--accent-blue);">₺<?= number_format($net, 2, ',', '.') ?></span>
            </div>
        </div>
        <div class="d-flex flex-column gap-3">
            <div>
                <label class="form-label-sm">Sayılan Nakit (₺) <span style="color:#f31260;">*</span></label>
                <input type="number" id="countedCash" class="form-input" placeholder="0.00" min="0" step="0.01" />
                <div style="font-size:.72rem;color:var(--text-muted);margin-top:3px;">Beklenen: ₺<?= number_format($cashIn - ($totalOut > 0 ? $totalOut : 0), 2, ',', '.') ?> (yaklaşık)</div>
            </div>
            <div>
                <label class="form-label-sm">Kapanış Notu</label>
                <input type="text" id="closeNote" class="form-input" placeholder="Günlük kapanış notu..." />
            </div>
            <button onclick="confirmClose()" class="btn-primary-sm w-100" style="height:44px;font-size:.9rem;background:#e08b00;border-color:#e08b00;">
                <i class="bi bi-lock-fill me-2"></i>Güncelleri Kaydet & Günü Kapat
            </button>
        </div>
    </div>
</div>

<style>
    .form-label-sm { display:block;font-size:.72rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.03em;margin-bottom:4px; }
    .form-input { width:100%;border:1px solid var(--border-color);border-radius:7px;padding:7px 11px;font-size:.83rem;background:var(--body-bg);color:var(--text-dark);outline:none;transition:border-color .15s,box-shadow .15s;font-family:'Inter',sans-serif; }
    .form-input:focus { border-color:var(--accent-blue);box-shadow:0 0 0 3px rgba(27,132,255,.12); }
    .method-btn { flex:1;text-align:center;padding:7px 8px;border:1.5px solid var(--border-color);border-radius:7px;font-size:.78rem;font-weight:600;cursor:pointer;color:var(--text-muted);background:var(--body-bg);transition:all .15s;display:flex;align-items:center;justify-content:center;gap:5px; }
    .method-btn.active { border-color:var(--accent-blue);background:#e8f1ff;color:var(--accent-blue); }
    body.dark .method-btn.active { background:rgba(27,132,255,.15); }
</style>

<script>
    var expMethod = 'CASH';
    var txBalanceStart = 0; /* will be set from first API response */

    /* ── Load transactions from API ── */
    function loadTransactions() {
        var from   = document.getElementById('dateFrom').value;
        var to     = document.getElementById('dateTo').value;
        var type   = document.getElementById('txType').value;
        var method = document.getElementById('txMethod').value;
        var url    = 'api.php?action=vault.list&from=' + from + '&to=' + to +
                     (type ? '&type=' + type : '') + (method ? '&method=' + method : '');

        document.getElementById('txBody').innerHTML = '<tr><td colspan="6" class="text-center" style="padding:28px;color:var(--text-muted);font-size:.82rem;">Yükleniyor...</td></tr>';

        fetch(url)
        .then(r => r.json())
        .then(res => {
            var data = Array.isArray(res) ? res : (res.data || []);
            document.getElementById('txSubLabel').textContent = data.length + ' işlem';
            if (data.length === 0) {
                document.getElementById('txBody').innerHTML = '<tr><td colspan="6" class="text-center" style="padding:28px;color:var(--text-muted);font-size:.82rem;">Kayıt bulunamadı.</td></tr>';
                return;
            }
            var balance = 0;
            var rows = data.map(function (tx) {
                balance += (tx.type === 'IN' ? parseFloat(tx.amount) : -parseFloat(tx.amount));
                var time = tx.created_at ? tx.created_at.substring(11,16) : '--:--';
                var typeHtml = tx.type === 'IN'
                    ? '<span class="status-badge" style="background:#e7f9f0;color:#0e8045;font-size:.72rem;"><i class="bi bi-arrow-down-left me-1"></i>Gelir</span>'
                    : '<span class="status-badge" style="background:#fff0f2;color:#c03060;font-size:.72rem;"><i class="bi bi-arrow-up-right me-1"></i>Gider</span>';
                var methodHtml = tx.method === 'CASH'
                    ? '<span class="status-badge" style="background:#fff8ec;color:#e08b00;font-size:.72rem;"><i class="bi bi-cash me-1"></i>Nakit</span>'
                    : '<span class="status-badge" style="background:#e8f1ff;color:#1b84ff;font-size:.72rem;"><i class="bi bi-credit-card me-1"></i>Kart</span>';
                var amtColor = tx.type === 'IN' ? '#0e8045' : '#c03060';
                var amtSign  = tx.type === 'IN' ? '+' : '−';
                return '<tr>' +
                    '<td style="font-size:.78rem;color:var(--text-muted);">' + time + '</td>' +
                    '<td style="font-size:.82rem;">' + (tx.description || '') + '</td>' +
                    '<td>' + typeHtml + '</td>' +
                    '<td>' + methodHtml + '</td>' +
                    '<td style="font-weight:700;font-size:.85rem;color:' + amtColor + ';">' + amtSign + '₺' + parseFloat(tx.amount).toFixed(2) + '</td>' +
                    '<td style="font-size:.82rem;font-weight:600;font-family:monospace;">₺' + balance.toFixed(2) + '</td>' +
                    '</tr>';
            });
            document.getElementById('txBody').innerHTML = rows.join('');
        })
        .catch(() => {
            document.getElementById('txBody').innerHTML = '<tr><td colspan="6" class="text-center" style="padding:28px;color:#c03060;font-size:.82rem;">Sunucu hatası oluştu.</td></tr>';
        });
    }

    function filterVault() { loadTransactions(); }
    function setRange(r) {
        var from = document.getElementById('dateFrom');
        var to   = document.getElementById('dateTo');
        var now  = new Date();
        if (r === 'today') { from.value = to.value = now.toISOString().slice(0,10); }
        else if (r === 'week') {
            var d = new Date(now); d.setDate(d.getDate() - d.getDay() + 1);
            from.value = d.toISOString().slice(0,10); to.value = now.toISOString().slice(0,10);
        } else if (r === 'month') {
            from.value = now.getFullYear() + '-' + String(now.getMonth()+1).padStart(2,'0') + '-01';
            to.value   = now.toISOString().slice(0,10);
        }
        loadTransactions();
    }

    /* ── Gider Modal ── */
    function openExpenseModal() { document.getElementById('expenseModal').style.display = 'flex'; }
    function closeExpenseModal() { document.getElementById('expenseModal').style.display = 'none'; }
    function selExpMethod(m, el) {
        expMethod = m;
        document.querySelectorAll('#expenseModal .method-btn').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
    }
    function saveExpense() {
        var amount = parseFloat(document.getElementById('expAmount').value) || 0;
        if (amount <= 0) { showToast('Tutar giriniz!', 'error'); return; }
        fetch('api.php?action=vault.addExpense', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify({
                category: document.getElementById('expCategory').value,
                amount: amount,
                method: expMethod,
                description: document.getElementById('expDesc').value,
            })
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                closeExpenseModal();
                showToast('Gider kaydedildi.', 'success');
                loadTransactions();
                document.getElementById('expAmount').value = '';
                document.getElementById('expDesc').value   = '';
            } else {
                showToast('Hata: ' + (res.error || ''), 'error');
            }
        })
        .catch(() => showToast('Sunucu hatası!', 'error'));
    }

    /* ── Gün Kapanış Modal ── */
    function openCloseModal() { document.getElementById('closeModal').style.display = 'flex'; }
    function closeCloseModal() { document.getElementById('closeModal').style.display = 'none'; }
    function confirmClose() {
        var counted = parseFloat(document.getElementById('countedCash').value);
        if (isNaN(counted)) { showToast('Sayılan nakiti giriniz!', 'error'); return; }
        if (!confirm('Bugünün kasasını kapatmak istediğinizden emin misiniz?')) return;
        fetch('api.php?action=vault.close', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify({
                counted_cash: counted,
                note: document.getElementById('closeNote').value,
                date: document.getElementById('dateFrom').value,
            })
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                closeCloseModal();
                showToast('✓ Kasa kapatıldı. Kapanış raporu hazırlandı.', 'success');
            } else {
                showToast('Hata: ' + (res.error || ''), 'error');
            }
        })
        .catch(() => showToast('Sunucu hatası!', 'error'));
    }

    /* ── Donut Grafik ── */
    window.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('vaultDonut').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Nakit', 'Kredi Kartı'],
                datasets: [{ data: [<?= $cashPct ?>, <?= $cardPct ?>], backgroundColor: ['#0e8045','#1b84ff'], borderWidth:0, hoverOffset:4 }]
            },
            options: { cutout:'72%', plugins: { legend: { display:false }, tooltip: { callbacks: { label: function(c) { return c.label + ': %' + c.raw; } } } } }
        });
        loadTransactions(); /* Initial load */
    });

    function showToast(msg, type) {
        var colors = { success:'#0e8045', info:'#1b84ff', error:'#c03060', warn:'#e08b00' };
        var t = document.createElement('div');
        t.style.cssText = 'position:fixed;bottom:24px;right:24px;padding:11px 18px;border-radius:8px;font-size:.82rem;font-weight:600;z-index:9999;box-shadow:0 4px 14px rgba(0,0,0,.18);color:#fff;background:' + (colors[type]||'#333') + ';transition:opacity .3s;max-width:340px;';
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(function(){ t.style.opacity='0'; setTimeout(function(){ t.remove(); }, 300); }, 3000);
    }
</script>