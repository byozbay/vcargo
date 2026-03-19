<?php
require_once __DIR__ . '/../../core/autoload.php';
$base = new BaseModel();
$bus_companies = $base->query("SELECT bc.company_id, bc.name, bc.contact_person, bc.phone, bc.commission_rate, bc.is_active, COUNT(DISTINCT t.trip_id) AS total_trips FROM bus_companies bc LEFT JOIN trips t ON t.company_id=bc.company_id GROUP BY bc.company_id, bc.name, bc.contact_person, bc.phone, bc.commission_rate, bc.is_active ORDER BY bc.name");
?>
<?php
$companies = [
    ['id' => 1, 'name' => 'Metro Turizm', 'code' => 'MTR', 'contact' => 'Ali Yılmaz', 'phone' => '0212 444 0001', 'email' => 'info@metro.com.tr', 'iban' => 'TR12 0001 2345 6789 0123 4567 89', 'comm' => 15, 'trips_month' => 184, 'cargo_month' => 1840, 'revenue_month' => 92000, 'status' => 'active'],
    ['id' => 2, 'name' => 'Pamukkale Turizm', 'code' => 'PMK', 'contact' => 'Fatma Çelik', 'phone' => '0212 444 0002', 'email' => 'info@pamukkale.com.tr', 'iban' => 'TR12 0001 2345 6789 0123 4567 90', 'comm' => 13, 'trips_month' => 141, 'cargo_month' => 1290, 'revenue_month' => 64500, 'status' => 'active'],
    ['id' => 3, 'name' => 'Kamil Koç', 'code' => 'KMK', 'contact' => 'Hüseyin Demir', 'phone' => '0212 444 0003', 'email' => 'info@kamilkoc.com.tr', 'iban' => 'TR12 0001 2345 6789 0123 4567 91', 'comm' => 14, 'trips_month' => 118, 'cargo_month' => 1060, 'revenue_month' => 53000, 'status' => 'active'],
    ['id' => 4, 'name' => 'Uludağ Turizm', 'code' => 'ULG', 'contact' => 'Selin Arslan', 'phone' => '0212 444 0004', 'email' => 'info@uludag.com.tr', 'iban' => 'TR12 0001 2345 6789 0123 4567 92', 'comm' => 12, 'trips_month' => 96, 'cargo_month' => 860, 'revenue_month' => 43000, 'status' => 'active'],
    ['id' => 5, 'name' => 'Varan Turizm', 'code' => 'VRN', 'contact' => 'Mehmet Şahin', 'phone' => '0212 444 0005', 'email' => 'info@varan.com.tr', 'iban' => 'TR12 0001 2345 6789 0123 4567 93', 'comm' => 16, 'trips_month' => 74, 'cargo_month' => 590, 'revenue_month' => 29500, 'status' => 'active'],
    ['id' => 6, 'name' => 'Süha Turizm', 'code' => 'SUH', 'contact' => 'Zeynep Kara', 'phone' => '0212 444 0006', 'email' => 'info@suha.com.tr', 'iban' => 'TR12 0001 2345 6789 0123 4567 94', 'comm' => 13, 'trips_month' => 52, 'cargo_month' => 410, 'revenue_month' => 20500, 'status' => 'active'],
    ['id' => 7, 'name' => 'Köseoğlu Turizm', 'code' => 'KSG', 'contact' => 'Burak Deniz', 'phone' => '0212 444 0007', 'email' => 'info@koseoglu.com.tr', 'iban' => 'TR12 0001 2345 6789 0123 4567 95', 'comm' => 11, 'trips_month' => 38, 'cargo_month' => 290, 'revenue_month' => 14500, 'status' => 'passive'],
    ['id' => 8, 'name' => 'Özkaymak Turizm', 'code' => 'OZK', 'contact' => 'Caner Yıldız', 'phone' => '0212 444 0008', 'email' => 'info@ozkaymak.com.tr', 'iban' => 'TR12 0001 2345 6789 0123 4567 96', 'comm' => 12, 'trips_month' => 0, 'cargo_month' => 0, 'revenue_month' => 0, 'status' => 'passive'],
];

$totalActive = count(array_filter($companies, fn($c) => $c['status'] === 'active'));
$totalTrips = array_sum(array_column($companies, 'trips_month'));
$totalRevenue = array_sum(array_column($companies, 'revenue_month'));
?>
<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Otobüs Firmaları</div>
            <div class="breadcrumb">
                <a href="?page=dashboard" style="color:var(--text-muted);">Dashboard</a>
                <span class="sep">·</span>
                <span style="color:var(--text-muted);">Otobüs Firmaları</span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn-outline-secondary-sm d-flex align-items-center gap-1" onclick="exportCompanies()">
                <i class="bi bi-download"></i> Dışa Aktar
            </button>
            <button class="btn-primary-sm d-flex align-items-center gap-1" onclick="openModal()">
                <i class="bi bi-plus-lg"></i> Yeni Firma Ekle
            </button>
        </div>
    </div>

    <div style="padding:18px 26px 40px;">

        <!-- ══ KPI Kartları ══ -->
        <div class="row g-3 mb-3">
            <div class="col-6 col-lg-3">
                <div class="card h-100" style="padding:18px;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="card-sm-label">Toplam Firma</div>
                            <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">
                                <?= count($companies) ?>
                            </div>
                            <span class="badge-change badge-up mt-2">
                                <?= $totalActive ?> aktif çalışıyor
                            </span>
                        </div>
                        <div
                            style="width:42px;height:42px;border-radius:8px;background:#e8f1ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-bus-front" style="color:#1b84ff;font-size:1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100" style="padding:18px;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="card-sm-label">Bu Ay Sefer</div>
                            <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">
                                <?= number_format($totalTrips) ?>
                            </div>
                            <span class="badge-change badge-up mt-2">&#8593; 9% geçen aya göre</span>
                        </div>
                        <div
                            style="width:42px;height:42px;border-radius:8px;background:#e7f9f0;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-signpost-split" style="color:#0e8045;font-size:1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100" style="padding:18px;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="card-sm-label">Ay Komisyon Ödemesi</div>
                            <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">₺
                                <?= number_format(array_sum(array_map(fn($c) => $c['revenue_month'] * $c['comm'] / 100, $companies)) / 1000, 1) ?>K
                            </div>
                            <span class="badge-change badge-up mt-2">Firmalara ödenen</span>
                        </div>
                        <div
                            style="width:42px;height:42px;border-radius:8px;background:#fff8ec;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-cash-stack" style="color:#e08b00;font-size:1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100" style="padding:18px;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="card-sm-label">Ay Kargo Hacmi</div>
                            <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">
                                <?= number_format(array_sum(array_column($companies, 'cargo_month'))) ?>
                            </div>
                            <span class="badge-change badge-up mt-2">Toplam taşınan kargo</span>
                        </div>
                        <div
                            style="width:42px;height:42px;border-radius:8px;background:#f3e5f5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-box-seam" style="color:#8e24aa;font-size:1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ Filtre ══ -->
        <div class="card mb-3" style="padding:14px 18px;">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div style="position:relative;">
                        <i class="bi bi-search"
                            style="position:absolute;top:50%;left:10px;transform:translateY(-50%);color:var(--text-muted);font-size:.85rem;"></i>
                        <input type="text" id="companySearch" class="form-input" style="padding-left:30px;"
                            placeholder="Firma adı, kod veya yetkili ara..." oninput="filterCompanies()">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-input" id="filterStatus" onchange="filterCompanies()">
                        <option value="">Tüm Durumlar</option>
                        <option value="active">Aktif</option>
                        <option value="passive">Pasif</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-input" id="sortBy" onchange="filterCompanies()">
                        <option value="trips">Sefer (Çok→Az)</option>
                        <option value="cargo">Kargo (Çok→Az)</option>
                        <option value="comm">Komisyon %</option>
                        <option value="name">İsim (A→Z)</option>
                    </select>
                </div>
                <div class="col-md-3 text-end">
                    <span id="companyCount" style="font-size:.8rem;color:var(--text-muted);">
                        <?= count($companies) ?> firma
                    </span>
                </div>
            </div>
        </div>

        <!-- ══ Firma Tablosu ══ -->
        <div class="card">
            <div class="table-responsive">
                <table class="orders-table" id="companiesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Firma</th>
                            <th>Yetkili</th>
                            <th>Telefon</th>
                            <th>Kom. %</th>
                            <th>Ay Sefer</th>
                            <th>Ay Kargo</th>
                            <th>Ay Ciro</th>
                            <th>Durum</th>
                            <th style="text-align:right;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody id="companiesBody">
                        <?php
                        $chipColors = ['#1b84ff', '#0e8045', '#8e24aa', '#e08b00', '#c03060', '#00897b', '#546e7a', '#6d4c41'];
                        foreach ($companies as $i => $c):
                            $color = $chipColors[$i % count($chipColors)];
                            $statusBg = $c['status'] === 'active' ? '#e7f9f0' : '#f1f3f4';
                            $statusClr = $c['status'] === 'active' ? '#0e8045' : '#78909c';
                            $statusLbl = $c['status'] === 'active' ? 'Aktif' : 'Pasif';
                            ?>
                            <tr class="company-row" data-status="<?= $c['status'] ?>"
                                data-name="<?= mb_strtolower($c['name']) ?>" data-code="<?= mb_strtolower($c['code']) ?>"
                                data-contact="<?= mb_strtolower($c['contact']) ?>" data-trips="<?= $c['trips_month'] ?>"
                                data-cargo="<?= $c['cargo_month'] ?>" data-comm="<?= $c['comm'] ?>">
                                <td style="font-size:.78rem;color:var(--text-muted);">
                                    <?= $c['id'] ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div
                                            style="width:36px;height:36px;border-radius:8px;background:<?= $color ?>22;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <span style="font-size:.7rem;font-weight:800;color:<?= $color ?>;">
                                                <?= $c['code'] ?>
                                            </span>
                                        </div>
                                        <div>
                                            <div style="font-size:.82rem;font-weight:700;">
                                                <?= $c['name'] ?>
                                            </div>
                                            <div style="font-size:.7rem;color:var(--text-muted);">
                                                <?= $c['email'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:.81rem;">
                                    <?= $c['contact'] ?>
                                </td>
                                <td style="font-size:.78rem;color:var(--text-muted);">
                                    <?= $c['phone'] ?>
                                </td>
                                <td>
                                    <span style="font-size:.82rem;font-weight:700;color:#1b84ff;">%
                                        <?= $c['comm'] ?>
                                    </span>
                                </td>
                                <td style="font-size:.82rem;font-weight:600;">
                                    <?= number_format($c['trips_month']) ?>
                                </td>
                                <td style="font-size:.82rem;">
                                    <?= number_format($c['cargo_month']) ?>
                                </td>
                                <td style="font-size:.82rem;font-weight:700;color:#0e8045;">
                                    <?= $c['revenue_month'] > 0 ? '₺' . number_format($c['revenue_month']) : '—' ?>
                                </td>
                                <td>
                                    <span class="status-badge" style="background:<?= $statusBg ?>;color:<?= $statusClr ?>;">
                                        <?= $statusLbl ?>
                                    </span>
                                </td>
                                <td style="text-align:right;">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button class="icon-btn-circle" title="IBAN Gör"
                                            onclick="showIban(<?= $c['id'] ?>, '<?= $c['name'] ?>', '<?= $c['iban'] ?>')"
                                            style="width:28px;height:28px;font-size:.8rem;">
                                            <i class="bi bi-bank"></i>
                                        </button>
                                        <button class="icon-btn-circle" title="Düzenle" onclick="openModal(<?= $c['id'] ?>, '<?= htmlspecialchars($c['name']) ?>',
                                                           '<?= $c['code'] ?>', '<?= $c['contact'] ?>',
                                                           '<?= $c['phone'] ?>', '<?= $c['email'] ?>',
                                                           <?= $c['comm'] ?>, '<?= $c['status'] ?>')"
                                            style="width:28px;height:28px;font-size:.8rem;">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="icon-btn-circle"
                                            title="<?= $c['status'] === 'active' ? 'Pasife Al' : 'Aktif Et' ?>"
                                            onclick="toggleStatus(<?= $c['id'] ?>, '<?= $c['status'] ?>', '<?= htmlspecialchars($c['name']) ?>')"
                                            style="width:28px;height:28px;font-size:.8rem;
                                               background:<?= $c['status'] === 'active' ? '#fff0f2' : '#e7f9f0' ?>;
                                               border-color:<?= $c['status'] === 'active' ? '#c03060' : '#0e8045' ?>;">
                                            <i class="bi bi-<?= $c['status'] === 'active' ? 'pause' : 'play' ?>-circle"
                                                style="color:<?= $c['status'] === 'active' ? '#c03060' : '#0e8045' ?>;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex align-items-center justify-content-between px-3 py-3 flex-wrap gap-2"
                style="border-top:1px solid var(--border-color);font-size:.78rem;color:var(--text-muted);">
                <span id="rowCountInfo">
                    <?= count($companies) ?> firma listelendi
                </span>
                <span>Sayfa 1 / 1</span>
            </div>
        </div>

    </div>

    <!-- ══ Firma Ekle / Düzenle Modal ══ -->
    <div id="companyModal" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,.45);
     align-items:center;justify-content:center;">
        <div style="background:var(--card-bg);border-radius:10px;width:100%;max-width:520px;margin:20px;
                box-shadow:0 20px 60px rgba(0,0,0,.25);max-height:90vh;overflow-y:auto;">
            <div
                style="padding:20px 24px;border-bottom:1px solid var(--border-color);display:flex;justify-content:space-between;align-items:center;">
                <div style="font-size:.95rem;font-weight:700;" id="modalTitle">Yeni Firma Ekle</div>
                <button onclick="closeModal()"
                    style="background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1.1rem;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div style="padding:22px 24px;">
                <input type="hidden" id="editId" value="">
                <div class="row g-3">
                    <div class="col-12 col-md-8">
                        <label class="form-label-sm">Firma Adı <span style="color:#c03060;">*</span></label>
                        <input type="text" class="form-input" id="mc-name" placeholder="ör. Metro Turizm">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label-sm">Kısa Kod <span style="color:#c03060;">*</span></label>
                        <input type="text" class="form-input" id="mc-code" placeholder="ör. MTR" maxlength="4"
                            style="text-transform:uppercase;">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-sm">Yetkili Adı</label>
                        <input type="text" class="form-input" id="mc-contact" placeholder="Ad Soyad">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-sm">Telefon</label>
                        <input type="text" class="form-input" id="mc-phone" placeholder="0212 ___ __ __">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-sm">E-posta</label>
                        <input type="email" class="form-input" id="mc-email" placeholder="info@firma.com.tr">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-sm">Komisyon Oranı (%) <span style="color:#c03060;">*</span></label>
                        <input type="number" class="form-input" id="mc-comm" value="15" min="0" max="50" step="0.5">
                    </div>
                    <div class="col-12">
                        <label class="form-label-sm">IBAN</label>
                        <input type="text" class="form-input" id="mc-iban"
                            placeholder="TR__ ____ ____ ____ ____ ____ __" maxlength="32">
                    </div>
                    <div class="col-12">
                        <label class="form-label-sm">Durum</label>
                        <select class="form-input" id="mc-status">
                            <option value="active">Aktif</option>
                            <option value="passive">Pasif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div
                style="padding:16px 24px;border-top:1px solid var(--border-color);display:flex;justify-content:flex-end;gap:8px;">
                <button onclick="closeModal()" class="btn-outline-secondary-sm">İptal</button>
                <button onclick="saveCompany()" class="btn-primary-sm">
                    <i class="bi bi-check-lg me-1"></i> Kaydet
                </button>
            </div>
        </div>
    </div>

    <!-- IBAN Modal -->
    <div id="ibanModal" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,.45);
     align-items:center;justify-content:center;">
        <div style="background:var(--card-bg);border-radius:10px;width:100%;max-width:400px;margin:20px;
                box-shadow:0 20px 60px rgba(0,0,0,.25);padding:24px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div style="font-size:.92rem;font-weight:700;" id="ibanCompanyName"></div>
                <button onclick="closeIban()"
                    style="background:none;border:none;cursor:pointer;color:var(--text-muted);">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div style="background:var(--body-bg);border-radius:6px;padding:14px;font-family:monospace;
                    font-size:.85rem;font-weight:600;letter-spacing:.05em;word-break:break-all;" id="ibanValue"></div>
            <button onclick="copyIban()" class="btn-outline-secondary-sm mt-3 d-flex align-items-center gap-2">
                <i class="bi bi-clipboard"></i> Kopyala
            </button>
        </div>
    </div>

    <!-- Toast -->
    <div id="bcToast" style="position:fixed;bottom:24px;right:24px;z-index:9999;padding:12px 20px;
     border-radius:6px;font-size:.82rem;font-weight:600;background:#1b84ff;color:#fff;
     opacity:0;transition:opacity .3s;pointer-events:none;"></div>

    <script>
        function showToast(msg, type) {
            var t = document.getElementById('bcToast');
            t.style.background = { success: '#0e8045', error: '#c03060', info: '#1b84ff' }[type] || '#1b84ff';
            t.textContent = msg;
            t.style.opacity = '1';
            setTimeout(function () { t.style.opacity = '0'; }, 3000);
        }

        /* ── Filtrele ── */
        function filterCompanies() {
            var q = document.getElementById('companySearch').value.trim().toLowerCase();
            var status = document.getElementById('filterStatus').value;
            var sortBy = document.getElementById('sortBy').value;

            var rows = Array.from(document.querySelectorAll('.company-row'));
            var visible = [];

            rows.forEach(function (r) {
                var match = (!q || r.dataset.name.includes(q) || r.dataset.code.includes(q) || r.dataset.contact.includes(q))
                    && (!status || r.dataset.status === status);
                r.style.display = match ? '' : 'none';
                if (match) visible.push(r);
            });

            visible.sort(function (a, b) {
                if (sortBy === 'trips') return parseInt(b.dataset.trips) - parseInt(a.dataset.trips);
                if (sortBy === 'cargo') return parseInt(b.dataset.cargo) - parseInt(a.dataset.cargo);
                if (sortBy === 'comm') return parseInt(b.dataset.comm) - parseInt(a.dataset.comm);
                if (sortBy === 'name') return a.dataset.name.localeCompare(b.dataset.name);
                return 0;
            });

            var tbody = document.getElementById('companiesBody');
            visible.forEach(function (r) { tbody.appendChild(r); });
            document.getElementById('companyCount').textContent = visible.length + ' firma';
            document.getElementById('rowCountInfo').textContent = visible.length + ' firma listelendi';
        }

        /* ── Modal Aç ── */
        function openModal(id, name, code, contact, phone, email, comm, status) {
            document.getElementById('editId').value = id || '';
            document.getElementById('mc-name').value = name || '';
            document.getElementById('mc-code').value = code || '';
            document.getElementById('mc-contact').value = contact || '';
            document.getElementById('mc-phone').value = phone || '';
            document.getElementById('mc-email').value = email || '';
            document.getElementById('mc-comm').value = comm || 15;
            document.getElementById('mc-status').value = status || 'active';
            document.getElementById('mc-iban').value = '';
            document.getElementById('modalTitle').textContent = id ? name + ' — Düzenle' : 'Yeni Firma Ekle';
            document.getElementById('companyModal').style.display = 'flex';
            document.getElementById('mc-name').focus();
        }
        function closeModal() { document.getElementById('companyModal').style.display = 'none'; }

        /* ── Kaydet ── */
        function saveCompany() {
            var name = document.getElementById('mc-name').value.trim();
            var code = document.getElementById('mc-code').value.trim();
            if (!name) { showToast('Firma adı zorunludur!', 'error'); return; }
            if (!code) { showToast('Kısa kod zorunludur!', 'error'); return; }
            var id = document.getElementById('editId').value;
            showToast(id ? '✓ ' + name + ' güncellendi.' : '✓ ' + name + ' eklendi.', 'success');
            closeModal();
        }

        /* ── IBAN ── */
        function showIban(id, name, iban) {
            document.getElementById('ibanCompanyName').textContent = name;
            document.getElementById('ibanValue').textContent = iban;
            document.getElementById('ibanModal').style.display = 'flex';
        }
        function closeIban() { document.getElementById('ibanModal').style.display = 'none'; }
        function copyIban() {
            var iban = document.getElementById('ibanValue').textContent;
            navigator.clipboard.writeText(iban).then(function () { showToast('✓ IBAN kopyalandı.', 'success'); });
        }

        /* ── Durum Değiştir ── */
        function toggleStatus(id, current, name) {
            var msg = current === 'active' ? name + ' firmasını pasife almak istiyor musunuz?' : name + ' firmasını aktif etmek istiyor musunuz?';
            if (!confirm(msg)) return;
            showToast(current === 'active' ? '✓ ' + name + ' pasife alındı.' : '✓ ' + name + ' aktif edildi.', 'success');
        }

        function exportCompanies() { showToast('Dışa aktarma başlatıldı...', 'info'); }

        /* Dışarı tıklayınca kapat */
        document.getElementById('companyModal').addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });
        document.getElementById('ibanModal').addEventListener('click', function (e) {
            if (e.target === this) closeIban();
        });
    </script>