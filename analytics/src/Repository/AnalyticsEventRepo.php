<?php

declare(strict_types=1);

namespace Analytics\Repository;

use Carbon\Carbon;
use Doctrine\DBAL\Connection;

/**
 * Repository for analytics events
 */
class AnalyticsEventRepo
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Insert analytics event
     */
    public function insert(array $data): int
    {
        $sql = <<<SQL
        INSERT INTO analytics_events (
            entity_type, entity_id, event_type,
            old_value, new_value, payload, created_at
        ) VALUES (
            :entity_type, :entity_id, :event_type,
            :old_value, :new_value, :payload, :created_at
        )
        RETURNING id
        SQL;

        $result = $this->db->executeQuery($sql, [
            'entity_type' => $data['entity_type'],
            'entity_id' => $data['entity_id'],
            'event_type' => $data['event_type'],
            'old_value' => isset($data['old_value']) ? json_encode($data['old_value']) : null,
            'new_value' => isset($data['new_value']) ? json_encode($data['new_value']) : null,
            'payload' => isset($data['payload']) ? json_encode($data['payload']) : null,
            'created_at' => isset($data['created_at']) 
                ? ($data['created_at'] instanceof Carbon ? $data['created_at']->toDateTimeString() : $data['created_at'])
                : null,
        ]);

        return (int)$result->fetchOne();
    }

    /**
     * Find events by entity
     */
    public function findByEntity(string $entityType, int $entityId): array
    {
        $sql = <<<SQL
        SELECT * FROM analytics_events
        WHERE entity_type = :entity_type AND entity_id = :entity_id
        ORDER BY created_at DESC
        SQL;

        return $this->db->fetchAllAssociative($sql, [
            'entity_type' => $entityType,
            'entity_id' => $entityId,
        ]);
    }

    /**
     * Find events by type
     */
    public function findByType(string $eventType, ?int $limit = 100): array
    {
        $sql = <<<SQL
        SELECT * FROM analytics_events
        WHERE event_type = :event_type
        ORDER BY created_at DESC
        LIMIT :limit
        SQL;

        return $this->db->fetchAllAssociative($sql, [
            'event_type' => $eventType,
            'limit' => $limit,
        ]);
    }

    /**
     * Get status change events for entity
     */
    public function getStatusChanges(int $entityId): array
    {
        $sql = <<<SQL
        SELECT * FROM analytics_events
        WHERE entity_type = 'lead'
        AND entity_id = :entity_id
        AND event_type = 'lead_status_changed'
        ORDER BY created_at ASC
        SQL;

        return $this->db->fetchAllAssociative($sql, ['entity_id' => $entityId]);
    }

    /**
     * Get events within date range
     */
    public function getEventsInRange(
        string $entityType,
        Carbon $from,
        Carbon $to,
        ?int $limit = 1000
    ): array {
        $sql = <<<SQL
        SELECT * FROM analytics_events
        WHERE entity_type = :entity_type
        AND created_at >= :from AND created_at <= :to
        ORDER BY created_at DESC
        LIMIT :limit
        SQL;

        return $this->db->fetchAllAssociative($sql, [
            'entity_type' => $entityType,
            'from' => $from->toDateTimeString(),
            'to' => $to->toDateTimeString(),
            'limit' => $limit,
        ]);
    }
}
