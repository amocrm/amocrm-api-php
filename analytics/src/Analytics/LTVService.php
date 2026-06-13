<?php

declare(strict_types=1);

namespace Analytics\Analytics;

use Analytics\Models\CustomerLTV;
use Analytics\Repository\CustomerLTVRepo;
use Analytics\Repository\TransactionRepo;
use Doctrine\DBAL\Connection;

/**
 * Service for customer lifetime value calculations
 */
class LTVService
{
    private Connection $db;
    private CustomerLTVRepo $customerLTVRepo;
    private TransactionRepo $transactionRepo;

    public function __construct(
        Connection $db,
        CustomerLTVRepo $customerLTVRepo,
        TransactionRepo $transactionRepo
    ) {
        $this->db = $db;
        $this->customerLTVRepo = $customerLTVRepo;
        $this->transactionRepo = $transactionRepo;
    }

    /**
     * Get LTV report
     */
    public function getLTVReport(
        string $segment = 'all',
        ?int $minTransactions = null,
        ?\DateTimeInterface $dateFrom = null,
        ?\DateTimeInterface $dateTo = null,
        string $sortBy = 'ltv',
        string $sortOrder = 'desc',
        int $limit = 50,
        int $offset = 0
    ): array {
        // Build conditions
        $params = [];
        $conditions = ['transaction_count > 0'];

        switch ($segment) {
            case 'active':
                $conditions[] = 'last_purchase_at >= NOW() - INTERVAL \'30 days\'';
                break;
            case 'churned':
                $conditions[] = 'last_purchase_at < NOW() - INTERVAL \'30 days\'';
                break;
        }

        if ($minTransactions !== null) {
            $conditions[] = 'transaction_count >= :min_tx';
            $params['min_tx'] = $minTransactions;
        }

        if ($dateFrom !== null) {
            $conditions[] = 'first_purchase_at >= :date_from';
            $params['date_from'] = $dateFrom->format('Y-m-d H:i:s');
        }

        if ($dateTo !== null) {
            $conditions[] = 'first_purchase_at <= :date_to';
            $params['date_to'] = $dateTo->format('Y-m-d H:i:s');
        }

        $where = implode(' AND ', $conditions);

        // Validate sort
        $allowedSorts = ['ltv', 'revenue', 'transactions', 'first_purchase_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'ltv';
        }

        $order = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';
        $sortColumn = match ($sortBy) {
            'revenue', 'ltv' => 'total_revenue',
            'transactions' => 'transaction_count',
            'first_purchase_at' => 'first_purchase_at',
            default => 'total_revenue',
        };

        // Get customers
        $sql = <<<SQL
        SELECT * FROM customer_ltv
        WHERE {$where}
        ORDER BY {$sortColumn} {$order}
        LIMIT :limit OFFSET :offset
        SQL;

        $params['limit'] = $limit;
        $params['offset'] = $offset;

        $rows = $this->db->fetchAllAssociative($sql, $params);

        $customers = [];
        foreach ($rows as $row) {
            $row['contact_ids'] = json_decode($row['contact_ids'] ?? '[]', true);
            $ltv = CustomerLTV::fromArray($row);
            $customers[] = $this->formatCustomer($ltv);
        }

        // Get total count
        $countSql = "SELECT COUNT(*) FROM customer_ltv WHERE {$where}";
        $total = (int)$this->db->fetchOne($countSql, array_filter($params, fn($k) => $k !== 'limit' && $k !== 'offset', ARRAY_FILTER_USE_KEY));

        // Get summary
        $summary = $this->customerLTVRepo->getLTVSummary();

        // Get cohort data
        $cohorts = $this->customerLTVRepo->getCohortData(12);

        return [
            'segment' => $segment,
            'period' => [
                'from' => $dateFrom?->format('Y-m-d'),
                'to' => $dateTo?->format('Y-m-d'),
            ],
            'customers' => $customers,
            'summary' => [
                'total_customers' => (int)($summary['total_customers'] ?? 0),
                'total_revenue' => round((float)($summary['total_revenue'] ?? 0), 2),
                'avg_ltv' => round((float)($summary['avg_ltv'] ?? 0), 2),
                'median_ltv' => round((float)($summary['median_ltv'] ?? 0), 2),
                'ltv_percentile_90' => round((float)($summary['ltv_percentile_90'] ?? 0), 2),
                'avg_transactions_per_customer' => round((float)($summary['avg_transactions'] ?? 0), 1),
            ],
            'cohorts' => array_map(fn($c) => [
                'cohort_month' => $c['cohort_month'],
                'customers' => (int)$c['customers'],
                'revenue_month_0' => (float)$c['month_0_revenue'],
            ], $cohorts),
            'pagination' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'has_more' => ($offset + $limit) < $total,
            ],
        ];
    }

    /**
     * Format customer for API response
     */
    private function formatCustomer(CustomerLTV $ltv): array
    {
        return [
            'customer_id' => $ltv->getCustomerId(),
            'metrics' => [
                'total_revenue' => round($ltv->getTotalRevenue(), 2),
                'transaction_count' => $ltv->getTransactionCount(),
                'avg_transaction_value' => round($ltv->getAvgTransactionValue(), 2),
                'first_purchase_at' => $ltv->getFirstPurchaseAt()?->format('Y-m-d'),
                'last_purchase_at' => $ltv->getLastPurchaseAt()?->format('Y-m-d'),
                'days_since_first_purchase' => $ltv->getDaysSinceFirstPurchase(),
                'ltv' => round($ltv->getLTV(), 2),
            ],
        ];
    }

    /**
     * Get LTV for single customer
     */
    public function getCustomerLTV(int $customerId): ?array
    {
        $ltv = $this->customerLTVRepo->findByCustomerId($customerId);

        if ($ltv === null) {
            // Calculate if not exists
            $ltv = $this->customerLTVRepo->calculateLTV($customerId);
        }

        // Get transactions
        $transactions = $this->transactionRepo->findByCustomerId($customerId);

        return [
            'customer_id' => $customerId,
            'ltv' => $this->formatCustomer($ltv),
            'transactions' => array_map(fn($t) => $t->toArray(), $transactions),
        ];
    }

    /**
     * Calculate or recalculate LTV for customer
     */
    public function recalculateLTV(int $customerId): CustomerLTV
    {
        return $this->customerLTVRepo->calculateLTV($customerId);
    }

    /**
     * Get top customers by LTV
     */
    public function getTopCustomers(int $limit = 50): array
    {
        $rows = $this->customerLTVRepo->getTopByLTV($limit);

        $customers = [];
        foreach ($rows as $row) {
            $row['contact_ids'] = json_decode($row['contact_ids'] ?? '[]', true);
            $ltv = CustomerLTV::fromArray($row);
            $customers[] = $this->formatCustomer($ltv);
        }

        return $customers;
    }

    /**
     * Get cohort analysis
     */
    public function getCohortAnalysis(int $monthsBack = 12): array
    {
        $cohortData = $this->customerLTVRepo->getCohortData($monthsBack);

        // Calculate retention rates
        $cohorts = [];
        foreach ($cohortData as $cohort) {
            $month0Customers = (int)$cohort['month_0_customers'];
            
            $cohorts[] = [
                'cohort_month' => $cohort['cohort_month'],
                'customers' => $month0Customers,
                'revenue_month_0' => (float)$cohort['month_0_revenue'],
                // Additional months would require more complex query
            ];
        }

        return [
            'cohorts' => $cohorts,
            'summary' => [
                'total_cohorts' => count($cohorts),
                'avg_cohort_size' => count($cohorts) > 0 
                    ? array_sum(array_column($cohorts, 'customers')) / count($cohorts) 
                    : 0,
            ],
        ];
    }
}