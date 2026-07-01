<?php

declare(strict_types=1);

namespace Analytics\Webhook;

use Analytics\Repository\WebhookLogRepo;
use Analytics\Repository\LeadSnapshotRepo;
use Analytics\Logger\AnalyticsLogger;
use Analytics\Exception\WebhookProcessingException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Main webhook handler
 */
class Handler
{
    private IdempotencyChecker $idempotencyChecker;
    private ProcessorFactory $processorFactory;
    private WebhookLogRepo $webhookLogRepo;
    private LeadSnapshotRepo $leadSnapshotRepo;
    private AnalyticsLogger $logger;
    private string $clientSecret;

    public function __construct(
        IdempotencyChecker $idempotencyChecker,
        ProcessorFactory $processorFactory,
        WebhookLogRepo $webhookLogRepo,
        LeadSnapshotRepo $leadSnapshotRepo,
        AnalyticsLogger $logger,
        string $clientSecret
    ) {
        $this->idempotencyChecker = $idempotencyChecker;
        $this->processorFactory = $processorFactory;
        $this->webhookLogRepo = $webhookLogRepo;
        $this->leadSnapshotRepo = $leadSnapshotRepo;
        $this->logger = $logger;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Handle incoming webhook
     */
    public function handle(array $payload, ?string $signature = null): array
    {
        $start = microtime(true);

        // Extract webhook info
        $webhookInfo = $payload['webhook'] ?? [];
        $eventType = $webhookInfo['type'] ?? 'unknown';
        $webhookId = $this->generateWebhookId($payload);
        $entityInfo = $this->extractEntityInfo($payload);

        $this->logger->webhook($eventType, (string)($entityInfo['entity_id'] ?? 'unknown'), [
            'webhook_id' => $webhookId,
        ]);

        // Check idempotency
        if ($this->idempotencyChecker->isProcessed($webhookId)) {
            $this->logger->webhook($eventType, (string)($entityInfo['entity_id'] ?? 'unknown'), [
                'status' => 'duplicate',
                'webhook_id' => $webhookId,
            ]);
            return ['status' => 'ok', 'webhook_id' => $webhookId, 'duplicate' => true];
        }

        // Reserve webhook (idempotency lock)
        $reserved = $this->idempotencyChecker->reserve(
            $webhookId,
            $eventType,
            $entityInfo['entity_type'] ?? 'unknown',
            $entityInfo['entity_id'] ?? 0,
            $payload
        );

        if (!$reserved) {
            // Already being processed or processed
            return ['status' => 'ok', 'webhook_id' => $webhookId, 'duplicate' => true];
        }

        try {
            // Process webhook
            $this->processWebhook($eventType, $payload);

            // Mark as processed
            $this->idempotencyChecker->markProcessed($webhookId);

            $duration = microtime(true) - $start;
            $this->logger->performance('webhook.' . $eventType, $duration, [
                'webhook_id' => $webhookId,
            ]);

            return [
                'status' => 'ok',
                'webhook_id' => $webhookId,
                'processed' => true,
                'duration' => $duration,
            ];

        } catch (\Exception $e) {
            $this->idempotencyChecker->markFailed($webhookId, $e->getMessage());

            $this->logger->apiError($e->getMessage(), [
                'webhook_id' => $webhookId,
                'event_type' => $eventType,
            ]);

            throw new WebhookProcessingException(
                $e->getMessage(),
                $webhookId,
                $eventType,
                $this->isRetryableError($e)
            );
        }
    }

    /**
     * Process webhook by event type
     */
    private function processWebhook(string $eventType, array $payload): void
    {
        $processor = $this->processorFactory->createProcessor($eventType);

        if ($processor === null) {
            $this->logger->webhook($eventType, 'unknown', [
                'status' => 'no_processor',
            ]);
            return;
        }

        $processor->process($payload);
    }

    /**
     * Extract entity info from payload
     */
    private function extractEntityInfo(array $payload): array
    {
        $data = $payload['payload'] ?? [];

        // Detect entity type from payload structure
        if (isset($data['id']) && isset($data['pipeline_id'])) {
            return [
                'entity_type' => 'lead',
                'entity_id' => (int)$data['id'],
            ];
        }

        if (isset($data['customer_id'])) {
            return [
                'entity_type' => 'customer',
                'entity_id' => (int)$data['customer_id'],
            ];
        }

        if (isset($data['contact_id'])) {
            return [
                'entity_type' => 'contact',
                'entity_id' => (int)$data['contact_id'],
            ];
        }

        return [
            'entity_type' => 'unknown',
            'entity_id' => 0,
        ];
    }

    /**
     * Generate webhook ID from payload
     */
    private function generateWebhookId(array $payload): string
    {
        // Use timestamp and entity info as ID
        $webhook = $payload['webhook'] ?? [];
        $data = $payload['payload'] ?? [];

        return sprintf(
            '%s_%s_%s_%d',
            $webhook['type'] ?? 'unknown',
            $this->extractEntityType($payload),
            $data['id'] ?? $data['customer_id'] ?? '0',
            $webhook['timestamp'] ?? time()
        );
    }

    /**
     * Extract entity type from payload
     */
    private function extractEntityType(array $payload): string
    {
        $data = $payload['payload'] ?? [];

        if (isset($data['pipeline_id']) || isset($data['old_pipeline_id'])) {
            return 'lead';
        }

        if (isset($data['customer_id']) && isset($data['price'])) {
            return 'transaction';
        }

        if (isset($data['contact_id'])) {
            return 'contact';
        }

        return 'unknown';
    }

    /**
     * Check if error is retryable
     */
    private function isRetryableError(\Exception $e): bool
    {
        // Network errors, timeouts are retryable
        // Validation errors are not retryable
        if ($e instanceof \AmoCRM\Exceptions\AmoCRMApiException) {
            return in_array($e->getCode(), [429, 500, 502, 503, 504]);
        }

        return true;
    }
}
