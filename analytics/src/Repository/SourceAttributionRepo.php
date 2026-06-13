<?php

declare(strict_types=1);

namespace Analytics\Repository;

use Analytics\Models\SourceAttribution;
use Doctrine\DBAL\Connection;

/**
 * Repository for source attribution
 */
class SourceAttributionRepo
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Insert attribution
     */
    public function insert(SourceAttribution $attr): int
    {
        $sql = <<<SQL
        INSERT INTO source_attribution (
            lead_id, unsorted_id, unsorted_category, source_id,
            source_name, source_external_id,
            first_touch_at, last_touch_at,
            first_touch_value, last_touch_value
        ) VALUES (
            :lead_id, :unsorted_id, :unsorted_category, :source_id,
            :source_name, :source_external_id,
            :first_touch_at, :last_touch_at,
            :first_touch_value, :last_touch_value
        )
        ON CONFLICT (lead_id) DO UPDATE SET
            last_touch_at = EXCLUDED.last_touch_at,
            last_touch_value = EXCLUDED.last_touch_value
        RETURNING id
        SQL;

        $result = $this->db->executeQuery($sql, [
            'lead_id' => $attr->getLeadId(),
            'unsorted_id' => $attr->getUnsortedId(),
            'unsorted_category' => $attr->getUnsortedCategory(),
            'source_id' => $attr->getSourceId(),
            'source_name' => $attr->getSourceName(),
            'source_external_id' => $attr->getSourceExternalId(),
            'first_touch_at' => $attr->getFirstTouchAt()?->toDateTimeString(),
            'last_touch_at' => $attr->getLastTouchAt()?->toDateTimeString(),
            'first_touch_value' => $attr->getFirstTouchValue(),
            'last_touch_value' => $attr->getLastTouchValue(),
        ]);

        return (int)$result->fetchOne();
    }

    /**
     * Find by lead ID
     */
    public function findByLeadId(int $leadId): ?SourceAttribution
    {
        $sql = 'SELECT * FROM source_attribution WHERE lead_id = :lead_id';
        $row = $this->db->fetchAssociative($sql, ['lead_id' => $leadId]);

        if ($row === false) {
            return null;
        }

        return SourceAttribution::fromArray($row);
    }

    /**
     * Get source attribution metrics (aggregation)
     */
    public function getAttributionMetrics(string $type, ?\DateTimeInterface $from = null, ?\DateTimeInterface $to = null): array
    {
        $column = $type === 'first_touch' ? 'first_touch_at' : 'last_touch_at';
        $valueColumn = $type === 'first_touch' ? 'first_touch_value' : 'last_touch_value';

        $params = [];
        $conditions = [];

        if ($from !== null) {
            $conditions[] = "{$column} >= :from";
            $params['from'] = $from->format('Y-m-d H:i:s');
        }

        if ($to !== null) {
            $conditions[] = "{$column} <= :to";
            $params['to'] = $to->format('Y-m-d H:i:s');
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = <<<SQL
        SELECT 
            sa.source_id,
            sa.source_name,
            COUNT(DISTINCT sa.lead_id) as lead_count,
            COALESCE(SUM(ls.price), 0) as total_revenue,
            COALESCE(AVG(ls.price), 0) as avg_deal_value,
            COUNT(DISTINCT CASE WHEN ps.is_final AND ps.final_type = 'won' THEN sa.lead_id END) as won_count
        FROM source_attribution sa
        LEFT JOIN lead_snapshots ls ON sa.lead_id = ls.lead_id AND ls.is_deleted = FALSE
        LEFT JOIN pipeline_stages ps ON ls.pipeline_id = ps.pipeline_id AND ls.status_id = ps.status_id
        {$where}
        GROUP BY sa.source_id, sa.source_name
        ORDER BY total_revenue DESC
        SQL;

        return $this->db->fetchAllAssociative($sql, $params);
    }

    /**
     * Get attribution by unsorted category
     */
    public function getByCategory(string $category, ?\DateTimeInterface $from = null, ?\DateTimeInterface $to = null): array
    {
        $params = ['category' => $category];
        $conditions = ['unsorted_category = :category'];

        if ($from !== null) {
            $conditions[] = 'first_touch_at >= :from';
            $params['from'] = $from->format('Y-m-d H:i:s');
        }

        if ($to !== null) {
            $conditions[] = 'first_touch_at <= :to';
            $params['to'] = $to->format('Y-m-d H:i:s');
        }

        $where = 'WHERE ' . implode(' AND ', $conditions);

        $sql = <<<SQL
        SELECT 
            unsorted_category,
            COUNT(DISTINCT lead_id) as lead_count,
            COUNT(DISTINCT unsorted_id) as unsorted_count
        FROM source_attribution
        {$where}
        GROUP BY unsorted_category
        ORDER BY lead_count DESC
        SQL;

        return $this->db->fetchAllAssociative($sql, $params);
    }

    /**
     * Aggregate by UTM parameters from custom fields
     */
    public function aggregateByUtm(string $field, ?\DateTimeInterface $from = null, ?\DateTimeInterface $to = null): array
    {
        $params = [];
        $conditions = [];

        if ($from !== null) {
            $conditions[] = 'snapshot_at >= :from';
            $params['from'] = $from->format('Y-m-d H:i:s');
        }

        if ($to !== null) {
            $conditions[] = 'snapshot_at <= :to';
            $params['to'] = $to->format('Y-m-d H:i:s');
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        // Extract UTM from JSON custom_fields
        $sql = <<<SQL
        SELECT 
            (custom_fields->>'utm_{$field}') as utm_value,
            COUNT(DISTINCT lead_id) as lead_count,
            SUM(price) as total_revenue
        FROM lead_snapshots
        {$where}
        AND custom_fields->>'utm_{$field}' IS NOT NULL
        GROUP BY utm_value
        ORDER BY total_revenue DESC
        SQL;

        return $this->db->fetchAllAssociative($sql, $params);
    }
}