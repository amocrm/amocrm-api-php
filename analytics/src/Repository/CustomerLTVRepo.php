<?php

declare(strict_types=1);

namespace Analytics\Repository;

use Analytics\Models\CustomerLTV;
use Carbon\Carbon;
use Doctrine\DBAL\Connection;

/**
 * Repository for customer LTV calculations
 */
class CustomerLTVRepo
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Calculate and update LTV for a customer
     */
    public function calculateLTV(int $customerId): CustomerLTV
    {
        $sql = <<<SQL
        SELECT 
            t.customer_id,
            COALESCE(SUM(t.price), 0) as total_revenue,
            COUNT(t.id) as transaction_count,
            MIN(t.created_at) as first_purchase_at,
            MAX(t.created_at) as last_purchase_at
        FROM transactions t
        WHERE t.customer_id = :customer_id AND t.is_completed = true
        GROUP BY t.customer_id
        SQL;

        $row = $this->db->fetchAssociative($sql, ['customer_id' => $customerId]);

        $ltv = new CustomerLTV();
        $ltv->setCustomerId($customerId);

        if ($row !== false) {
            $ltv->setTotalRevenue((float)$row['total_revenue']);
            $ltv->setTransactionCount((int)$row['transaction_count']);
            $ltv->setFirstPurchaseAt($row['first_purchase_at'] ? Carbon::parse($row['first_purchase_at']) : null);
            $ltv->setLastPurchaseAt($row['last_purchase_at'] ? Carbon::parse($row['last_purchase_at']) : null);
        }

        // Calculate period-specific LTV
        $now = Carbon::now();
        
        if ($ltv->getFirstPurchaseAt() !== null) {
            $daysSinceFirst = $ltv->getFirstPurchaseAt()->diffInDays($now);
            
            if ($daysSinceFirst >= 90) {
                $ltv->setLtv90Days($this->getPeriodRevenue($customerId, 90));
            }
            if ($daysSinceFirst >= 180) {
                $ltv->setLtv180Days($this->getPeriodRevenue($customerId, 180));
            }
            if ($daysSinceFirst >= 365) {
                $ltv->setLtv365Days($this->getPeriodRevenue($customerId, 365));
            }
        }

        // Upsert
        $this->upsert($ltv);

        return $ltv;
    }

    /**
     * Get revenue for customer within N days from first purchase
     */
    private function getPeriodRevenue(int $customerId, int $days): float
    {
        $sql = <<<SQL
        SELECT COALESCE(SUM(price), 0) as revenue
        FROM transactions
        WHERE customer_id = :customer_id 
        AND is_completed = true
        AND created_at >= (
            SELECT MIN(created_at) - INTERVAL '{$days} days'
            FROM transactions
            WHERE customer_id = :customer_id AND is_completed = true
        )
        SQL;

        return (float)$this->db->fetchOne($sql, ['customer_id' => $customerId]);
    }

    /**
     * Upsert LTV record
     */
    public function upsert(CustomerLTV $ltv): void
    {
        $sql = <<<SQL
        INSERT INTO customer_ltv (
            customer_id, contact_ids, total_revenue, transaction_count,
            first_purchase_at, last_purchase_at,
            ltv_90_days, ltv_180_days, ltv_365_days
        ) VALUES (
            :customer_id, :contact_ids, :total_revenue, :transaction_count,
            :first_purchase_at, :last_purchase_at,
            :ltv_90_days, :ltv_180_days, :ltv_365_days
        )
        ON CONFLICT (customer_id) DO UPDATE SET
            total_revenue = EXCLUDED.total_revenue,
            transaction_count = EXCLUDED.transaction_count,
            first_purchase_at = COALESCE(EXCLUDED.first_purchase_at, customer_ltv.first_purchase_at),
            last_purchase_at = EXCLUDED.last_purchase_at,
            ltv_90_days = EXCLUDED.ltv_90_days,
            ltv_180_days = EXCLUDED.ltv_180_days,
            ltv_365_days = EXCLUDED.ltv_365_days,
            updated_at = NOW()
        SQL;

        $this->db->executeStatement($sql, [
            'customer_id' => $ltv->getCustomerId(),
            'contact_ids' => json_encode($ltv->getContactIds()),
            'total_revenue' => $ltv->getTotalRevenue(),
            'transaction_count' => $ltv->getTransactionCount(),
            'first_purchase_at' => $ltv->getFirstPurchaseAt()?->toDateTimeString(),
            'last_purchase_at' => $ltv->getLastPurchaseAt()?->toDateTimeString(),
            'ltv_90_days' => $ltv->getLtv90Days(),
            'ltv_180_days' => $ltv->getLtv180Days(),
            'ltv_365_days' => $ltv->getLtv365Days(),
        ]);
    }

    /**
     * Get LTV summary statistics
     */
    public function getLTVSummary(): array
    {
        $sql = <<<SQL
        SELECT 
            COUNT(*) as total_customers,
            SUM(total_revenue) as total_revenue,
            AVG(total_revenue) as avg_ltv,
            PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY total_revenue) as median_ltv,
            PERCENTILE_CONT(0.9) WITHIN GROUP (ORDER BY total_revenue) as ltv_percentile_90,
            AVG(transaction_count) as avg_transactions
        FROM customer_ltv
        WHERE transaction_count > 0
        SQL;

        return $this->db->fetchAssociative($sql);
    }

    /**
     * Get cohort analysis data
     */
    public function getCohortData(int $monthsBack = 12): array
    {
        $sql = <<<SQL
        WITH cohort_data AS (
            SELECT 
                DATE_TRUNC('month', first_purchase_at) as cohort_month,
                customer_id,
                total_revenue
            FROM customer_ltv
            WHERE first_purchase_at >= DATE_TRUNC('month', NOW() - INTERVAL ':months months')
        )
        SELECT 
            cohort_month,
            COUNT(DISTINCT customer_id) as customers,
            SUM(total_revenue) as month_0_revenue,
            COUNT(DISTINCT customer_id) as month_0_customers
        FROM cohort_data
        GROUP BY cohort_month
        ORDER BY cohort_month DESC
        SQL;

        return $this->db->fetchAllAssociative($sql, ['months' => $monthsBack]);
    }

    /**
     * Get top customers by LTV
     */
    public function getTopByLTV(int $limit = 50): array
    {
        $sql = <<<SQL
        SELECT cl.*, t.customer_name
        FROM customer_ltv cl
        LEFT JOIN (
            SELECT customer_id, MAX(catalog_element_name) as customer_name
            FROM transactions
            GROUP BY customer_id
        ) t ON cl.customer_id = t.customer_id
        ORDER BY cl.total_revenue DESC
        LIMIT :limit
        SQL;

        return $this->db->fetchAllAssociative($sql, ['limit' => $limit]);
    }

    /**
     * Find by customer ID
     */
    public function findByCustomerId(int $customerId): ?CustomerLTV
    {
        $sql = 'SELECT * FROM customer_ltv WHERE customer_id = :customer_id';
        $row = $this->db->fetchAssociative($sql, ['customer_id' => $customerId]);

        if ($row === false) {
            return null;
        }

        $row['contact_ids'] = json_decode($row['contact_ids'] ?? '[]', true);
        return CustomerLTV::fromArray($row);
    }
}