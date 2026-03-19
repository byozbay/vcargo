<header class="topbar">
    <!-- Hamburger — mobile only -->
    <button class="btn-sidebar-toggle" id="sidebarToggle"
            style="background:none;border:none;cursor:pointer;padding:4px 6px;color:var(--text-dark);font-size:1.4rem;line-height:1;display:flex;align-items:center;">
        <i class="bi bi-list"></i>
    </button>

    <div class="topbar-search">
        <i class="bi bi-search" style="color:#a3aab8;font-size:.9rem;"></i>
        <input type="text" placeholder="Ara..." />
    </div>

    <div class="topbar-actions">
        <button class="topbar-icon-btn" title="Bildirimler">
            <i class="bi bi-bell"></i>
            <span class="badge-dot"></span>
        </button>
        <button class="topbar-icon-btn" title="Mesajlar">
            <i class="bi bi-chat-dots"></i>
            <span class="badge-count">8</span>
        </button>
        <button class="topbar-icon-btn" title="Kullanıcılar">
            <i class="bi bi-people"></i>
        </button>

        <!-- Dark / Light Mode Toggle -->
        <button class="theme-toggle" id="themeToggle" title="Tema Değiştir">
            <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
        </button>
    </div>
</header>

<!-- Mobile sidebar overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<script>
    (function () {
        var toggle = document.getElementById('sidebarToggle');
        var sidebar = document.querySelector('.sidebar');
        var overlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.add('sidebar-open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar.classList.remove('sidebar-open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        if (toggle) toggle.addEventListener('click', openSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);
    })();
</script>