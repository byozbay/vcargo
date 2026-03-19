<?php
declare(strict_types=1);

/**
 * BaseModel — Reusable CRUD with soft-delete, pagination, and query helpers.
 * All models extend this class.
 */
class BaseModel
{
    protected PDO    $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected bool   $softDelete = true;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    // ── READ ─────────────────────────────────────────────────

    /**
     * Find a single record by primary key.
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        if ($this->softDelete) {
            $sql .= " AND is_active = 1";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Find all records, optionally with conditions.
     */
    public function findAll(array $conditions = [], string $orderBy = '', int $limit = 0, int $offset = 0): array
    {
        $sql    = "SELECT * FROM {$this->table}";
        $params = [];

        $where = $this->buildWhere($conditions, $params);
        if ($this->softDelete) {
            $where = $where ? "({$where}) AND is_active = 1" : "is_active = 1";
        }
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        if ($limit > 0) {
            $sql .= " LIMIT {$limit}";
            if ($offset > 0) {
                $sql .= " OFFSET {$offset}";
            }
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Count records matching conditions.
     */
    public function count(array $conditions = []): int
    {
        $sql    = "SELECT COUNT(*) as cnt FROM {$this->table}";
        $params = [];
        $where  = $this->buildWhere($conditions, $params);
        if ($this->softDelete) {
            $where = $where ? "({$where}) AND is_active = 1" : "is_active = 1";
        }
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    // ── CREATE ───────────────────────────────────────────────

    /**
     * Insert a new record and return the inserted ID.
     */
    public function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $holders = implode(', ', array_fill(0, count($data), '?'));
        $sql     = "INSERT INTO {$this->table} ({$columns}) VALUES ({$holders})";
        $stmt    = $this->db->prepare($sql);
        $stmt->execute(array_values($data));
        return (int) $this->db->lastInsertId();
    }

    // ── UPDATE ───────────────────────────────────────────────

    /**
     * Update a record by primary key.
     */
    public function update(int $id, array $data): bool
    {
        $sets   = implode(', ', array_map(fn($k) => "{$k} = ?", array_keys($data)));
        $sql    = "UPDATE {$this->table} SET {$sets} WHERE {$this->primaryKey} = ?";
        $params = array_values($data);
        $params[] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // ── DELETE (soft) ────────────────────────────────────────

    /**
     * Soft-delete by setting is_active = 0.
     */
    public function delete(int $id): bool
    {
        if ($this->softDelete) {
            return $this->update($id, ['is_active' => 0]);
        }
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ── RAW QUERY ────────────────────────────────────────────

    /**
     * Execute a raw SQL query with parameters.
     */
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Execute a raw SQL statement (INSERT/UPDATE/DELETE) with parameters.
     */
    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // ── HELPERS ──────────────────────────────────────────────

    /**
     * Build WHERE clause from conditions array.
     * Supports: ['field' => value] for =, ['field >=' => value] for comparison operators.
     */
    protected function buildWhere(array $conditions, array &$params): string
    {
        if (empty($conditions)) return '';

        $clauses = [];
        foreach ($conditions as $key => $value) {
            // Support operators like 'field >=' or 'field LIKE'
            if (preg_match('/^(\w+)\s+(>=|<=|>|<|!=|LIKE|IN)$/i', $key, $m)) {
                $field = $m[1];
                $op    = strtoupper($m[2]);
                if ($op === 'IN' && is_array($value)) {
                    $holders  = implode(',', array_fill(0, count($value), '?'));
                    $clauses[] = "{$field} IN ({$holders})";
                    $params   = array_merge($params, $value);
                } else {
                    $clauses[] = "{$field} {$op} ?";
                    $params[]  = $value;
                }
            } elseif ($value === null) {
                $clauses[] = "{$key} IS NULL";
            } else {
                $clauses[] = "{$key} = ?";
                $params[]  = $value;
            }
        }
        return implode(' AND ', $clauses);
    }

    /**
     * Begin a database transaction.
     */
    public function beginTransaction(): bool
    {
        return $this->db->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->db->commit();
    }

    public function rollBack(): bool
    {
        return $this->db->rollBack();
    }
}
