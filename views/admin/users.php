<?php
$todayDate = date('d.m.Y');

$users = [
    ['id' => 1, 'name' => 'Mehmet Kara', 'email' => 'm.kara@vcargo.com.tr', 'role' => 'branch_manager', 'branch' => 'İstanbul Otogar', 'city' => 'İstanbul', 'phone' => '0532 111 0001', 'last_login' => '5 dk önce', 'status' => 'active'],
    ['id' => 2, 'name' => 'Ayşe Yıldız', 'email' => 'a.yildiz@vcargo.com.tr', 'role' => 'branch_staff', 'branch' => 'Ankara Şehirler', 'city' => 'Ankara', 'phone' => '0533 222 0002', 'last_login' => '32 dk önce', 'status' => 'active'],
    ['id' => 3, 'name' => 'Hasan Demir', 'email' => 'h.demir@vcargo.com.tr', 'role' => 'region_manager', 'branch' => 'Ege Bölgesi', 'city' => 'İzmir', 'phone' => '0534 333 0003', 'last_login' => '2 sa önce', 'status' => 'active'],
    ['id' => 4, 'name' => 'Caner Güneş', 'email' => 'c.gunes@vcargo.com.tr', 'role' => 'branch_staff', 'branch' => 'Bursa Osmangazi', 'city' => 'Bursa', 'phone' => '0535 444 0004', 'last_login' => 'Dün', 'status' => 'active'],
    ['id' => 5, 'name' => 'Selin Çelik', 'email' => 's.celik@vcargo.com.tr', 'role' => 'branch_manager', 'branch' => 'Antalya Liman', 'city' => 'Antalya', 'phone' => '0536 555 0005', 'last_login' => 'Dün', 'status' => 'active'],
    ['id' => 6, 'name' => 'Ali Şahin', 'email' => 'a.sahin@vcargo.com.tr', 'role' => 'branch_staff', 'branch' => 'Konya Merkez', 'city' => 'Konya', 'phone' => '0537 666 0006', 'last_login' => '3 gün önce', 'status' => 'active'],
    ['id' => 7, 'name' => 'Fatma Arslan', 'email' => 'f.arslan@vcargo.com.tr', 'role' => 'branch_manager', 'branch' => 'Adana Seyhan', 'city' => 'Adana', 'phone' => '0538 777 0007', 'last_login' => '1 hafta önce', 'status' => 'active'],
    ['id' => 8, 'name' => 'Kemal Öztürk', 'email' => 'k.ozturk@vcargo.com.tr', 'role' => 'accountant', 'branch' => 'Merkez', 'city' => 'İstanbul', 'phone' => '0539 888 0008', 'last_login' => '1 sa önce', 'status' => 'active'],
    ['id' => 9, 'name' => 'Zeynep Ok', 'email' => 'z.ok@vcargo.com.tr', 'role' => 'branch_staff', 'branch' => 'Trabzon Sahil', 'city' => 'Trabzon', 'phone' => '0540 999 0009', 'last_login' => '2 gün önce', 'status' => 'passive'],
    ['id' => 10, 'name' => 'Kadir Bal', 'email' => 'k.bal@vcargo.com.tr', 'role' => 'branch_staff', 'branch' => 'Kayseri Terminal', 'city' => 'Kayseri', 'phone' => '0541 100 0010', 'last_login' => '3 hafta önce', 'status' => 'suspended'],
    ['id' => 11, 'name' => 'Nurgül Aydın', 'email' => 'n.aydin@vcargo.com.tr', 'role' => 'courier', 'branch' => 'İstanbul Otogar', 'city' => 'İstanbul', 'phone' => '0542 200 0011', 'last_login' => '10 dk önce', 'status' => 'active'],
    ['id' => 12, 'name' => 'Burak Koç', 'email' => 'b.koc@vcargo.com.tr', 'role' => 'admin', 'branch' => 'Sistem', 'city' => '—', 'phone' => '0543 300 0012', 'last_login' => 'Şu an', 'status' => 'active'],
];

$roleLabels = [
    'admin' => ['Süper Admin', '#c03060', '#fff0f2'],
    'region_manager' => ['Bölge Müdürü', '#8e24aa', '#f3e5f5'],
    'branch_manager' => ['Şube Müdürü', '#1b84ff', '#e8f1ff'],
    'branch_staff' => ['Personel', '#0e8045', '#e7f9f0'],
    'accountant' => ['Muhasebe', '#e08b00', '#fff8ec'],
    'courier' => ['Kurye', '#78909c', '#f1f3f4'],
];

$totalActive = count(array_filter($users, fn($u) => $u['status'] === 'active'));
$totalPassive = count(array_filter($users, fn($u) => $u['status'] !== 'active'));
?>
<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Kullanıcı Yönetimi</div>
            <div class="breadcrumb">
                <a href="?page=dashboard" style="color:var(--text-muted);">Dashboard</a>
                <span class="sep">·</span>
                <span style="color:var(--text-muted);">Kullanıcılar</span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn-outline-secondary-sm d-flex align-items-center gap-1" onclick="exportUsers()">
                <i class="bi bi-download"></i> Dışa Aktar
            </button>
            <a href="?page=user_create" class="btn-primary-sm d-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i> Yeni Kullanıcı
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
                            <div class="card-sm-label">Toplam Kullanıcı</div>
                            <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">
                                <?= count($users) ?>
                            </div>
                            <span class="badge-change badge-up mt-2">&#8593; 4 yeni bu ay</span>
                        </div>
                        <div
                            style="width:42px;height:42px;border-radius:8px;background:#e8f1ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-people" style="color:#1b84ff;font-size:1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100" style="padding:18px;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="card-sm-label">Aktif Kullanıcı</div>
                            <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">
                                <?= $totalActive ?>
                            </div>
                            <span class="badge-change badge-up mt-2">Sisteme giriş yapıyor</span>
                        </div>
                        <div
                            style="width:42px;height:42px;border-radius:8px;background:#e7f9f0;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-person-check" style="color:#0e8045;font-size:1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100" style="padding:18px;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="card-sm-label">Pasif / Askıda</div>
                            <div class="stat-value" style="font-size:1.8rem;margin-top:4px;color:#e08b00;">
                                <?= $totalPassive ?>
                            </div>
                            <span class="badge-change badge-down mt-2">Erişim kısıtlı</span>
                        </div>
                        <div
                            style="width:42px;height:42px;border-radius:8px;background:#fff8ec;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-person-dash" style="color:#e08b00;font-size:1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100" style="padding:18px;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="card-sm-label">Şube Sayısı</div>
                            <div class="stat-value" style="font-size:1.8rem;margin-top:4px;">18</div>
                            <span class="badge-change badge-up mt-2">Kullanıcı bağlı</span>
                        </div>
                        <div
                            style="width:42px;height:42px;border-radius:8px;background:#f3e5f5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-shop" style="color:#8e24aa;font-size:1.2rem;"></i>
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
                        <input type="text" id="userSearch" class="form-input" style="padding-left:30px;"
                            placeholder="Ad, e-posta veya şube ara..." oninput="filterUsers()">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-input" id="filterRole" onchange="filterUsers()">
                        <option value="">Tüm Roller</option>
                        <option value="admin">Süper Admin</option>
                        <option value="region_manager">Bölge Müdürü</option>
                        <option value="branch_manager">Şube Müdürü</option>
                        <option value="branch_staff">Personel</option>
                        <option value="accountant">Muhasebe</option>
                        <option value="courier">Kurye</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-input" id="filterStatus" onchange="filterUsers()">
                        <option value="">Tüm Durumlar</option>
                        <option value="active">Aktif</option>
                        <option value="passive">Pasif</option>
                        <option value="suspended">Askıya Alındı</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select class="form-input" id="sortBy" onchange="filterUsers()">
                        <option value="name">Ad (A→Z)</option>
                        <option value="role">Role Göre</option>
                        <option value="login">Son Giriş</option>
                    </select>
                </div>
                <div class="col-6 col-md-2 text-end">
                    <span id="userCount" style="font-size:.8rem;color:var(--text-muted);">
                        <?= count($users) ?> kullanıcı
                    </span>
                </div>
            </div>
        </div>

        <!-- ══ Kullanıcı Tablosu ══ -->
        <div class="card">
            <div class="table-responsive">
                <table class="orders-table" id="usersTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kullanıcı</th>
                            <th>Rol</th>
                            <th>Şube</th>
                            <th>Telefon</th>
                            <th>Son Giriş</th>
                            <th>Durum</th>
                            <th style="text-align:right;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody id="usersBody">
                        <?php foreach ($users as $u):
                            [$roleLabel, $roleColor, $roleBg] = $roleLabels[$u['role']] ?? ['—', '#78909c', '#f1f3f4'];

                            $statusMap = [
                                'active' => ['Aktif', '#e7f9f0', '#0e8045'],
                                'passive' => ['Pasif', '#f1f3f4', '#78909c'],
                                'suspended' => ['Askıya Alındı', '#fff0f2', '#c03060'],
                            ];
                            [$sLabel, $sBg, $sColor] = $statusMap[$u['status']];

                            // Avatar initials
                            $parts = explode(' ', $u['name']);
                            $initials = mb_strtoupper(mb_substr($parts[0], 0, 1) . (isset($parts[1]) ? mb_substr($parts[1], 0, 1) : ''));
                            $avatarColors = ['#1b84ff', '#0e8045', '#8e24aa', '#e08b00', '#c03060', '#00897b', '#546e7a', '#6d4c41'];
                            $avatarColor = $avatarColors[$u['id'] % count($avatarColors)];
                            ?>
                            <tr class="user-row" data-role="<?= $u['role'] ?>" data-status="<?= $u['status'] ?>"
                                data-name="<?= mb_strtolower($u['name']) ?>" data-email="<?= mb_strtolower($u['email']) ?>"
                                data-branch="<?= mb_strtolower($u['branch']) ?>">
                                <td style="font-size:.78rem;color:var(--text-muted);">
                                    <?= $u['id'] ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div
                                            style="width:34px;height:34px;border-radius:50%;background:<?= $avatarColor ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <span style="color:#fff;font-size:.7rem;font-weight:700;">
                                                <?= $initials ?>
                                            </span>
                                        </div>
                                        <div>
                                            <div style="font-size:.82rem;font-weight:600;">
                                                <?= htmlspecialchars($u['name']) ?>
                                            </div>
                                            <div style="font-size:.71rem;color:var(--text-muted);">
                                                <?= $u['email'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge" style="background:<?= $roleBg ?>;color:<?= $roleColor ?>;">
                                        <?= $roleLabel ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size:.81rem;">
                                        <?= $u['branch'] ?>
                                    </div>
                                    <div style="font-size:.71rem;color:var(--text-muted);">
                                        <?= $u['city'] ?>
                                    </div>
                                </td>
                                <td style="font-size:.78rem;color:var(--text-muted);">
                                    <?= $u['phone'] ?>
                                </td>
                                <td style="font-size:.78rem;color:var(--text-muted);">
                                    <?= $u['last_login'] ?>
                                </td>
                                <td>
                                    <span class="status-badge" style="background:<?= $sBg ?>;color:<?= $sColor ?>;">
                                        <?= $sLabel ?>
                                    </span>
                                </td>
                                <td style="text-align:right;">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="?page=user_edit&id=<?= $u['id'] ?>" class="icon-btn-circle" title="Düzenle"
                                            style="width:28px;height:28px;font-size:.8rem;">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="icon-btn-circle" title="Şifre Sıfırla"
                                            onclick="resetPassword(<?= $u['id'] ?>, '<?= htmlspecialchars($u['name']) ?>')"
                                            style="width:28px;height:28px;font-size:.8rem;">
                                            <i class="bi bi-key"></i>
                                        </button>
                                        <?php if ($u['status'] === 'active'): ?>
                                            <button class="icon-btn-circle" title="Pasife Al"
                                                onclick="toggleUserStatus(<?= $u['id'] ?>, 'active')"
                                                style="width:28px;height:28px;font-size:.8rem;background:#fff0f2;border-color:#c03060;">
                                                <i class="bi bi-person-dash" style="color:#c03060;"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="icon-btn-circle" title="Aktif Et"
                                                onclick="toggleUserStatus(<?= $u['id'] ?>, '<?= $u['status'] ?>')"
                                                style="width:28px;height:28px;font-size:.8rem;background:#e7f9f0;border-color:#0e8045;">
                                                <i class="bi bi-person-check" style="color:#0e8045;"></i>
                                            </button>
                                        <?php endif; ?>
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
                    <?= count($users) ?> kullanıcı listelendi
                </span>
                <span>Sayfa 1 / 1</span>
            </div>
        </div>

    </div>

    <!-- Toast -->
    <div id="usersToast" style="position:fixed;bottom:24px;right:24px;z-index:9999;padding:12px 20px;
     border-radius:6px;font-size:.82rem;font-weight:600;background:#1b84ff;color:#fff;
     opacity:0;transition:opacity .3s;pointer-events:none;"></div>

    <script>
        function showToast(msg, type) {
            var t = document.getElementById('usersToast');
            t.style.background = { success: '#0e8045', error: '#c03060', info: '#1b84ff' }[type] || '#1b84ff';
            t.textContent = msg;
            t.style.opacity = '1';
            setTimeout(function () { t.style.opacity = '0'; }, 3000);
        }

        function filterUsers() {
            var q = document.getElementById('userSearch').value.trim().toLowerCase();
            var role = document.getElementById('filterRole').value;
            var status = document.getElementById('filterStatus').value;
            var sortBy = document.getElementById('sortBy').value;

            var rows = Array.from(document.querySelectorAll('.user-row'));
            var visible = [];

            rows.forEach(function (r) {
                var match = (!q || r.dataset.name.includes(q) || r.dataset.email.includes(q) || r.dataset.branch.includes(q))
                    && (!role || r.dataset.role === role)
                    && (!status || r.dataset.status === status);
                r.style.display = match ? '' : 'none';
                if (match) visible.push(r);
            });

            // Sort
            var tbody = document.getElementById('usersBody');
            visible.sort(function (a, b) {
                if (sortBy === 'name') return a.dataset.name.localeCompare(b.dataset.name);
                if (sortBy === 'role') return a.dataset.role.localeCompare(b.dataset.role);
                return 0;
            });
            visible.forEach(function (r) { tbody.appendChild(r); });

            document.getElementById('userCount').textContent = visible.length + ' kullanıcı';
            document.getElementById('rowCountInfo').textContent = visible.length + ' kullanıcı listelendi';
        }

        function resetPassword(id, name) {
            if (!confirm(name + ' kullanıcısının şifresini sıfırlamak istiyor musunuz?')) return;
            showToast('✓ ' + name + ' şifresi sıfırlandı, e-posta gönderildi.', 'success');
        }

        function toggleUserStatus(id, currentStatus) {
            var msg = currentStatus === 'active' ? 'Kullanıcıyı pasife almak istiyor musunuz?' : 'Kullanıcıyı aktif etmek istiyor musunuz?';
            if (!confirm(msg)) return;
            showToast(currentStatus === 'active' ? '✓ Kullanıcı pasife alındı.' : '✓ Kullanıcı aktif edildi.', 'success');
        }

        function exportUsers() {
            showToast('Dışa aktarma başlatıldı...', 'info');
        }
    </script>
</main>