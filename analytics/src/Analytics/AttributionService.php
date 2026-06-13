<?php

declare(strict_types=1);

namespace Analytics\Analytics;

use Analytics\Repository\SourceAttributionRepo;
use Analytics\Repository\LeadSnapshotRepo;
use Doctrine\DBAL\Connection;

/**
 * Service for source attribution calculations
 */
class AttributionService
{
    private Connection $db;
    private SourceAttributionRepo $sourceAttributionRepo;
    private LeadSnapshotRepo $leadSnapshotRepo;

    public function __construct(
        Connection $db,
        SourceAttributionRepo $sourceAttributionRepo,
        LeadSnapshotRepo $leadSnapshotRepo
    ) {
        $this->db = $db;
        $this->sourceAttributionRepo = $sourceAttributionRepo;
        $this->leadSnapshotRepo = $leadSnapshotRepo;
    }

    /**
     * Get attribution report
     */
    public function getAttributionReport(
        string $type,
        ?\DateTimeInterface $from = null,
        ?\DateTimeInterface $to = null,
        ?int $sourceId = null,
        int $limit = 50,
        int $offset = 0
    ): array {
        // Validate type
        if (!in_array($type, ['first_touch', 'last_touch'])) {
            throw new \InvalidArgumentException('Invalid attribution type: ' . $type);
        }

        // Get source metrics
        $sources = $this->sourceAttributionRepo->getAttributionMetrics($type, $from, $to);

        // Apply filters
        if ($sourceId !== null) {
            $sources = array_filter($sources, fn($s) => $s['source_id'] == $sourceId);
        }

        // Pagination
        $total = count($sources);
        $sources = array_slice($sources, $offset, $limit);

        // Enrich with breakdown
        $enrichedSources = [];
        foreach ($sources as $source) {
            $enriched = $this->enrichSourceMetrics($source, $from, $to);
            $enrichedSources[] = $enriched;
        }

        // Build summary
        $summary = $this->buildSummary($sources);

        return [
            'type' => $type,
            'period' => [
                'from' => $from?->format('Y-m-d'),
                'to' => $to?->format('Y-m-d'),
            ],
            'sources' => $enrichedSources,
            'summary' => $summary,
            'pagination' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'has_more' => ($offset + $limit) < $total,
            ],
        ];
    }

    /**
     * Enrich source with additional metrics
     */
    private function enrichSourceMetrics(array $source, ?\DateTimeInterface $from, ?\DateTimeInterface $to): array
    {
        $sourceId = $source['source_id'];
        $leadCount = (int)$source['lead_count'];
        $wonCount = (int)$source['won_count'];

        // Get breakdown by pipeline
        $byPipeline = $this->getBreakdownByPipeline($sourceId, $from, $to);

        // Get UTM breakdown
        $byUtm = $this->getBreakdownByUtm($sourceId, 'source', $from, $to);

        // Calculate average time to close
        $avgTimeToClose = $this->getAverageTimeToClose($sourceId, $from, $to);

        return [
            'source_id' => $sourceId,
            'source_name' => $source['source_name'],
            'metrics' => [
                'lead_count' => $leadCount,
                'total_revenue' => (float)$source['total_revenue'],
                'avg_deal_value' => (float)$source['avg_deal_value'],
                'won_count' => $wonCount,
                'conversion_rate' => $leadCount > 0 ? round($wonCount / $leadCount, 4) : 0,
                'avg_time_to_close_days' => $avgTimeToClose,
            ],
            'breakdown' => [
                'by_pipeline' => $byPipeline,
                'by_utm_source' => $byUtm,
            ],
        ];
    }

    /**
     * Get breakdown by pipeline
     */
    private function getBreakdownByPipeline(int $sourceId, ?\DateTimeInterface $from, ?\DateTimeInterface $to): array
    {
        $params = ['source_id' => $sourceId];
        $conditions = ['sa.source_id = :source_id'];

        if ($from !== null) {
            $conditions[] = 'sa.first_touch_at >= :from';
            $params['from'] = $from->format('Y-m-d H:i:s');
        }

        if ($to !== null) {
            $conditions[] = 'sa.first_touch_at <= :to';
            $params['to'] = $to->format('Y-m-d H:i:s');
        }

        $where = implode(' AND ', $conditions);

        $sql = <<<SQL
        SELECT 
            ps.pipeline_id,
            ps.pipeline_name,
            COUNT(DISTINCT sa.lead_id) as lead_count,
            COALESCE(SUM(ls.price), 0) as revenue
        FROM source_attribution sa
        LEFT JOIN lead_snapshots ls ON sa.lead_id = ls.lead_id AND ls.is_deleted = false
        LEFT JOIN pipeline_stages ps ON ls.pipeline_id = ps.pipeline_id
        WHERE {$where}
        GROUP BY ps.pipeline_id, ps.pipeline_name
        ORDER BY revenue DESC
        SQL;

        return $this->db->fetchAllAssociative($sql, $params);
    }

    /**
     * Get breakdown by UTM parameter
     */
    private function getBreakdownByUtm(int $sourceId, string $field, ?\DateTimeInterface $from, ?\DateTimeInterface $to): array
    {
        return $this->sourceAttributionRepo->aggregateByUtm($field, $from, $to);
    }

    /**
     * Get average time from first touch to deal close
     */
    private function getAverageTimeToClose(int $sourceId, ?\DateTimeInterface $from, ?\DateTimeInterface $to): float
    {
        $params = ['source_id' => $sourceId];
        $conditions = [
            'sa.source_id = :source_id',
            'ls.is_deleted = false',
            'ps.is_final = true',
            'ps.final_type = \'won\'',
        ];

        if ($from !== null) {
            $conditions[] = 'sa.first_touch_at >= :from';
            $params['from'] = $from->format('Y-m-d H:i:s');
        }

        if ($to !== null) {
            $conditions[] = 'sa.first_touch_at <= :to';
            $params['to'] = $to->format('Y-m-d H:i:s');
        }

        $where = implode(' AND ', $conditions);

        $sql = <<<SQL
        SELECT AVG(EXTRACT(EPOCH FROM (ls.closed_at - sa.first_touch_at)) / 86400) as avg_days
        FROM source_attribution sa
        JOIN lead_snapshots ls ON sa.lead_id = ls.lead_id
        JOIN pipeline_stages ps ON ls.pipeline_id = ps.pipeline_id AND ls.status_id = ps.status_id
        WHERE {$where}
        AND ls.closed_at IS NOT NULL
        SQL;

        $result = $this->db->fetchOne($sql, $params);

        return round((float)($result ?? 0), 1);
    }

    /**
     * Build summary statistics
     */
    private function buildSummary(array $sources): array
    {
        $totalLeads = 0;
        $totalRevenue = 0.0;
        $topSource = null;

        foreach ($sources as $source) {
            $totalLeads += (int)$source['lead_count'];
            $totalRevenue += (float)$source['total_revenue'];

            if ($topSource === null || (float)$source['total_revenue'] > $topSource['revenue']) {
                $topSource = [
                    'source_id' => $source['source_id'],
                    'source_name' => $source['source_name'],
                    'revenue' => (float)$source['total_revenue'],
                    'revenue_share' => 0,
                ];
            }
        }

        if ($topSource !== null && $totalRevenue > 0) {
            $topSource['revenue_share'] = round($topSource['revenue'] / $totalRevenue, 4);
        }

        return [
            'total_leads' => $totalLeads,
            'total_revenue' => round($totalRevenue, 2),
            'top_source' => $topSource,
        ];
    }

    /**
     * Get unsorted category report
     */
    public function getUnsortedReport(?\DateTimeInterface $from = null, ?\DateTimeInterface $to = null): array
    {
        $categories = $this->sourceAttributionRepo->getByCategory('', $from, $to);

        $totalLeads = 0;
        foreach ($categories as $cat) {
            $totalLeads += (int)$cat['lead_count'];
        }

        return [
            'period' => [
                'from' => $from?->format('Y-m-d'),
                'to' => $to?->format('Y-m-d'),
            ],
            'categories' => array_map(function ($cat) use ($totalLeads) {
                return [
                    'category' => $cat['unsorted_category'],
                    'lead_count' => (int)$cat['lead_count'],
                    'unsorted_count' => (int)$cat['unsorted_count'],
                    'share' => $totalLeads > 0 ? round((int)$cat['lead_count'] / $totalLeads, 4) : 0,
                ];
            }, $categories),
            'summary' => [
                'total_leads' => $totalLeads,
                'total_categories' => count($categories),
            ],
        ];
    }

    /**
     * First-touch attribution
     */
    public function firstTouch(int $leadId): ?int
    {
        $attribution = $this->sourceAttributionRepo->findByLeadId($leadId);

        if ($attribution === null) {
            return null;
        }

        return $attribution->getSourceId();
    }

    /**
     * Last-touch attribution
     */
    public function lastTouch(int $leadId): ?int
    {
        $attribution = $this->sourceAttributionRepo->findByLeadId($leadId);

        if ($attribution === null) {
            return null;
        }

        return $attribution->getSourceId();
    }
}