<?php

declare(strict_types=1);

namespace Analytics\Webhook;

use Analytics\Repository\WebhookLogRepo;
use Analytics\Logger\AnalyticsLogger;
use Analytics\Exception\WebhookProcessingException;
use Doctrine\DBAL\Connection;

/**
 * Idempotency checker using webhook logs
 */
class IdempotencyChecker
{
    private WebhookLogRepo $repo;
    private AnalyticsLogger $logger;

    public function __construct(WebhookLogRepo $repo, AnalyticsLogger $logger)
    {
        $this->repo = $repo;
        $this->logger = $logger;
    }

    /**
     * Check if webhook was already processed
     */
    public function isProcessed(string $webhookId): bool
    {
        $log = $this->repo->findByWebhookId($webhookId);

        if ($log === null) {
            return false;
        }

        return $log['status'] === 'processed';
    }

    /**
     * Check if webhook is currently being processed
     */
    public function isProcessing(string $webhookId): bool
    {
        $log = $this->repo->findByWebhookId($webhookId);

        if ($log === null) {
            return false;
        }

        return $log['status'] === 'processing';
    }

    /**
     * Reserve webhook for processing (idempotency lock)
     */
    public function reserve(string $webhookId, string $eventType, string $entityType, int $entityId, array $payload): bool
    {
        try {
            return $this->repo->insert([
                'webhook_id' => $webhookId,
                'event_type' => $eventType,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'payload' => $payload,
                'status' => 'processing',
            ]);
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            // Already exists, check status
            $this->logger->webhook($eventType, (string)$entityId, [
                'webhook_id' => $webhookId,
                'status' => 'duplicate_reserve_attempt',
            ]);
            return false;
        }
    }

    /**
     * Mark webhook as processed
     */
    public function markProcessed(string $webhookId): void
    {
        $this->repo->updateStatus($webhookId, 'processed');
    }

    /**
     * Mark webhook as failed
     */
    public function markFailed(string $webhookId, string $errorMessage): void
    {
        $this->repo->updateStatus($webhookId, 'failed', $errorMessage);
    }

    /**
     * Reset webhook to pending for retry
     */
    public function resetToPending(string $webhookId): void
    {
        $this->repo->resetToPending($webhookId);
    }
}
