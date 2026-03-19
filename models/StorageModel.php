<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseModel.php';

class StorageModel extends BaseModel
{
    protected string $table      = 'storage_records';
    protected string $primaryKey = 'storage_id';

    /** Generate unique record number: EMN-YYMMDD-XXX */
    public function generateRecordNo(): string
    {
        $date = date('ymd');
        $row  = $this->query(
            "SELECT COUNT(*) as cnt FROM storage_records WHERE DATE(created_at) = CURDATE()"
        );
        $seq = ($row[0]['cnt'] ?? 0) + 1;
        return sprintf('EMN-%s-%03d', $date, $seq);
    }

    /** Get active storage records for a branch */
    public function getActive(int $branchId = 0, string $type = '', string $search = ''): array
    {
        $sql    = "SELECT sr.*, b.name AS branch_name, b.free_storage_hours, b.storage_hourly_rate, b.baggage_hourly_rate
                   FROM storage_records sr
                   LEFT JOIN branches b ON sr.branch_id = b.branch_id
                   WHERE sr.status = 'stored' AND sr.is_active = 1";
        $params = [];

        if ($branchId > 0) {
            $sql    .= " AND sr.branch_id = ?";
            $params[] = $branchId;
        }
        if ($type) {
            $sql    .= " AND sr.type = ?";
            $params[] = $type;
        }
        if ($search) {
            $sql    .= " AND (sr.record_no LIKE ? OR sr.owner_name LIKE ?)";
            $like     = "%{$search}%";
            $params[] = $like;
            $params[] = $like;
        }

        $sql .= " ORDER BY sr.check_in ASC";
        return $this->query($sql, $params);
    }

    /** Calculate storage fee for a record */
    public function calculateFee(int $storageId): array
    {
        $record = $this->find($storageId);
        if (!$record) return ['fee' => 0, 'hours' => 0, 'paid_hours' => 0];

        $checkIn   = strtotime($record['check_in']);
        $now       = time();
        $totalSecs = $now - $checkIn;
        $totalH    = $totalSecs / 3600;
        $freeH     = (float) $record['free_hours'];
        $rate      = (float) $record['hourly_rate'];
        $paidH     = max(0, $totalH - $freeH);
        $fee       = $paidH * $rate;

        return [
            'total_hours' => round($totalH, 2),
            'free_hours'  => $freeH,
            'paid_hours'  => round($paidH, 2),
            'hourly_rate' => $rate,
            'fee'         => round($fee, 2),
            'is_critical' => $totalH >= 24,
        ];
    }

    /** Deliver a storage record (check-out) */
    public function deliver(int $storageId, float $totalFee, float $discount = 0, ?int $approvedBy = null, string $paymentMethod = 'CASH'): bool
    {
        return $this->update($storageId, [
            'check_out'            => date('Y-m-d H:i:s'),
            'total_fee'            => $totalFee,
            'fee_discount'         => $discount,
            'discount_approved_by' => $approvedBy,
            'payment_method'       => $paymentMethod,
            'status'               => 'delivered',
        ]);
    }

    /** Get storage dashboard stats */
    public function getStats(int $branchId = 0): array
    {
        $where  = $branchId > 0 ? "AND branch_id = ?" : "";
        $params = $branchId > 0 ? [$branchId] : [];

        $sql = "SELECT
                    COUNT(*) as total_stored,
                    SUM(CASE WHEN TIMESTAMPDIFF(HOUR, check_in, NOW()) > free_hours THEN 1 ELSE 0 END) AS paid_count,
                    SUM(CASE WHEN TIMESTAMPDIFF(HOUR, check_in, NOW()) >= 24 THEN 1 ELSE 0 END) AS critical_count
                FROM storage_records
                WHERE status = 'stored' AND is_active = 1 {$where}";

        $row = $this->query($sql, $params);
        return $row[0] ?? ['total_stored' => 0, 'paid_count' => 0, 'critical_count' => 0];
    }
}
