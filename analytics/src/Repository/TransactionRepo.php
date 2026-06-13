<?php

declare(strict_types=1);

namespace Analytics\Repository;

use Analytics\Models\Transaction;
use Doctrine\DBAL\Connection;

/**
 * Repository for transactions
 */
class TransactionRepo
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Insert transaction
     */
    public function insert(Transaction $tx): int
    {
        $sql = <<<SQL
        INSERT INTO transactions (
            transaction_id, customer_id, price, catalog_element_id,
            catalog_element_name, quantity, unit_price,
            created_at, completed_at, is_completed, custom_fields
        ) VALUES (
            :transaction_id, :customer_id, :price, :catalog_element_id,
            :catalog_element_name, :quantity, :unit_price,
            :created_at, :completed_at, :is_completed, :custom_fields
        )
        ON CONFLICT (transaction_id) DO UPDATE SET
            price = EXCLUDED.price,
            updated_at = NOW()
        RETURNING id
        SQL;

        $result = $this->db->executeQuery($sql, [
            'transaction_id' => $tx->getTransactionId(),
            'customer_id' => $tx->getCustomerId(),
            'price' => $tx->getPrice(),
            'catalog_element_id' => $tx->getCatalogElementId(),
            'catalog_element_name' => $tx->getCatalogElementName(),
            'quantity' => $tx->getQuantity(),
            'unit_price' => $tx->getUnitPrice(),
            'created_at' => $tx->getCreatedAt()?->toDateTimeString(),
            'completed_at' => $tx->getCompletedAt()?->toDateTimeString(),
            'is_completed' => $tx->isCompleted(),
            'custom_fields' => json_encode($tx->getCustomFields()),
        ]);

        return (int)$result->fetchOne();
    }

    /**
     * Find by customer ID
     */
    public function findByCustomerId(int $customerId): array
    {
        $sql = <<<SQL
        SELECT * FROM transactions
        WHERE customer_id = :customer_id
        ORDER BY created_at DESC
        SQL;

        $rows = $this->db->fetchAllAssociative($sql, ['customer_id' => $customerId]);

        return array_map(fn($row) => $this->hydrate($row), $rows);
    }

    /**
     * Get total revenue by customer
     */
    public function getCustomerRevenue(int $customerId): float
    {
        $sql = <<<SQL
        SELECT COALESCE(SUM(price), 0) as total
        FROM transactions
        WHERE customer_id = :customer_id AND is_completed = true
        SQL;

        return (float)$this->db->fetchOne($sql, ['customer_id' => $customerId]);
    }

    /**
     * Get transaction count by customer
     */
    public function getCustomerTransactionCount(int $customerId): int
    {
        $sql = <<<SQL
        SELECT COUNT(*) as count
        FROM transactions
        WHERE customer_id = :customer_id AND is_completed = true
        SQL;

        return (int)$this->db->fetchOne($sql, ['customer_id' => $customerId]);
    }

    /**
     * Hydrate from row
     */
    private function hydrate(array $row): Transaction
    {
        $row['custom_fields'] = json_decode($row['custom_fields'] ?? '{}', true);
        return Transaction::fromArray($row);
    }
}
