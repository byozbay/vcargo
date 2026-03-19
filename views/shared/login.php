<?php
// ── Login Page ─────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, redirect
if (!empty($_SESSION['user_id'])) {
    header('Location: ?page=dashboard');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Kullanıcı adı ve şifre zorunludur.';
    } else {
        require_once __DIR__ . '/../../core/database.php';
        $db   = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id']   = $user['user_id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['branch_id'] = $user['branch_id'];

            // Update last_login
            $upd = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
            $upd->execute([$user['user_id']]);

            header('Location: ?page=dashboard');
            exit;
        } else {
            $error = 'Kullanıcı adı veya şifre hatalı.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vCargo — Giriş Yap</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f172a;
            color: #e2e8f0;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0;
            padding: 40px 32px;
        }

        .login-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 32px;
        }

        .login-logo i { font-size: 1.6rem; color: #1b84ff; }
        .login-logo span { font-size: 1.3rem; font-weight: 800; color: #e2e8f0; }

        .login-title {
            text-align: center;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #e2e8f0;
        }

        .login-subtitle {
            text-align: center;
            font-size: .8rem;
            color: #94a3b8;
            margin-bottom: 28px;
        }

        .form-group { margin-bottom: 18px; }

        .form-group label {
            display: block;
            font-size: .75rem;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #64748b;
            font-size: .9rem;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px 10px 38px;
            border: 1px solid #334155;
            border-radius: 0;
            background: #0f172a;
            color: #e2e8f0;
            font-size: .84rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }

        .form-control:focus {
            border-color: #1b84ff;
            box-shadow: 0 0 0 3px rgba(27, 132, 255, .15);
        }

        .form-control::placeholder { color: #475569; }

        .btn-login {
            width: 100%;
            padding: 11px;
            border: none;
            border-radius: 0;
            background: #1b84ff;
            color: #fff;
            font-size: .88rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: opacity .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover { opacity: .9; }

        .error-msg {
            background: rgba(192, 48, 96, .15);
            border: 1px solid #c03060;
            color: #f87171;
            padding: 10px 14px;
            font-size: .8rem;
            font-weight: 500;
            margin-bottom: 18px;
            border-radius: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            font-size: .72rem;
            color: #475569;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-logo">
                <i class="bi bi-truck-front-fill"></i>
                <span>vCargo</span>
            </div>

            <div class="login-title">Sisteme Giriş Yap</div>
            <div class="login-subtitle">Otogar Lojistik Yönetim Sistemi</div>

            <?php if ($error): ?>
                <div class="error-msg">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="?page=login">
                <div class="form-group">
                    <label>Kullanıcı Adı</label>
                    <div class="input-wrap">
                        <i class="bi bi-person"></i>
                        <input type="text" name="username" class="form-control"
                               placeholder="Kullanıcı adınızı giriniz"
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                               required autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label>Şifre</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password" class="form-control"
                               placeholder="Şifrenizi giriniz"
                               required autocomplete="current-password">
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Giriş Yap
                </button>
            </form>

            <div class="login-footer">
                &copy; <?= date('Y') ?> vCargo — Tüm hakları saklıdır.
            </div>
        </div>
    </div>
</body>
</html>