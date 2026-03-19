<?php
declare(strict_types=1);

class AuthController
{
    /**
     * Authenticate user with username and password.
     * Uses password_verify() against the stored password_hash column.
     */
    public function login(string $username, string $password): bool
    {
        $db   = new Database();
        $conn = $db->connect();

        // Fetch only active users — match DB column name: password_hash
        $stmt = $conn->prepare(
            "SELECT user_id, username, password_hash, full_name, role, branch_id
             FROM users
             WHERE username = ? AND is_active = 1
             LIMIT 1"
        );
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id']   = $user['user_id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['branch_id'] = $user['branch_id'];

            // Update last_login timestamp
            $upd = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
            $upd->execute([$user['user_id']]);

            header("Location: ?page=dashboard");
            exit;
        }

        return false;
    }

    /**
     * Destroy session and redirect to login page.
     */
    public function logout(): void
    {
        $_SESSION = [];

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
        header("Location: ?page=login");
        exit;
    }
}
