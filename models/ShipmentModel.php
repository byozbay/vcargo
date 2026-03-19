<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseModel.php';

class ShipmentModel extends BaseModel
{
    protected string $table      = 'shipments';
    protected string $primaryKey = 'shipment_id';

    /** Generate a unique tracking number: TRK-YYMMDD-XXX */
    public function generateTrackingNo(): string
    {
        $prefix = 'TRK';
        $date   = date('ymd');
        $row    = $this->query(
            "SELECT COUNT(*) as cnt FROM shipments WHERE DATE(created_at) = CURDATE()"
        );
        $seq = ($row[0]['cnt'] ?? 0) + 1;
        return sprintf('%s-%s-%03d', $prefix, $date, $seq);
    }

    /** Create a shipment with auto-generated tracking_no */
    public function createShipment(array $data): int
    {
        $data['tracking_no'] = $this->generateTrackingNo();
        $data['status']      = $data['status'] ?? 'accepted';
        return $this->create($data);
    }

    /** Get shipments list with filters */
    public function getList(int $branchId = 0, string $status = '', string $search = '', int $limit = 20, int $offset = 0): array
    {
        $sql    = "SELECT s.*, oc.name AS origin_city, dc.name AS dest_city, b.name AS branch_name
                   FROM shipments s
                   LEFT JOIN branches ob ON s.origin_branch_id = ob.branch_id
                   LEFT JOIN cities oc ON ob.city_id = oc.city_id
                   LEFT JOIN cities dc ON s.destination_city_id = dc.city_id
                   LEFT JOIN branches b ON s.branch_id = b.branch_id
                   WHERE s.is_active = 1";
        $params = [];

        if ($branchId > 0) {
            $sql    .= " AND s.branch_id = ?";
            $params[] = $branchId;
        }
        if ($status) {
            $sql    .= " AND s.status = ?";
            $params[] = $status;
        }
        if ($search) {
            $sql    .= " AND (s.tracking_no LIKE ? OR s.sender_name LIKE ? OR s.receiver_name LIKE ?)";
            $like     = "%{$search}%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        $sql .= " ORDER BY s.created_at DESC LIMIT {$limit} OFFSET {$offset}";

        return $this->query($sql, $params);
    }

    /** Count shipments with filters */
    public function countList(int $branchId = 0, string $status = '', string $search = ''): int
    {
        $sql    = "SELECT COUNT(*) as cnt FROM shipments s WHERE s.is_active = 1";
        $params = [];

        if ($branchId > 0) {
            $sql    .= " AND s.branch_id = ?";
            $params[] = $branchId;
        }
        if ($status) {
            $sql    .= " AND s.status = ?";
            $params[] = $status;
        }
        if ($search) {
            $sql    .= " AND (s.tracking_no LIKE ? OR s.sender_name LIKE ? OR s.receiver_name LIKE ?)";
            $like     = "%{$search}%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        $row = $this->query($sql, $params);
        return (int) ($row[0]['cnt'] ?? 0);
    }

    /** Get dashboard stats */
    public function getDashboardStats(int $branchId = 0): array
    {
        $where  = $branchId > 0 ? "AND branch_id = ?" : "";
        $params = $branchId > 0 ? [$branchId] : [];

        $sql = "SELECT
                    COUNT(*) AS total,
                    SUM(CASE WHEN status IN ('dispatched','in_transit') THEN 1 ELSE 0 END) AS in_transit,
                    SUM(CASE WHEN status = 'in_storage' THEN 1 ELSE 0 END) AS in_storage,
                    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) AS delivered,
                    SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) AS accepted
                FROM shipments
                WHERE is_active = 1 AND DATE(created_at) = CURDATE() {$where}";

        $row = $this->query($sql, $params);
        return $row[0] ?? ['total' => 0, 'in_transit' => 0, 'in_storage' => 0, 'delivered' => 0, 'accepted' => 0];
    }

    /** Update shipment status */
    public function updateStatus(int $id, string $newStatus): bool
    {
        $extra = [];
        if ($newStatus === 'delivered') {
            $extra['delivered_at'] = date('Y-m-d H:i:s');
        }
        if ($newStatus === 'in_storage') {
            $extra['storage_start'] = date('Y-m-d H:i:s');
        }
        $extra['status'] = $newStatus;
        return $this->update($id, $extra);
    }
}
