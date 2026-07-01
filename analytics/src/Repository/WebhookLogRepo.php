<?php

declare(strict_types=1);

namespace Analytics\Repository;

use Doctrine\DBAL\Connection;

/**
 * Repository for webhook logs (idempotency tracking)
 */
class WebhookLogRepo
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Find webhook log by webhook ID
     */
    public function findByWebhookId(string $webhookId): ?array
    {
        $sql = 'SELECT * FROM webhook_logs WHERE webhook_id = :webhook_id';
        $row = $this->db->fetchAssociative($sql, ['webhook_id' => $webhookId]);

        return $row ?: null;
    }

    /**
     * Insert new webhook log
     */
    public function insert(array $data): bool
    {
        $sql = <<<SQL
        INSERT INTO webhook_logs (
            webhook_id, event_type, entity_type, entity_id, payload, status
        ) VALUES (
            :webhook_id, :event_type, :entity_type, :entity_id, :payload, :status
        )
        RETURNING id
        SQL;

        try {
            $result = $this->db->executeQuery($sql, [
                'webhook_id' => $data['webhook_id'],
                'event_type' => $data['event_type'],
                'entity_type' => $data['entity_type'],
                'entity_id' => $data['entity_id'],
                'payload' => json_encode($data['payload']),
                'status' => $data['status'] ?? 'pending',
            ]);

            return (bool)$result->fetchOne();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update status of webhook log
     */
    public function updateStatus(string $webhookId, string $status, ?string $errorMessage = null): int
    {
        $params = [
            'webhook_id' => $webhookId,
            'status' => $status,
            'processed_at' => (new \DateTime())->format('Y-m-d H:i:s'),
        ];

        $sql = 'UPDATE webhook_logs SET status = :status, processed_at = :processed_at';

        if ($errorMessage !== null) {
            $sql .= ', error_message = :error_message';
            $params['error_message'] = $errorMessage;
        }

        $sql .= ' WHERE webhook_id = :webhook_id';

        return $this->db->executeStatement($sql, $params);
    }

    /**
     * Reset webhook to pending for retry
     */
    public function resetToPending(string $webhookId): int
    {
        $sql = <<<SQL
        UPDATE webhook_logs
        SET status = 'pending', processed_at = NULL
        WHERE webhook_id = :webhook_id
        SQL;

        return $this->db->executeStatement($sql, ['webhook_id' => $webhookId]);
    }

    /**
     * Increment retry count
     */
    public function incrementRetry(string $webhookId): int
    {
        $sql = <<<SQL
        UPDATE webhook_logs
        SET retry_count = retry_count + 1, last_retry_at = NOW()
        WHERE webhook_id = :webhook_id
        SQL;

        return $this->db->executeStatement($sql, ['webhook_id' => $webhookId]);
    }

    /**
     * Get pending webhooks for retry
     */
    public function getPending(int $limit = 100): array
    {
        $sql = <<<SQL
        SELECT * FROM webhook_logs
        WHERE status = 'pending'
        AND retry_count < 3
        ORDER BY received_at ASC
        LIMIT :limit
        SQL;

        return $this->db->fetchAllAssociative($sql, ['limit' => $limit]);
    }

    /**
     * Get failed webhooks
     */
    public function getFailed(int $limit = 100): array
    {
        $sql = <<<SQL
        SELECT * FROM webhook_logs
        WHERE status = 'failed'
        ORDER BY received_at DESC
        LIMIT :limit
        SQL;

        return $this->db->fetchAllAssociative($sql, ['limit' => $limit]);
    }

    /**
     * Delete old processed webhooks (cleanup)
     */
    public function deleteOldProcessed(int $daysOld = 7): int
    {
        $sql = <<<SQL
        DELETE FROM webhook_logs
        WHERE status = 'processed'
        AND processed_at < NOW() - INTERVAL ':days days'
        SQL;

        return $this->db->executeStatement($sql, ['days' => $daysOld]);
    }
}
