<?php

// ── Logout ───────────────────────────────────────────────────────
if (isset($_GET['page']) && $_GET['page'] === 'logout') {
    // Invalidate session completely
    $_SESSION = [];

    // Delete session cookie
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();

    // JS redirect — headers already sent by the layout wrapper
    echo '<script>window.location.replace("?page=login");</script>';
    exit;
}

// ── Route guard (path traversal protection) ───────────────────────
$page = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['page'] ?? 'dashboard');

// ── Read role from session (no more hardcoding) ───────────────────
$user_role = $_SESSION['user_role'] ?? 'branch_staff';

// ── View resolution ───────────────────────────────────────────────
$roleViewMap = [
    'admin'          => 'views/admin/',
    'region_manager' => 'views/region_manager/',
    'branch_manager' => 'views/branch_staff/',   // branch_manager shares branch_staff views
    'branch_staff'   => 'views/branch_staff/',
    'accountant'     => 'views/admin/',           // accountant uses admin views (finance subset)
    'courier'        => 'views/branch_staff/',    // courier uses branch_staff views
];

$viewDir  = $roleViewMap[$user_role] ?? 'views/branch_staff/';
$viewPath = $viewDir . $page . '.php';

if (file_exists($viewPath)) {
    include $viewPath;
} else {
    // Fallback 1: try admin views (shared pages like settings, reports)
    $adminFallback = "views/admin/{$page}.php";
    if (file_exists($adminFallback)) {
        include $adminFallback;
    }
    // Fallback 2: try branch_staff views
    elseif (file_exists("views/branch_staff/{$page}.php")) {
        include "views/branch_staff/{$page}.php";
    } else {
        echo '<div style="padding:40px;text-align:center;color:#c03060;">
              Sayfa bulunamadı: <strong>' . htmlspecialchars($page) . '</strong>
              </div>';
    }
}
