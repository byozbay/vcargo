<?php
require_once __DIR__ . '/../../core/autoload.php';
$base = new BaseModel();
$branches = $base->query("SELECT b.branch_id, MIN(b.name) AS name, MIN(c.name) AS city, b.address, b.phone, b.branch_type, b.is_active, b.free_storage_hours, b.storage_hourly_rate, b.baggage_hourly_rate, COUNT(u.user_id) AS staff_count FROM branches b LEFT JOIN cities c ON c.city_id=b.city_id LEFT JOIN users u ON u.branch_id=b.branch_id AND u.is_active=1 GROUP BY b.branch_id, b.address, b.phone, b.branch_type, b.is_active, b.free_storage_hours, b.storage_hourly_rate, b.baggage_hourly_rate ORDER BY b.is_active DESC, b.name");
?>
<?php
$todayDate = date('d.m.Y');

// Mock branches data
$branches = [
    ['id' => 1, 'name' => 'İstanbul Otogar', 'city' => 'İstanbul', 'type' => 'CORPORATE', 'manager' => 'Mehmet Kara', 'phone' => '0212 555 0101', 'cargo_month' => 842, 'revenue_month' => 48600, 'staff' => 6, 'status' => 'active'],
    ['id' => 2, 'name' => 'Ankara Şehirler', 'city' => 'Ankara', 'type' => 'CORPORATE', 'manager' => 'Ayşe Yıldız', 'phone' => '0312 555 0202', 'cargo_month' => 614, 'revenue_month' => 36200, 'staff' => 4, 'status' => 'active'],
    ['id' => 3, 'name' => 'İzmir Ege', 'city' => 'İzmir', 'type' => 'FRANCHISE', 'manager' => 'Hasan Demir', 'phone' => '0232 555 0303', 'cargo_month' => 501, 'revenue_month' => 28900, 'staff' => 3, 'status' => 'active'],
    ['id' => 4, 'name' => 'Bursa Osmangazi', 'city' => 'Bursa', 'type' => 'FRANCHISE', 'manager' => 'Caner Güneş', 'phone' => '0224 555 0404', 'cargo_month' => 388, 'revenue_month' => 22100, 'staff' => 3, 'status' => 'active'],
    ['id' => 5, 'name' => 'Antalya Liman', 'city' => 'Antalya', 'type' => 'FRANCHISE', 'manager' => 'Selin Çelik', 'phone' => '0242 555 0505', 'cargo_month' => 312, 'revenue_month' => 18400, 'staff' => 2, 'status' => 'active'],
    ['id' => 6, 'name' => 'Konya Merkez', 'city' => 'Konya', 'type' => 'FRANCHISE', 'manager' => 'Ali Şahin', 'phone' => '0332 555 0606', 'cargo_month' => 274, 'revenue_month' => 15600, 'staff' => 2, 'status' => 'active'],
    ['id' => 7, 'name' => 'Adana Seyhan', 'city' => 'Adana', 'type' => 'FRANCHISE', 'manager' => 'Fatma Arslan', 'phone' => '0322 555 0707', 'cargo_month' => 218, 'revenue_month' => 12300, 'staff' => 2, 'status' => 'active'],
    ['id' => 8, 'name' => 'Gaziantep Bayi', 'city' => 'Gaziantep', 'type' => 'FRANCHISE', 'manager' => '—', 'phone' => '—', 'cargo_month' => 0, 'revenue_month' => 0, 'staff' => 0, 'status' => 'pending'],
    ['id' => 9, 'name' => 'Trabzon Sahil', 'city' => 'Trabzon', 'type' => 'FRANCHISE', 'manager' => 'Zeynep Ok', 'phone' => '0462 555 0909', 'cargo_month' => 104, 'revenue_month' => 6100, 'staff' => 1, 'status' => 'active'],
    ['id' => 10, 'name' => 'Kayseri Terminal', 'city' => 'Kayseri', 'type' => 'FRANCHISE', 'manager' => 'Kadir Bal', 'phone' => '0352 555 1010', 'cargo_month' => 88, 'revenue_month' => 4900, 'staff' => 1, 'status' => 'suspended'],
];

$totalActive = count(array_filter($branches, fn($b) => $b['status'] === 'active'));
$totalPending = count(array_filter($branches, fn($b) => $b['status'] === 'pending'));
$totalCargo = array_sum(array_column($branches, 'cargo_month'));
?>
<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Şube Yönetimi</div>
            <div class="breadcrumb">
                <a href="?page=dashboard" style="color:var(--text-muted);">Dashboard</a>
                <span class="sep">·</span>
                <span style="color:var(--text-muted);">Şube Listesi</span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn-outline-secondary-sm d-flex align-items-center gap-1" onclick="exportBranches()">
                <i class="bi bi-download"></i> Dışa Aktar
            </button>
            <a href="?page=branch_create" class="btn-primary-sm d-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i> Yeni Şube
            </a>
        </div>
    </div>

    <div style="padding:18px 26px 40px;">

        <!-- ══ KPI Kartları ══ -->
        <div class="row g-3 mb-3">
            <div class="col-6 col-lg-3">
                <div class="card h-100" style="padding:18px;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="card-sm-label">Toplam Şube</div>
                            <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">
                                <?= count($branches) ?>
                            </div>
                            <span class="badge-change badge-up mt-2">&#8593; 3 yeni bu ay</span>
                        </div>
                        <div
                            style="width:42px;height:42px;border-radius:8px;background:#e8f1ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-shop" style="color:#1b84ff;font-size:1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100" style="padding:18px;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="card-sm-label">Aktif Şube</div>
                            <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">
                                <?= $totalActive ?>
                            </div>
                            <span class="badge-change badge-up mt-2">Operasyonel</span>
                        </div>
                        <div
                            style="width:42px;height:42px;border-radius:8px;background:#e7f9f0;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-check-circle" style="color:#0e8045;font-size:1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100" style="padding:18px;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="card-sm-label">Onay Bekleyen</div>
                            <div class="stat-value" style="font-size:1.8rem;margin-top:4px;color:#e08b00;">
                                <?= $totalPending ?>
                            </div>
                            <span class="badge-change badge-down mt-2">İnceleme gerekli</span>
                        </div>
                        <div
                            style="width:42px;height:42px;border-radius:8px;background:#fff8ec;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-hourglass-split" style="color:#e08b00;font-size:1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100" style="padding:18px;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="card-sm-label">Bu Ay Kargo (Toplam)</div>
                            <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">
                                <?= number_format($totalCargo) ?>
                            </div>
                            <span class="badge-change badge-up mt-2">&#8593; 11% geçen aya göre</span>
                        </div>
                        <div
                            style="width:42px;height:42px;border-radius:8px;background:#e8f1ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-box-seam" style="color:#1b84ff;font-size:1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ Filtre & Arama ══ -->
        <div class="card mb-3" style="padding:14px 18px;">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div style="position:relative;">
                        <i class="bi bi-search"
                            style="position:absolute;top:50%;left:10px;transform:translateY(-50%);color:var(--text-muted);font-size:.85rem;"></i>
                        <input type="text" id="branchSearch" class="form-input" style="padding-left:30px;"
                            placeholder="Şube adı, şehir veya müdür ara..." oninput="filterBranches()">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-input" id="filterType" onchange="filterBranches()">
                        <option value="">Tüm Türler</option>
                        <option value="CORPORATE">Merkez</option>
                        <option value="FRANCHISE">Bayi</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-input" id="filterStatus" onchange="filterBranches()">
                        <option value="">Tüm Durumlar</option>
                        <option value="active">Aktif</option>
                        <option value="pending">Onay Bekliyor</option>
                        <option value="suspended">Askıya Alındı</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-input" id="sortBy" onchange="filterBranches()">
                        <option value="cargo">Kargo (Çok→Az)</option>
                        <option value="revenue">Ciro (Çok→Az)</option>
                        <option value="name">İsim (A→Z)</option>
                    </select>
                </div>
                <div class="col-6 col-md-2 text-end">
                    <span id="branchCount" style="font-size:.8rem;color:var(--text-muted);">
                        <?= count($branches) ?> şube gösteriliyor
                    </span>
                </div>
            </div>
        </div>

        <!-- ══ Şube Tablosu ══ -->
        <div class="card">
            <div class="table-responsive">
                <table class="orders-table" id="branchesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Şube Adı</th>
                            <th>Şehir</th>
                            <th>Tür</th>
                            <th>Müdür</th>
                            <th>Telefon</th>
                            <th>Personel</th>
                            <th>Ay Kargo</th>
                            <th>Ay Ciro</th>
                            <th>Durum</th>
                            <th style="text-align:right;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody id="branchesBody">
                        <?php foreach ($branches as $b):
                            // Status badge
                            $statusMap = [
                                'active' => ['Aktif', '#e7f9f0', '#0e8045'],
                                'pending' => ['Onay Bekliyor', '#fff8ec', '#e08b00'],
                                'suspended' => ['Askıya Alındı', '#fff0f2', '#c03060'],
                            ];
                            [$sLabel, $sBg, $sColor] = $statusMap[$b['status']];

                            // Type badge
                            $typeBadge = $b['type'] === 'CORPORATE'
                                ? '<span class="status-badge" style="background:#e8f1ff;color:#1b84ff;">Merkez</span>'
                                : '<span class="status-badge" style="background:#f3e5f5;color:#8e24aa;">Bayi</span>';
                            ?>
                            <tr class="branch-row" data-type="<?= $b['type'] ?>" data-status="<?= $b['status'] ?>"
                                data-name="<?= mb_strtolower($b['name']) ?>" data-city="<?= mb_strtolower($b['city']) ?>"
                                data-manager="<?= mb_strtolower($b['manager']) ?>" data-cargo="<?= $b['cargo_month'] ?>"
                                data-revenue="<?= $b['revenue_month'] ?>">
                                <td style="font-size:.78rem;color:var(--text-muted);">
                                    <?= $b['id'] ?>
                                </td>
                                <td>
                                    <div style="font-size:.82rem;font-weight:600;">
                                        <?= $b['name'] ?>
                                    </div>
                                </td>
                                <td style="font-size:.81rem;">
                                    <?= $b['city'] ?>
                                </td>
                                <td>
                                    <?= $typeBadge ?>
                                </td>
                                <td style="font-size:.81rem;">
                                    <?= $b['manager'] ?>
                                </td>
                                <td style="font-size:.78rem;color:var(--text-muted);">
                                    <?= $b['phone'] ?>
                                </td>
                                <td style="font-size:.82rem;text-align:center;">
                                    <?= $b['staff'] ?>
                                </td>
                                <td style="font-size:.82rem;font-weight:600;">
                                    <?= number_format($b['cargo_month']) ?>
                                </td>
                                <td style="font-size:.82rem;font-weight:700;color:#0e8045;">
                                    <?= $b['revenue_month'] > 0 ? '₺' . number_format($b['revenue_month']) : '—' ?>
                                </td>
                                <td>
                                    <span class="status-badge" style="background:<?= $sBg ?>;color:<?= $sColor ?>;">
                                        <?= $sLabel ?>
                                    </span>
                                </td>
                                <td style="text-align:right;">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="?page=branch_detail&id=<?= $b['id'] ?>" class="icon-btn-circle"
                                            title="Detay" style="width:28px;height:28px;font-size:.8rem;">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="?page=branch_edit&id=<?= $b['id'] ?>" class="icon-btn-circle"
                                            title="Düzenle" style="width:28px;height:28px;font-size:.8rem;">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if ($b['status'] === 'pending'): ?>
                                            <button class="icon-btn-circle" title="Onayla"
                                                onclick="approveBranch(<?= $b['id'] ?>, '<?= htmlspecialchars($b['name']) ?>')"
                                                style="width:28px;height:28px;font-size:.8rem;background:#e7f9f0;border-color:#0e8045;">
                                                <i class="bi bi-check-lg" style="color:#0e8045;"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button class="icon-btn-circle" title="Askıya Al / Aktif Et"
                                            onclick="toggleStatus(<?= $b['id'] ?>, '<?= $b['status'] ?>')"
                                            style="width:28px;height:28px;font-size:.8rem;background:#fff0f2;border-color:#c03060;">
                                            <i class="bi bi-<?= $b['status'] === 'suspended' ? 'play' : 'pause' ?>-circle"
                                                style="color:#c03060;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tablo altı bilgi -->
            <div class="d-flex align-items-center justify-content-between px-3 py-3 flex-wrap gap-2"
                style="border-top:1px solid var(--border-color);font-size:.78rem;color:var(--text-muted);">
                <span id="rowCountInfo">
                    <?= count($branches) ?> şube listelendi
                </span>
                <span>Sayfa 1 / 1</span>
            </div>
        </div>

    </div>

    <!-- ══ Toast ══ -->
    <div id="adminToast" style="
    position:fixed;bottom:24px;right:24px;z-index:9999;
    padding:12px 20px;border-radius:6px;font-size:.82rem;font-weight:600;
    background:#1b84ff;color:#fff;opacity:0;transition:opacity .3s;pointer-events:none;">
    </div>

    <script>
        /* ── Toast ── */
        function showToast(msg, type) {
            var t = document.getElementById('adminToast');
            var colors = { success: '#0e8045', error: '#c03060', info: '#1b84ff' };
            t.style.background = colors[type] || colors.info;
            t.textContent = msg;
            t.style.opacity = '1';
            setTimeout(function () { t.style.opacity = '0'; }, 3000);
        }

        /* ── Filtre & Arama ── */
        function filterBranches() {
            var q = document.getElementById('branchSearch').value.trim().toLowerCase();
            var type = document.getElementById('filterType').value;
            var status = document.getElementById('filterStatus').value;
            var sortBy = document.getElementById('sortBy').value;

            var rows = Array.from(document.querySelectorAll('.branch-row'));
            var visible = [];

            rows.forEach(function (r) {
                var nameMatch = !q || r.dataset.name.includes(q)
                    || r.dataset.city.includes(q)
                    || r.dataset.manager.includes(q);
                var typeMatch = !type || r.dataset.type === type;
                var statusMatch = !status || r.dataset.status === status;
                var show = nameMatch && typeMatch && statusMatch;
                r.style.display = show ? '' : 'none';
                if (show) visible.push(r);
            });

            /* Sıralama */
            var tbody = document.getElementById('branchesBody');
            visible.sort(function (a, b) {
                if (sortBy === 'cargo') return parseInt(b.dataset.cargo) - parseInt(a.dataset.cargo);
                if (sortBy === 'revenue') return parseInt(b.dataset.revenue) - parseInt(a.dataset.revenue);
                if (sortBy === 'name') return a.dataset.name.localeCompare(b.dataset.name);
                return 0;
            });
            visible.forEach(function (r) { tbody.appendChild(r); });

            document.getElementById('branchCount').textContent = visible.length + ' şube gösteriliyor';
            document.getElementById('rowCountInfo').textContent = visible.length + ' şube listelendi';
        }

        /* ── Onayla ── */
        function approveBranch(id, name) {
            if (!confirm(name + ' şubesini onaylamak istiyor musunuz?')) return;
            showToast('✓ ' + name + ' onaylandı.', 'success');
            /* TODO: AJAX */
        }

        /* ── Askıya Al / Aktif Et ── */
        function toggleStatus(id, currentStatus) {
            var msg = currentStatus === 'suspended' ? 'Şubeyi aktif etmek istiyor musunuz?' : 'Şubeyi askıya almak istiyor musunuz?';
            if (!confirm(msg)) return;
            showToast(currentStatus === 'suspended' ? '✓ Şube aktif edildi.' : '✓ Şube askıya alındı.', 'success');
            /* TODO: AJAX */
        }

        /* ── Dışa Aktar ── */
        function exportBranches() {
            showToast('Dışa aktarma başlatıldı...', 'info');
        }
    </script>