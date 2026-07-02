<?php

namespace AmoCRM\Dwh;

use PDO;
use PDOStatement;

class DwhDbAdapter
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        $this->pdo->commit();
    }

    public function rollback(): void
    {
        $this->pdo->rollBack();
    }

    public function insert(string $table, array $data): int
    {
        $columns = array_keys($data);
        $placeholders = array_map(static fn($c) => ':' . $c, $columns);
        $sql = sprintf(
            'INSERT INTO `%s` (`%s`) VALUES (%s)',
            $table,
            implode('`, `', $columns),
            implode(', ', $placeholders)
        );
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        return (int)$this->pdo->lastInsertId();
    }

    public function upsert(string $table, array $data, array $uniqueKeys): int
    {
        $columns = array_keys($data);
        $placeholders = array_map(static fn($c) => ':' . $c, $columns);
        $updates = array_map(static fn($c) => "`$c` = VALUES(`$c`)", $columns);

        $sql = sprintf(
            'INSERT INTO `%s` (`%s`) VALUES (%s) ON DUPLICATE KEY UPDATE %s',
            $table,
            implode('`, `', $columns),
            implode(', ', $placeholders),
            implode(', ', $updates)
        );
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        return $stmt->rowCount();
    }

    public function bulkInsert(string $table, array $rows): int
    {
        if (empty($rows)) {
            return 0;
        }
        $columns = array_keys(reset($rows));
        $placeholders = array_map(static fn($c) => ':' . $c, $columns);
        $sql = sprintf(
            'INSERT INTO `%s` (`%s`) VALUES (%s)',
            $table,
            implode('`, `', $columns),
            implode(', ', $placeholders)
        );
        $stmt = $this->pdo->prepare($sql);
        $count = 0;
        foreach ($rows as $row) {
            $stmt->execute($row);
            $count++;
        }

        return $count;
    }

    public function deleteBy(string $table, array $conditions): int
    {
        $wheres = [];
        $params = [];
        foreach ($conditions as $col => $val) {
            $wheres[] = "`$col` = :$col";
            $params[$col] = $val;
        }
        $sql = sprintf('DELETE FROM `%s` WHERE %s', $table, implode(' AND ', $wheres));
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount();
    }

    public function truncate(string $table): void
    {
        $this->pdo->exec("TRUNCATE TABLE `$table`");
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function count(string $table, array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) FROM `$table`";
        $params = [];
        if (!empty($conditions)) {
            $wheres = [];
            foreach ($conditions as $col => $val) {
                $wheres[] = "`$col` = :$col";
                $params[$col] = $val;
            }
            $sql .= ' WHERE ' . implode(' AND ', $wheres);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int)$stmt->fetchColumn();
    }
}
