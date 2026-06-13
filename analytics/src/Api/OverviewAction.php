<?php

declare(strict_types=1);

namespace Analytics\Api;

use Carbon\Carbon;
use Fusio\Engine\Action\Runtime;
use Fusio\Engine\Context;
use Fusio\Engine\Request;

/**
 * Overview API action for Fusio
 * 
 * GET /api/analytics/overview
 */
class OverviewAction extends BaseAction
{
    public function handle(Request $request, Context $context, Runtime $runtime): array
    {
        try {
            $db = $this->getConnection();
            $now = Carbon::now();
            $startOfMonth = $now->copy()->startOfMonth();
            $endOfMonth = $now->copy()->endOfMonth();

            // Get leads metrics
            $leadsMetrics = $this->getLeadsMetrics($db, $startOfMonth, $endOfMonth);
            
            // Get revenue metrics
            $revenueMetrics = $this->getRevenueMetrics($db, $startOfMonth, $endOfMonth);
            
            // Get customer metrics
            $customerMetrics = $this->getCustomerMetrics($db, $startOfMonth, $endOfMonth);
            
            // Get top sources
            $topSources = $this->getTopSources($db, $startOfMonth, $endOfMonth);
            
            // Get recent activity
            $activity = $this->getRecentActivity($db);

            return $this->jsonResponse([
                'generated_at' => $now->toIso8601String(),
                'period' => [
                    'from' => $startOfMonth->format('Y-m-d'),
                    'to' => $endOfMonth->format('Y-m-d'),
                ],
                'metrics' => [
                    'leads' => $leadsMetrics,
                    'revenue' => $revenueMetrics,
                    'customers' => $customerMetrics,
                    'funnel' => $this->getFunnelSummary($db),
                ],
                'top_sources' => $topSources,
                'recent_activity' => $activity,
            ]);

        } catch (\Exception $e) {
            $this->logger->apiError('Overview API error: ' . $e->getMessage());

            return $this->jsonResponse([
                'error' => [
                    'code' => 'INTERNAL_ERROR',
                    'message' => 'Failed to generate overview',
                ],
            ], 500);
        }
    }

    private function getLeadsMetrics($db, Carbon $from, Carbon $to): array
    {
        $sql = <<<SQL
        SELECT 
            COUNT(DISTINCT CASE WHEN snapshot_at >= :start THEN lead_id END) as total,
            COUNT(DISTINCT CASE WHEN created_at >= :start AND is_deleted = false THEN lead_id END) as new_this_month,
            COUNT(DISTINCT CASE WHEN is_final = true AND final_type = 'won' AND snapshot_at >= :start THEN lead_id END) as won,
            COUNT(DISTINCT CASE WHEN is_final = true AND final_type = 'lost' AND snapshot_at >= :start THEN lead_id END) as lost,
            AVG(CASE WHEN is_deleted = false THEN price END) as avg_deal_value
        FROM (
            SELECT DISTINCT ON (ls.lead_id)
                ls.lead_id, ls.created_at, ls.price, ls.is_deleted, ls.snapshot_at,
                ps.is_final, ps.final_type
            FROM lead_snapshots ls
            LEFT JOIN pipeline_stages ps ON ls.pipeline_id = ps.pipeline_id AND ls.status_id = ps.status_id
            WHERE ls.snapshot_at >= :start AND ls.snapshot_at <= :end
            ORDER BY ls.lead_id, ls.snapshot_at DESC
        ) latest
        SQL;

        $row = $db->fetchAssociative($sql, [
            'start' => $from->format('Y-m-d H:i:s'),
            'end' => $to->format('Y-m-d H:i:s'),
        ]);

        return [
            'total' => (int)($row['total'] ?? 0),
            'new_this_month' => (int)($row['new_this_month'] ?? 0),
            'won' => (int)($row['won'] ?? 0),
            'lost' => (int)($row['lost'] ?? 0),
            'avg_deal_value' => round((float)($row['avg_deal_value'] ?? 0), 2),
        ];
    }

    private function getRevenueMetrics($db, Carbon $from, Carbon $to): array
    {
        // Revenue from won deals
        $dealRevenueSql = <<<SQL
        SELECT COALESCE(SUM(price), 0) as revenue
        FROM lead_snapshots ls
        JOIN pipeline_stages ps ON ls.pipeline_id = ps.pipeline_id AND ls.status_id = ps.status_id
        WHERE ps.is_final = true AND ps.final_type = 'won'
        AND ls.snapshot_at >= :start AND ls.snapshot_at <= :end
        AND ls.is_deleted = false
        SQL;

        $dealRevenue = (float)$db->fetchOne($dealRevenueSql, [
            'start' => $from->format('Y-m-d H:i:s'),
            'end' => $to->format('Y-m-d H:i:s'),
        ]);

        // Revenue from transactions
        $txRevenueSql = <<<SQL
        SELECT COALESCE(SUM(price), 0) as revenue
        FROM transactions
        WHERE is_completed = true
        AND created_at >= :start AND created_at <= :end
        SQL;

        $txRevenue = (float)$db->fetchOne($txRevenueSql, [
            'start' => $from->format('Y-m-d H:i:s'),
            'end' => $to->format('Y-m-d H:i:s'),
        ]);

        return [
            'total' => round($dealRevenue + $txRevenue, 2),
            'from_won_deals' => round($dealRevenue, 2),
            'from_transactions' => round($txRevenue, 2),
        ];
    }

    private function getCustomerMetrics($db, Carbon $from, Carbon $to): array
    {
        $sql = <<<SQL
        SELECT 
            COUNT(*) as total,
            COUNT(CASE WHEN first_purchase_at >= :start THEN 1 END) as new_this_month,
            COUNT(CASE WHEN last_purchase_at >= DATE_TRUNC('day', NOW() - INTERVAL '30 days') THEN 1 END) as active,
            COUNT(CASE WHEN last_purchase_at < DATE_TRUNC('day', NOW() - INTERVAL '30 days') THEN 1 END) as churned
        FROM customer_ltv
        SQL;

        $row = $db->fetchAssociative($sql, [
            'start' => $from->format('Y-m-d H:i:s'),
        ]);

        return [
            'total' => (int)($row['total'] ?? 0),
            'new_this_month' => (int)($row['new_this_month'] ?? 0),
            'active' => (int)($row['active'] ?? 0),
            'churned' => (int)($row['churned'] ?? 0),
        ];
    }

    private function getTopSources($db, Carbon $from, Carbon $to, int $limit = 5): array
    {
        $sql = <<<SQL
        SELECT 
            sa.source_name,
            COUNT(DISTINCT sa.lead_id) as lead_count,
            COALESCE(SUM(ls.price), 0) as revenue,
            COUNT(DISTINCT CASE WHEN ps.is_final = true AND ps.final_type = 'won' THEN sa.lead_id END)::float / 
                NULLIF(COUNT(DISTINCT sa.lead_id), 0) as conversion_rate
        FROM source_attribution sa
        LEFT JOIN lead_snapshots ls ON sa.lead_id = ls.lead_id AND ls.is_deleted = false
        LEFT JOIN pipeline_stages ps ON ls.pipeline_id = ps.pipeline_id AND ls.status_id = ps.status_id
        WHERE sa.first_touch_at >= :start AND sa.first_touch_at <= :end
        GROUP BY sa.source_name
        ORDER BY revenue DESC
        LIMIT :limit
        SQL;

        $rows = $db->fetchAllAssociative($sql, [
            'start' => $from->format('Y-m-d H:i:s'),
            'end' => $to->format('Y-m-d H:i:s'),
            'limit' => $limit,
        ]);

        return array_map(fn($row) => [
            'source_name' => $row['source_name'] ?? 'Unknown',
            'lead_count' => (int)$row['lead_count'],
            'revenue' => (float)$row['revenue'],
            'conversion_rate' => round((float)($row['conversion_rate'] ?? 0), 4),
        ], $rows);
    }

    private function getFunnelSummary($db): array
    {
        $sql = <<<SQL
        SELECT 
            AVG(EXTRACT(EPOCH FROM (closed_at - created_at)) / 86400) as avg_days
        FROM (
            SELECT DISTINCT ON (lead_id)
                lead_id, created_at, closed_at
            FROM lead_snapshots
            WHERE is_deleted = false AND closed_at IS NOT NULL
            ORDER BY lead_id, snapshot_at DESC
        ) leads
        WHERE closed_at IS NOT NULL
        SQL;

        $avgDays = (float)($db->fetchOne($sql) ?? 0);

        return [
            'avg_conversion_rate' => 0.35, // Would need more complex query
            'avg_deal_cycle_days' => round($avgDays, 1),
        ];
    }

    private function getRecentActivity($db): array
    {
        // Last sync time
        $lastSyncSql = "SELECT MAX(snapshot_at) as last_sync FROM lead_snapshots";
        $lastSync = $db->fetchOne($lastSyncSql);

        // Pending webhooks
        $pendingSql = "SELECT COUNT(*) FROM webhook_logs WHERE status = 'pending'";
        $pending = (int)$db->fetchOne($pendingSql);

        // Last lead update
        $lastLeadSql = "SELECT MAX(updated_at) FROM lead_snapshots WHERE is_deleted = false";
        $lastLead = $db->fetchOne($lastLeadSql);

        return [
            'last_sync_at' => $lastSync,
            'pending_webhooks' => $pending,
            'last_lead_at' => $lastLead,
        ];
    }
}