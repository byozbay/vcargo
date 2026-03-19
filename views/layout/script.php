<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ─── DARK / LIGHT MODE TOGGLE ─── */
(function () {
    var toggleBtn = document.getElementById('themeToggle');
    var themeIcon = document.getElementById('themeIcon');

    function applyTheme(dark) {
        if (dark) {
            document.body.classList.add('dark');
            if (themeIcon) themeIcon.className = 'bi bi-brightness-high-fill';
        } else {
            document.body.classList.remove('dark');
            if (themeIcon) themeIcon.className = 'bi bi-moon-stars-fill';
        }
    }

    /* Restore saved preference on every page load */
    applyTheme(localStorage.getItem('theme') === 'dark');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            var isDark = document.body.classList.contains('dark');
            applyTheme(!isDark);
            localStorage.setItem('theme', !isDark ? 'dark' : 'light');
        });
    }
})();
</script>