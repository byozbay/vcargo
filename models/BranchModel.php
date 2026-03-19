<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseModel.php';

class BranchModel extends BaseModel
{
    protected string $table      = 'branches';
    protected string $primaryKey = 'branch_id';

    /** Get all active branches with city name */
    public function getAllWithCity(): array
    {
        return $this->query(
            "SELECT b.*, c.name AS city_name, c.plate_code, r.name AS region_name
             FROM branches b
             LEFT JOIN cities c ON b.city_id = c.city_id
             LEFT JOIN regions r ON b.region_id = r.region_id
             WHERE b.is_active = 1
             ORDER BY b.name"
        );
    }

    /** Get branch stats (cargo count, revenue) for dashboard */
    public function getStats(int $branchId): array
    {
        $row = $this->query(
            "SELECT
                 (SELECT COUNT(*) FROM shipments WHERE branch_id = ? AND is_active = 1 AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())) AS monthly_cargo,
                 (SELECT COALESCE(SUM(total_fee), 0) FROM shipments WHERE branch_id = ? AND is_active = 1 AND payment_status = 'paid' AND MONTH(created_at) = MONTH(NOW())) AS monthly_revenue,
                 (SELECT COUNT(*) FROM storage_records WHERE branch_id = ? AND status = 'stored' AND is_active = 1) AS active_storage,
                 (SELECT COUNT(*) FROM users WHERE branch_id = ? AND is_active = 1) AS staff_count",
            [$branchId, $branchId, $branchId, $branchId]
        );
        return $row[0] ?? ['monthly_cargo' => 0, 'monthly_revenue' => 0, 'active_storage' => 0, 'staff_count' => 0];
    }

    /** Count by status */
    public function countByStatus(): array
    {
        return $this->query(
            "SELECT status, COUNT(*) as cnt FROM branches WHERE is_active = 1 GROUP BY status"
        );
    }
}
