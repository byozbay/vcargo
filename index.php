<?php
// ── Session ──────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── Login page (public) ──────────────────────────────────────────
$page = $_GET['page'] ?? 'dashboard';

if ($page === 'login') {
    include 'views/shared/login.php';
    exit;
}

// ── Auth Guard — redirect to login if not authenticated ──────────
if (empty($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">

<?php include 'views/layout/header.php'; ?>

<body>

    <!-- ══════════════════ SIDEBAR ══════════════════ -->
    <?php include 'views/layout/sidebar.php'; ?>

    <!-- ══════════════════ TOPBAR ══════════════════ -->
    <?php include 'views/layout/navbar.php'; ?>

    <!-- ══════════════════ MAIN CONTENT ══════════════════ -->
    <?php include 'core/router.php'; ?>

    <!-- Bootstrap JS -->
    <?php include 'views/layout/script.php'; ?>
</body>

</html>