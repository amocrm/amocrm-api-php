<?php

declare(strict_types=1);

namespace Analytics\Analytics;

use Analytics\Models\FunnelStage;
use Analytics\Repository\LeadSnapshotRepo;
use Analytics\Repository\PipelineStagesRepo;
use Doctrine\DBAL\Connection;

/**
 * Service for calculating funnel metrics
 */
class FunnelService
{
    private Connection $db;
    private LeadSnapshotRepo $leadSnapshotRepo;
    private PipelineStagesRepo $pipelineStagesRepo;

    public function __construct(
        Connection $db,
        LeadSnapshotRepo $leadSnapshotRepo,
        PipelineStagesRepo $pipelineStagesRepo
    ) {
        $this->db = $db;
        $this->leadSnapshotRepo = $leadSnapshotRepo;
        $this->pipelineStagesRepo = $pipelineStagesRepo;
    }

    /**
     * Get funnel metrics for a pipeline
     */
    public function getFunnel(
        int $pipelineId,
        ?\DateTimeInterface $from = null,
        ?\DateTimeInterface $to = null,
        ?string $groupBy = null
    ): array {
        // Get pipeline stages
        $stages = $this->pipelineStagesRepo->findByPipelineId($pipelineId);
        
        if (empty($stages)) {
            return [
                'pipeline_id' => $pipelineId,
                'pipeline_name' => null,
                'stages' => [],
                'summary' => null,
            ];
        }

        $pipelineName = $stages[0]['pipeline_name'] ?? 'Unknown';
        
        // Get lead metrics per status
        $metrics = $this->getLeadMetricsByStatus($pipelineId, $from, $to);

        // Build funnel stages
        $funnelStages = [];
        $previousLeadCount = null;

        foreach ($stages as $stage) {
            $statusId = $stage['status_id'];
            $stageMetrics = $metrics[$statusId] ?? [
                'lead_count' => 0,
                'total_revenue' => 0,
                'avg_price' => 0,
            ];

            $leadCount = (int)$stageMetrics['lead_count'];
            $totalRevenue = (float)($stageMetrics['total_revenue'] ?? 0);

            $funnelStage = new FunnelStage();
            $funnelStage->setPipelineId($pipelineId);
            $funnelStage->setPipelineName($pipelineName);
            $funnelStage->setStatusId($statusId);
            $funnelStage->setStatusName($stage['status_name']);
            $funnelStage->setSortOrder((int)$stage['sort_order']);
            $funnelStage->setIsFinal((bool)$stage['is_final']);
            $funnelStage->setFinalType($stage['final_type']);
            $funnelStage->setLeadCount($leadCount);
            $funnelStage->setTotalRevenue($totalRevenue);
            $funnelStage->setAvgDealValue($leadCount > 0 ? $totalRevenue / $leadCount : 0);

            // Calculate conversion from previous stage
            if ($previousLeadCount !== null && $previousLeadCount > 0) {
                $conversionRate = $leadCount / $previousLeadCount;
                $funnelStage->setConversionRate(round($conversionRate, 4));
                $funnelStage->setPreviousConversionRate(round($conversionRate, 4));
                $funnelStage->setConvertedCount($leadCount);
            } else {
                $funnelStage->setConversionRate(1.0);
            }

            // Calculate average time in stage
            $avgTime = $this->getAverageTimeInStage($pipelineId, $statusId, $from, $to);
            $funnelStage->setAvgTimeSeconds($avgTime);

            $funnelStages[] = $funnelStage;
            $previousLeadCount = $leadCount;
        }

        // Build summary
        $summary = $this->buildSummary($funnelStages, $from, $to);

        return [
            'pipeline_id' => $pipelineId,
            'pipeline_name' => $pipelineName,
            'period' => [
                'from' => $from?->format('Y-m-d'),
                'to' => $to?->format('Y-m-d'),
            ],
            'stages' => array_map(fn($s) => $s->toArray(), $funnelStages),
            'summary' => $summary,
        ];
    }

    /**
     * Get lead metrics grouped by status
     */
    private function getLeadMetricsByStatus(int $pipelineId, ?\DateTimeInterface $from, ?\DateTimeInterface $to): array
    {
        $params = ['pipeline_id' => $pipelineId];
        $conditions = ['pipeline_id = :pipeline_id', 'is_deleted = false'];

        if ($from !== null) {
            $conditions[] = 'snapshot_at >= :from';
            $params['from'] = $from->format('Y-m-d H:i:s');
        }

        if ($to !== null) {
            $conditions[] = 'snapshot_at <= :to';
            $params['to'] = $to->format('Y-m-d H:i:s');
        }

        $where = implode(' AND ', $conditions);

        // Get latest snapshot per lead
        $sql = <<<SQL
        WITH latest_snapshots AS (
            SELECT DISTINCT ON (lead_id) *
            FROM lead_snapshots
            WHERE {$where}
            ORDER BY lead_id, snapshot_at DESC
        )
        SELECT 
            status_id,
            COUNT(DISTINCT lead_id) as lead_count,
            SUM(COALESCE(price, 0)) as total_revenue,
            AVG(COALESCE(price, 0)) as avg_price
        FROM latest_snapshots
        GROUP BY status_id
        SQL;

        $rows = $this->db->fetchAllAssociative($sql, $params);

        $metrics = [];
        foreach ($rows as $row) {
            $metrics[$row['status_id']] = $row;
        }

        return $metrics;
    }

    /**
     * Calculate average time spent in a stage
     */
    private function getAverageTimeInStage(
        int $pipelineId, 
        int $statusId, 
        ?\DateTimeInterface $from, 
        ?\DateTimeInterface $to
    ): float {
        $params = [
            'pipeline_id' => $pipelineId,
            'status_id' => $statusId,
        ];
        $conditions = ['ls.pipeline_id = :pipeline_id', 'ls.status_id = :status_id'];

        if ($from !== null) {
            $conditions[] = 'ls.snapshot_at >= :from';
            $params['from'] = $from->format('Y-m-d H:i:s');
        }

        if ($to !== null) {
            $conditions[] = 'ls.snapshot_at <= :to';
            $params['to'] = $to->format('Y-m-d H:i:s');
        }

        $where = implode(' AND ', $conditions);

        // Calculate time between this status and next status
        $sql = <<<SQL
        SELECT AVG(EXTRACT(EPOCH FROM (
            COALESCE(next_s.snapshot_at, NOW()) - ls.snapshot_at
        ))) as avg_seconds
        FROM lead_snapshots ls
        LEFT JOIN LATERAL (
            SELECT snapshot_at 
            FROM lead_snapshots 
            WHERE lead_id = ls.lead_id 
            AND snapshot_at > ls.snapshot_at
            ORDER BY snapshot_at ASC
            LIMIT 1
        ) next_s ON true
        WHERE {$where}
        SQL;

        $result = $this->db->fetchOne($sql, $params);

        return (float)($result ?? 0);
    }

    /**
     * Build funnel summary
     */
    private function buildSummary(array $stages, ?\DateTimeInterface $from, ?\DateTimeInterface $to): array
    {
        $totalLeads = 0;
        $totalRevenue = 0.0;
        $wonLeads = 0;

        foreach ($stages as $stage) {
            $totalLeads += $stage->getLeadCount();
            $totalRevenue += $stage->getTotalRevenue();

            if ($stage->isFinal() && $stage->getFinalType() === 'won') {
                $wonLeads += $stage->getLeadCount();
            }
        }

        // Find first and last stage
        $firstStage = $stages[0] ?? null;
        $lastStage = null;
        foreach (array_reverse($stages) as $stage) {
            if ($stage->getLeadCount() > 0) {
                $lastStage = $stage;
                break;
            }
        }

        // Calculate average deal cycle
        $avgCycleDays = 0;
        if ($firstStage !== null && $lastStage !== null) {
            $totalDays = 0;
            $count = 0;
            foreach ($stages as $stage) {
                if ($stage->getLeadCount() > 0) {
                    $totalDays += $stage->getAvgTimeDays();
                    $count++;
                }
            }
            $avgCycleDays = $count > 0 ? round($totalDays / $count, 1) : 0;
        }

        return [
            'total_leads' => $totalLeads,
            'total_revenue' => round($totalRevenue, 2),
            'overall_conversion' => $totalLeads > 0 && $wonLeads > 0 
                ? round($wonLeads / $totalLeads, 4) 
                : 0,
            'avg_deal_cycle_days' => $avgCycleDays,
        ];
    }

    /**
     * Get historical funnel for a specific date
     */
    public function getHistoricalFunnel(int $pipelineId, \DateTimeInterface $date): array
    {
        return $this->getFunnel($pipelineId, null, $date);
    }
}