<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseModel.php';

class AuditModel extends BaseModel
{
    protected string $table      = 'audit_logs';
    protected string $primaryKey = 'log_id';
    protected bool   $softDelete = false; // Audit logs are never soft-deleted

    /** Log an audit event */
    public static function log(
        string $action,
        ?string $entityType = null,
        ?int    $entityId   = null,
        ?array  $oldValue   = null,
        ?array  $newValue   = null
    ): void {
        $model = new self();
        $model->create([
            'user_id'     => $_SESSION['user_id'] ?? null,
            'action'      => $action,
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'old_value'   => $oldValue ? json_encode($oldValue) : null,
            'new_value'   => $newValue ? json_encode($newValue) : null,
            'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent'  => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
            'branch_id'   => $_SESSION['branch_id'] ?? null,
        ]);
    }

    /** Get recent logs */
    public function getRecent(int $limit = 50, int $branchId = 0): array
    {
        $sql    = "SELECT al.*, u.full_name AS user_name
                   FROM audit_logs al
                   LEFT JOIN users u ON al.user_id = u.user_id
                   WHERE 1=1";
        $params = [];

        if ($branchId > 0) {
            $sql    .= " AND al.branch_id = ?";
            $params[] = $branchId;
        }

        $sql .= " ORDER BY al.created_at DESC LIMIT {$limit}";
        return $this->query($sql, $params);
    }
}
