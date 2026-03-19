<?php
// Read role from session (set during login)
$user_role = $_SESSION['user_role'] ?? 'branch_staff';
// Detect current page for active states
$currentPage = $_GET['page'] ?? 'dashboard';

// Determine which parent sections should be open
$kargoPages = ['shipment', 'shipment_create', 'shipment_track', 'dispatch', 'delivery'];
$emanetPages = ['storage', 'storage_create'];
$seferPages = ['voyage', 'voyage_create'];
$finansPages = ['vault', 'accounts', 'accounts_create'];

$kargoOpen = in_array($currentPage, $kargoPages);
$emanetOpen = in_array($currentPage, $emanetPages);
$seferOpen = in_array($currentPage, $seferPages);
$finansOpen = in_array($currentPage, $finansPages);
?>

<!-- ══════════════════ SIDEBAR ══════════════════ -->
<aside class="sidebar">

    <!-- Logo -->
    <a href="?page=dashboard" class="sidebar-logo">
        <div class="icon">
            <i class="bi bi-truck-front-fill text-secondary"></i>
        </div>
        <span style="color:var(--text-dark);font-weight:700;">vCargo</span>
    </a>
    <?php if ($user_role == "branch_staff") { ?>
        <!-- ── ANA MENÜ ── -->
        <div class="sidebar-section">Ana Menü</div>
        <ul class="sidebar-nav">
            <li>
                <a href="?page=dashboard" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
        </ul>

        <!-- ── OPERASYON ── -->
        <div class="sidebar-section">Operasyon</div>
        <ul class="sidebar-nav">

            <!-- Kargo İşlemleri -->
            <li class="<?= $kargoOpen ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="has-sub-toggle <?= $kargoOpen ? 'active' : '' ?>"
                    onclick="toggleSub(this)">
                    <i class="bi bi-box-seam"></i>
                    <span style="flex:1;">Kargo İşlemleri</span>
                    <i class="bi bi-chevron-down sub-arrow"
                        style="font-size:.7rem;transition:transform .2s;<?= $kargoOpen ? 'transform:rotate(180deg)' : '' ?>"></i>
                </a>
                <ul class="sub-nav" style="<?= $kargoOpen ? 'display:block;' : 'display:none;' ?>">
                    <li><a href="?page=shipment" class="<?= $currentPage === 'shipment' ? 'active' : '' ?>">Kargo
                            Listesi</a></li>
                    <li><a href="?page=shipment_create"
                            class="<?= $currentPage === 'shipment_create' ? 'active' : '' ?>">Yeni Kargo Kabul</a></li>
                    <li><a href="?page=shipment_track"
                            class="<?= $currentPage === 'shipment_track' ? 'active' : '' ?>">Kargo Sorgula</a></li>
                    <li><a href="?page=dispatch" class="<?= $currentPage === 'dispatch' ? 'active' : '' ?>">Kargo
                            Sevk</a></li>
                    <li><a href="?page=delivery" class="<?= $currentPage === 'delivery' ? 'active' : '' ?>">Kargo
                            Teslim</a></li>
                </ul>
            </li>

            <!-- Emanet & Depo -->
            <li class="<?= $emanetOpen ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="has-sub-toggle <?= $emanetOpen ? 'active' : '' ?>"
                    onclick="toggleSub(this)">
                    <i class="bi bi-archive"></i>
                    <span style="flex:1;">Emanet & Depo</span>
                    <i class="bi bi-chevron-down sub-arrow"
                        style="font-size:.7rem;transition:transform .2s;<?= $emanetOpen ? 'transform:rotate(180deg)' : '' ?>"></i>
                </a>
                <ul class="sub-nav" style="<?= $emanetOpen ? 'display:block;' : 'display:none;' ?>">
                    <li><a href="?page=storage" class="<?= $currentPage === 'storage' ? 'active' : '' ?>">Emanet Listesi</a>
                    </li>
                    <li><a href="?page=storage_create"
                            class="<?= $currentPage === 'storage_create' ? 'active' : '' ?>">Yeni Emanet Kaydı</a></li>
                </ul>
            </li>

            <!-- Sefer -->
            <li class="<?= $seferOpen ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="has-sub-toggle <?= $seferOpen ? 'active' : '' ?>"
                    onclick=" toggleSub(this)">
                    <i class="bi bi-bus-front"></i>
                    <span style="flex:1;">Sefer</span>
                    <i class="bi bi-chevron-down sub-arrow"
                        style="font-size:.7rem;transition:transform .2s;<?= $seferOpen ? 'transform:rotate(180deg)' : '' ?>"></i>
                </a>
                <ul class="sub-nav" style="<?= $seferOpen ? 'display:block;' : 'display:none;' ?>">
                    <li><a href="?page=voyage" class="<?= $currentPage === 'voyage' ? 'active' : '' ?>">Seferler</a></li>
                    <li><a href="?page=voyage_create" class="<?= $currentPage === 'voyage_create' ? 'active' : '' ?>">Yeni
                            Sefer</a></li>
                </ul>
            </li>

        </ul>

        <!-- ── FİNANS ── -->
        <div class="sidebar-section">Finans</div>
        <ul class="sidebar-nav">
            <li class="<?= $finansOpen ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="has-sub-toggle <?= $finansOpen ? 'active' : '' ?>"
                    onclick="toggleSub(this)">
                    <i class="bi bi-wallet2"></i>
                    <span style="flex:1;">Finans & Kasa</span>
                    <i class="bi bi-chevron-down sub-arrow"
                        style="font-size:.7rem;transition:transform .2s;<?= $finansOpen ? 'transform:rotate(180deg)' : '' ?>"></i>
                </a>
                <ul class="sub-nav" style="<?= $finansOpen ? 'display:block;' : 'display:none;' ?>">
                    <li><a href="?page=vault" class="<?= $currentPage === 'vault' ? 'active' : '' ?>">Kasa</a></li>
                    <li><a href="?page=accounts" class="<?= $currentPage === 'accounts' ? 'active' : '' ?>">Cari
                            Hesaplar</a></li>
                    <li><a href="?page=accounts_create"
                            class="<?= $currentPage === 'accounts_create' ? 'active' : '' ?>">Yeni Cari Hesap
                        </a></li>
                </ul>
            </li>
        </ul>
    <?php } ?>

    <?php if ($user_role === 'admin') { ?>
        <!-- ══ ADMIN MENU ══ -->

        <!-- Ana Menü -->
        <div class="sidebar-section">Ana Menü</div>
        <ul class="sidebar-nav">
            <li>
                <a href="?page=dashboard" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="?page=reports" class="<?= $currentPage === 'reports' ? 'active' : '' ?>">
                    <i class="bi bi-bar-chart-line"></i> Raporlar
                </a>
            </li>
        </ul>

        <!-- Sistem Yönetimi -->
        <?php
        $adminPages = ['branches', 'branch_create', 'users', 'user_create', 'bus_companies', 'pricing', 'settings'];
        $adminOpen = in_array($currentPage, $adminPages);
        ?>
        <div class="sidebar-section">Sistem Yönetimi</div>
        <ul class="sidebar-nav">

            <!-- Şube Yönetimi -->
            <?php $branchOpen = in_array($currentPage, ['branches', 'branch_create']); ?>
            <li class="<?= $branchOpen ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="has-sub-toggle <?= $branchOpen ? 'active' : '' ?>"
                    onclick="toggleSub(this)">
                    <i class="bi bi-shop"></i>
                    <span style="flex:1;">Şube Yönetimi</span>
                    <i class="bi bi-chevron-down sub-arrow"
                        style="font-size:.7rem;transition:transform .2s;<?= $branchOpen ? 'transform:rotate(180deg)' : '' ?>"></i>
                </a>
                <ul class="sub-nav" style="<?= $branchOpen ? 'display:block;' : 'display:none;' ?>">
                    <li><a href="?page=branches" class="<?= $currentPage === 'branches' ? 'active' : '' ?>">Şube
                            Listesi</a></li>
                    <li><a href="?page=branch_create" class="<?= $currentPage === 'branch_create' ? 'active' : '' ?>">Yeni
                            Şube</a></li>
                </ul>
            </li>

            <!-- Kullanıcı Yönetimi -->
            <?php $userOpen = in_array($currentPage, ['users', 'user_create']); ?>
            <li class="<?= $userOpen ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="has-sub-toggle <?= $userOpen ? 'active' : '' ?>"
                    onclick="toggleSub(this)">
                    <i class="bi bi-people"></i>
                    <span style="flex:1;">Kullanıcı Yönetimi</span>
                    <i class="bi bi-chevron-down sub-arrow"
                        style="font-size:.7rem;transition:transform .2s;<?= $userOpen ? 'transform:rotate(180deg)' : '' ?>"></i>
                </a>
                <ul class="sub-nav" style="<?= $userOpen ? 'display:block;' : 'display:none;' ?>">
                    <li><a href="?page=users" class="<?= $currentPage === 'users' ? 'active' : '' ?>">Kullanıcı
                            Listesi</a></li>
                    <li><a href="?page=user_create" class="<?= $currentPage === 'user_create' ? 'active' : '' ?>">Yeni
                            Kullanıcı</a></li>
                </ul>
            </li>

            <!-- Otobüs Firmaları -->
            <li>
                <a href="?page=bus_companies" class="<?= $currentPage === 'bus_companies' ? 'active' : '' ?>">
                    <i class="bi bi-bus-front"></i> Otobüs Firmaları
                </a>
            </li>

            <!-- Fiyatlandırma -->
            <li>
                <a href="?page=pricing" class="<?= $currentPage === 'pricing' ? 'active' : '' ?>">
                    <i class="bi bi-tags"></i> Fiyatlandırma
                </a>
            </li>

            <!-- Ayarlar -->
            <li>
                <a href="?page=settings" class="<?= $currentPage === 'settings' ? 'active' : '' ?>">
                    <i class="bi bi-gear"></i> Sistem Ayarları
                </a>
            </li>
        </ul>

        <!-- Operasyon (Admin görünümü — tüm şubeler) -->
        <div class="sidebar-section">Operasyon</div>
        <ul class="sidebar-nav">
            <li>
                <a href="?page=shipment" class="<?= $currentPage === 'shipment' ? 'active' : '' ?>">
                    <i class="bi bi-box-seam"></i> Tüm Kargolar
                </a>
            </li>
            <li>
                <a href="?page=voyage" class="<?= $currentPage === 'voyage' ? 'active' : '' ?>">
                    <i class="bi bi-bus-front"></i> Tüm Seferler
                </a>
            </li>
            <li>
                <a href="?page=storage" class="<?= $currentPage === 'storage' ? 'active' : '' ?>">
                    <i class="bi bi-archive"></i> Tüm Emanetler
                </a>
            </li>
        </ul>

        <!-- Finans -->
        <div class="sidebar-section">Finans & Muhasebe</div>
        <ul class="sidebar-nav">
            <?php $adminFinOpen = in_array($currentPage, ['vault', 'accounts', 'accounts_create', 'transactions', 'audit_log']); ?>
            <li class="<?= $adminFinOpen ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="has-sub-toggle <?= $adminFinOpen ? 'active' : '' ?>"
                    onclick="toggleSub(this)">
                    <i class="bi bi-wallet2"></i>
                    <span style="flex:1;">Finans & Kasa</span>
                    <i class="bi bi-chevron-down sub-arrow"
                        style="font-size:.7rem;transition:transform .2s;<?= $adminFinOpen ? 'transform:rotate(180deg)' : '' ?>"></i>
                </a>
                <ul class="sub-nav" style="<?= $adminFinOpen ? 'display:block;' : 'display:none;' ?>">
                    <li><a href="?page=vault" class="<?= $currentPage === 'vault' ? 'active' : '' ?>">Kasa
                            Özeti</a></li>

                    <li><a href="?page=accounts" class="<?= $currentPage === 'accounts' ? 'active' : '' ?>">Cari
                            Hesaplar</a></li>
                    <li><a href="?page=accounts_create"
                            class="<?= $currentPage === 'accounts_create' ? 'active' : '' ?>">Yeni Cari</a></li>

                </ul>
            </li>
        </ul>

    <?php } /* end admin */ ?>

    <?php if ($user_role === 'region_manager') { ?>
        <!-- ══ BÖLGE MÜDÜRÜ MENU ══ -->

        <!-- Ana Menü -->
        <div class="sidebar-section">Ana Menü</div>
        <ul class="sidebar-nav">
            <li>
                <a href="?page=dashboard" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i> Bölge Dashboard
                </a>
            </li>
            <li>
                <a href="?page=reports" class="<?= $currentPage === 'reports' ? 'active' : '' ?>">
                    <i class="bi bi-bar-chart-line"></i> Bölge Raporları
                </a>
            </li>
        </ul>

        <!-- Şube Denetimi -->
        <div class="sidebar-section">Şube Denetimi</div>
        <ul class="sidebar-nav">
            <li>
                <a href="?page=branches" class="<?= $currentPage === 'branches' ? 'active' : '' ?>">
                    <i class="bi bi-shop"></i> Bölge Şubeleri
                </a>
            </li>
            <li>
                <a href="?page=shipment" class="<?= $currentPage === 'shipment' ? 'active' : '' ?>">
                    <i class="bi bi-box-seam"></i> Kargo Takibi
                </a>
            </li>
            <li>
                <a href="?page=voyage" class="<?= $currentPage === 'voyage' ? 'active' : '' ?>">
                    <i class="bi bi-bus-front"></i> Sefer Takibi
                </a>
            </li>
            <li>
                <a href="?page=storage" class="<?= $currentPage === 'storage' ? 'active' : '' ?>">
                    <i class="bi bi-archive"></i> Emanet Takibi
                </a>
            </li>
        </ul>

        <!-- Fiyatlandırma -->
        <div class="sidebar-section">Bölge Ayarları</div>
        <ul class="sidebar-nav">
            <li>
                <a href="?page=pricing" class="<?= $currentPage === 'pricing' ? 'active' : '' ?>">
                    <i class="bi bi-tags"></i> Bölge Fiyatları
                </a>
            </li>
            <li>
                <a href="?page=bus_companies" class="<?= $currentPage === 'bus_companies' ? 'active' : '' ?>">
                    <i class="bi bi-bus-front"></i> Otobüs Firmaları
                </a>
            </li>
        </ul>

        <!-- Finans -->
        <div class="sidebar-section">Finans</div>
        <ul class="sidebar-nav">
            <?php $rmFinOpen = in_array($currentPage, ['vault', 'accounts', 'transactions']); ?>
            <li class="<?= $rmFinOpen ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="has-sub-toggle <?= $rmFinOpen ? 'active' : '' ?>"
                    onclick="toggleSub(this)">
                    <i class="bi bi-wallet2"></i>
                    <span style="flex:1;">Finans & Kasa</span>
                    <i class="bi bi-chevron-down sub-arrow"
                        style="font-size:.7rem;transition:transform .2s;<?= $rmFinOpen ? 'transform:rotate(180deg)' : '' ?>"></i>
                </a>
                <ul class="sub-nav" style="<?= $rmFinOpen ? 'display:block;' : 'display:none;' ?>">
                    <li><a href="?page=vault" class="<?= $currentPage === 'vault' ? 'active' : '' ?>">Bölge Kasa
                            Özeti</a></li>
                    <li><a href="?page=transactions" class="<?= $currentPage === 'transactions' ? 'active' : '' ?>">İşlem
                            Hareketleri</a></li>
                    <li><a href="?page=accounts" class="<?= $currentPage === 'accounts' ? 'active' : '' ?>">Cari
                            Hesaplar</a></li>
                </ul>
            </li>
        </ul>

    <?php } /* end region_manager */ ?>

    <!-- ── USER FOOTER ── -->
    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2">
            <div
                style="width:34px;height:34px;border-radius:50%;background:var(--accent-blue);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-person-fill" style="color:#fff;font-size:.9rem;"></i>
            </div>
            <div style="overflow:hidden;flex:1;">
                <div
                    style="font-size:.78rem;font-weight:600;color:var(--text-dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    <?= htmlspecialchars($_SESSION['user_name'] ?? 'Kullanıcı') ?>
                </div>
                <div style="font-size:.71rem;color:var(--text-muted);">
                    <?php
                    $roleLabels = [
                        'branch_staff' => 'Şube Personeli',
                        'admin' => 'Süper Admin',
                        'region_manager' => 'Bölge Müdürü',
                    ];
                    echo $roleLabels[$user_role] ?? 'Kullanıcı';
                    ?>
                </div>
            </div>
            <a href="?page=logout" onclick="return confirm('Sistemden çıkış yapmak istiyor musunuz?')"
                style="color:var(--text-muted);font-size:.95rem;" title="Güvenli Çıkış Yap">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>

</aside>


<script>
    function toggleSub(el) {
        const li = el.closest('li');
        const sub = li.querySelector('.sub-nav');
        const arr = el.querySelector('.sub-arrow');
        const isOpen = li.classList.contains('open');

        // Close all siblings first (accordion behaviour)
        el.closest('ul').querySelectorAll('li.open').forEach(openLi => {
            openLi.classList.remove('open');
            const s = openLi.querySelector('.sub-nav');
            const a = openLi.querySelector('.sub-arrow');
            if (s) s.style.display = 'none';
            if (a) a.style.transform = '';
        });

        if (!isOpen) {
            li.classList.add('open');
            if (sub) sub.style.display = 'block';
            if (arr) arr.style.transform = 'rotate(180deg)';
        }
    }
</script>