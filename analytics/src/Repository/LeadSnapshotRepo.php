<?php

declare(strict_types=1);

namespace Analytics\Repository;

use Analytics\Models\LeadSnapshot;
use Doctrine\DBAL\Connection;

/**
 * Repository for lead snapshots
 */
class LeadSnapshotRepo
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Insert a lead snapshot
     */
    public function insert(LeadSnapshot $snapshot): int
    {
        $sql = <<<SQL
        INSERT INTO lead_snapshots (
            lead_id, pipeline_id, status_id, status_name, price,
            source_id, responsible_user_id, contact_ids, company_id,
            loss_reason_id, tags, custom_fields,
            created_at, updated_at, closed_at, snapshot_at, is_deleted
        ) VALUES (
            :lead_id, :pipeline_id, :status_id, :status_name, :price,
            :source_id, :responsible_user_id, :contact_ids, :company_id,
            :loss_reason_id, :tags, :custom_fields,
            :created_at, :updated_at, :closed_at, :snapshot_at, :is_deleted
        )
        RETURNING id
        SQL;

        $result = $this->db->executeQuery($sql, [
            'lead_id' => $snapshot->getLeadId(),
            'pipeline_id' => $snapshot->getPipelineId(),
            'status_id' => $snapshot->getStatusId(),
            'status_name' => $snapshot->getStatusName(),
            'price' => $snapshot->getPrice(),
            'source_id' => $snapshot->getSourceId(),
            'responsible_user_id' => $snapshot->getResponsibleUserId(),
            'contact_ids' => json_encode($snapshot->getContactIds()),
            'company_id' => $snapshot->getCompanyId(),
            'loss_reason_id' => $snapshot->getLossReasonId(),
            'tags' => json_encode($snapshot->getTags()),
            'custom_fields' => json_encode($snapshot->getCustomFields()),
            'created_at' => $snapshot->getCreatedAt()?->toDateTimeString(),
            'updated_at' => $snapshot->getUpdatedAt()?->toDateTimeString(),
            'closed_at' => $snapshot->getClosedAt()?->toDateTimeString(),
            'snapshot_at' => $snapshot->getSnapshotAt()->toDateTimeString(),
            'is_deleted' => $snapshot->isDeleted(),
        ]);

        return (int)$result->fetchOne();
    }

    /**
     * Insert multiple snapshots (batch)
     */
    public function insertBatch(array $snapshots): int
    {
        $count = 0;

        $this->db->beginTransaction();
        try {
            foreach ($snapshots as $snapshot) {
                $this->insert($snapshot);
                $count++;
            }
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }

        return $count;
    }

    /**
     * Find latest snapshot for a lead
     */
    public function findLatestByLeadId(int $leadId): ?LeadSnapshot
    {
        $sql = <<<SQL
        SELECT * FROM lead_snapshots
        WHERE lead_id = :lead_id
        ORDER BY snapshot_at DESC
        LIMIT 1
        SQL;

        $row = $this->db->fetchAssociative($sql, ['lead_id' => $leadId]);

        if ($row === false) {
            return null;
        }

        return $this->hydrateFromRow($row);
    }

    /**
     * Find all snapshots for a lead
     */
    public function findByLeadId(int $leadId): array
    {
        $sql = <<<SQL
        SELECT * FROM lead_snapshots
        WHERE lead_id = :lead_id
        ORDER BY snapshot_at ASC
        SQL;

        $rows = $this->db->fetchAllAssociative($sql, ['lead_id' => $leadId]);

        return array_map(fn($row) => $this->hydrateFromRow($row), $rows);
    }

    /**
     * Get funnel metrics for a pipeline
     */
    public function getFunnelMetrics(int $pipelineId, ?\DateTimeInterface $from = null, ?\DateTimeInterface $to = null): array
    {
        $params = ['pipeline_id' => $pipelineId];
        $conditions = ['pipeline_id = :pipeline_id'];

        if ($from !== null) {
            $conditions[] = 'snapshot_at >= :from';
            $params['from'] = $from->format('Y-m-d H:i:s');
        }

        if ($to !== null) {
            $conditions[] = 'snapshot_at <= :to';
            $params['to'] = $to->format('Y-m-d H:i:s');
        }

        $where = implode(' AND ', $conditions);

        $sql = <<<SQL
        SELECT 
            status_id,
            status_name,
            COUNT(DISTINCT lead_id) as lead_count,
            SUM(price) as total_revenue,
            AVG(price) as avg_price,
            MIN(snapshot_at) as first_seen,
            MAX(snapshot_at) as last_seen
        FROM lead_snapshots
        WHERE {$where}
        GROUP BY status_id, status_name
        ORDER BY MIN(snapshot_at) ASC
        SQL;

        return $this->db->fetchAllAssociative($sql, $params);
    }

    /**
     * Hydrate model from database row
     */
    private function hydrateFromRow(array $row): LeadSnapshot
    {
        $row['contact_ids'] = json_decode($row['contact_ids'] ?? '[]', true);
        $row['tags'] = json_decode($row['tags'] ?? '[]', true);
        $row['custom_fields'] = json_decode($row['custom_fields'] ?? '{}', true);

        return LeadSnapshot::fromArray($row);
    }

    /**
     * Check if lead exists in snapshots
     */
    public function existsByLeadId(int $leadId): bool
    {
        $sql = "SELECT 1 FROM lead_snapshots WHERE lead_id = :lead_id LIMIT 1";
        return (bool)$this->db->fetchOne($sql, ['lead_id' => $leadId]);
    }

    /**
     * Mark lead as deleted
     */
    public function markDeleted(int $leadId, \DateTimeInterface $at): int
    {
        $sql = <<<SQL
        INSERT INTO lead_snapshots (lead_id, is_deleted, snapshot_at)
        VALUES (:lead_id, true, :at)
        SQL;

        return $this->db->executeStatement($sql, [
            'lead_id' => $leadId,
            'at' => $at->format('Y-m-d H:i:s'),
        ]);
    }
}
