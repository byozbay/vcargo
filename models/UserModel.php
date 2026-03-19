<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseModel.php';

class UserModel extends BaseModel
{
    protected string $table      = 'users';
    protected string $primaryKey = 'user_id';

    /** Get users with branch name */
    public function getList(string $role = '', string $search = '', int $branchId = 0): array
    {
        $sql    = "SELECT u.user_id, u.username, u.full_name, u.email, u.phone, u.role, u.is_active,
                          u.created_at, u.last_login, b.name AS branch_name
                   FROM users u
                   LEFT JOIN branches b ON u.branch_id = b.branch_id
                   WHERE u.is_active = 1";
        $params = [];

        if ($role) {
            $sql    .= " AND u.role = ?";
            $params[] = $role;
        }
        if ($branchId > 0) {
            $sql    .= " AND u.branch_id = ?";
            $params[] = $branchId;
        }
        if ($search) {
            $sql    .= " AND (u.full_name LIKE ? OR u.username LIKE ? OR u.email LIKE ?)";
            $like     = "%{$search}%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        $sql .= " ORDER BY u.full_name";
        return $this->query($sql, $params);
    }

    /** Create user with hashed password */
    public function createUser(array $data): int
    {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);
        return $this->create($data);
    }

    /** Update user password */
    public function updatePassword(int $userId, string $newPassword): bool
    {
        return $this->update($userId, [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);
    }
}
