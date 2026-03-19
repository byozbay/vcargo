<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseModel.php';

class TransactionModel extends BaseModel
{
    protected string $table      = 'transactions';
    protected string $primaryKey = 'transaction_id';
    protected bool   $softDelete = true;

    /** Record a financial transaction and update vault */
    public function record(array $data): int
    {
        $this->beginTransaction();
        try {
            $txId = $this->create($data);

            // Only CASH and CARD go into vault (not ACCOUNT)
            if (in_array($data['method'], ['CASH', 'CARD'])) {
                // Get current vault balance for this branch
                $lastVault = $this->query(
                    "SELECT running_balance FROM vault_transactions
                     WHERE branch_id = ? ORDER BY vault_id DESC LIMIT 1",
                    [$data['branch_id']]
                );
                $balance = (float) ($lastVault[0]['running_balance'] ?? 0);
                $balance += ($data['type'] === 'IN') ? $data['amount'] : -$data['amount'];

                $this->execute(
                    "INSERT INTO vault_transactions (transaction_id, type, method, amount, running_balance, description, branch_id, created_by)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                    [
                        $txId,
                        $data['type'],
                        $data['method'],
                        $data['amount'],
                        $balance,
                        $data['description'] ?? '',
                        $data['branch_id'],
                        $data['created_by'] ?? null,
                    ]
                );
            }

            // If it's an account payment, update account balance
            if ($data['method'] === 'ACCOUNT' && !empty($data['account_id'])) {
                $sign = $data['type'] === 'IN' ? '+' : '-';
                $this->execute(
                    "UPDATE accounts SET balance = balance {$sign} ? WHERE account_id = ?",
                    [$data['amount'], $data['account_id']]
                );
            }

            $this->commit();
            return $txId;
        } catch (\Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /** Get vault transactions for a branch */
    public function getVaultTransactions(int $branchId, string $dateFrom = '', string $dateTo = '', string $type = '', string $method = ''): array
    {
        $sql    = "SELECT vt.*, t.category, t.shipment_id, t.trip_id, t.storage_id
                   FROM vault_transactions vt
                   LEFT JOIN transactions t ON vt.transaction_id = t.transaction_id
                   WHERE vt.branch_id = ?";
        $params = [$branchId];

        if ($dateFrom) {
            $sql    .= " AND DATE(vt.created_at) >= ?";
            $params[] = $dateFrom;
        }
        if ($dateTo) {
            $sql    .= " AND DATE(vt.created_at) <= ?";
            $params[] = $dateTo;
        }
        if ($type) {
            $sql    .= " AND vt.type = ?";
            $params[] = $type;
        }
        if ($method) {
            $sql    .= " AND vt.method = ?";
            $params[] = $method;
        }

        $sql .= " ORDER BY vt.created_at DESC";
        return $this->query($sql, $params);
    }

    /** Get daily vault summary */
    public function getDailySummary(int $branchId, string $date = ''): array
    {
        $date   = $date ?: date('Y-m-d');
        $sql    = "SELECT
                       COALESCE(SUM(CASE WHEN vt.type='IN' AND vt.method='CASH' THEN vt.amount ELSE 0 END), 0) AS cash_in,
                       COALESCE(SUM(CASE WHEN vt.type='IN' AND vt.method='CARD' THEN vt.amount ELSE 0 END), 0) AS card_in,
                       COALESCE(SUM(CASE WHEN vt.type='OUT' THEN vt.amount ELSE 0 END), 0) AS total_out,
                       COALESCE(SUM(CASE WHEN vt.type='IN'  THEN vt.amount ELSE 0 END), 0) AS total_in,
                       COALESCE(SUM(CASE WHEN vt.type='OUT' AND t.category='driver_payment' THEN vt.amount ELSE 0 END), 0) AS driver_out,
                       COALESCE(SUM(CASE WHEN vt.type='OUT' AND (t.category IS NULL OR t.category!='driver_payment') THEN vt.amount ELSE 0 END), 0) AS expense_out,
                       COUNT(*) AS tx_count,
                       COALESCE(SUM(CASE WHEN vt.type='IN'  THEN 1 ELSE 0 END), 0) AS in_count,
                       COALESCE(SUM(CASE WHEN vt.type='OUT' THEN 1 ELSE 0 END), 0) AS out_count
                   FROM vault_transactions vt
                   LEFT JOIN transactions t ON vt.transaction_id = t.transaction_id
                   WHERE vt.branch_id = ? AND DATE(vt.created_at) = ?";
        $row = $this->query($sql, [$branchId, $date]);
        return $row[0] ?? [];
    }
}
