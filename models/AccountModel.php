<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseModel.php';

class AccountModel extends BaseModel
{
    protected string $table      = 'accounts';
    protected string $primaryKey = 'account_id';

    /** Get accounts with balance info */
    public function getList(int $branchId = 0, string $type = '', string $search = ''): array
    {
        $sql    = "SELECT a.*, b.name AS branch_name
                   FROM accounts a
                   LEFT JOIN branches b ON a.branch_id = b.branch_id
                   WHERE a.is_active = 1";
        $params = [];

        if ($branchId > 0) {
            $sql    .= " AND a.branch_id = ?";
            $params[] = $branchId;
        }
        if ($type) {
            $sql    .= " AND a.type = ?";
            $params[] = $type;
        }
        if ($search) {
            $sql    .= " AND (a.name LIKE ? OR a.tc_no LIKE ? OR a.tax_no LIKE ? OR a.phone LIKE ?)";
            $like     = "%{$search}%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        $sql .= " ORDER BY a.name";
        return $this->query($sql, $params);
    }

    /** Get accounts with credit limit exceeded */
    public function getOverLimit(): array
    {
        return $this->query(
            "SELECT a.*, b.name AS branch_name
             FROM accounts a
             LEFT JOIN branches b ON a.branch_id = b.branch_id
             WHERE a.is_active = 1
               AND a.credit_limit > 0
               AND a.balance > a.credit_limit
             ORDER BY (a.balance - a.credit_limit) DESC"
        );
    }

    /** Get transaction history for an account */
    public function getTransactions(int $accountId): array
    {
        return $this->query(
            "SELECT t.*, s.tracking_no
             FROM transactions t
             LEFT JOIN shipments s ON t.shipment_id = s.shipment_id
             WHERE t.account_id = ? AND t.is_active = 1
             ORDER BY t.created_at DESC",
            [$accountId]
        );
    }
}
