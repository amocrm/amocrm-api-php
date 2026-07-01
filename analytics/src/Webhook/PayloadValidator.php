<?php

declare(strict_types=1);

namespace Analytics\Webhook;

use Analytics\Exception\WebhookProcessingException;

/**
 * Validates webhook payloads
 */
class PayloadValidator
{
    /**
     * Supported event types
     */
    private const SUPPORTED_EVENTS = [
        'lead_added',
        'lead_status_changed',
        'lead_updated',
        'lead_deleted',
        'lead_restored',
        'contact_added',
        'contact_updated',
        'contact_deleted',
        'company_added',
        'company_updated',
        'company_deleted',
        'customer_added',
        'customer_updated',
        'customer_deleted',
        'customer_transaction_added',
        'customer_transaction_updated',
        'unsorted_added',
        'task_added',
        'task_updated',
        'note_added',
        'call_in',
        'call_out',
    ];

    /**
     * Required fields per event type
     */
    private const REQUIRED_FIELDS = [
        'lead_added' => ['id', 'pipeline_id', 'status_id'],
        'lead_status_changed' => ['id', 'old_status_id', 'new_status_id'],
        'lead_updated' => ['id'],
        'lead_deleted' => ['id'],
        'contact_added' => ['id'],
        'contact_updated' => ['id'],
        'contact_deleted' => ['id'],
        'company_added' => ['id'],
        'company_updated' => ['id'],
        'company_deleted' => ['id'],
        'customer_added' => ['id'],
        'customer_updated' => ['id'],
        'customer_deleted' => ['id'],
        'customer_transaction_added' => ['id', 'customer_id'],
        'unsorted_added' => ['id', 'category'],
        'default' => ['id'],
    ];

    /**
     * Validate webhook payload
     *
     * @throws WebhookProcessingException
     */
    public function validate(array $payload): void
    {
        // Check webhook section exists
        if (!isset($payload['webhook'])) {
            throw new WebhookProcessingException(
                'Missing webhook section in payload',
                '',
                'unknown',
                false,
                0,
                ['reason' => 'missing_webhook_section']
            );
        }

        // Check event type
        $eventType = $payload['webhook']['type'] ?? null;
        if ($eventType === null) {
            throw new WebhookProcessingException(
                'Missing webhook type',
                '',
                'unknown',
                false,
                0,
                ['reason' => 'missing_webhook_type']
            );
        }

        // Check if event type is supported
        if (!$this->isSupported($eventType)) {
            throw new WebhookProcessingException(
                "Unsupported event type: {$eventType}",
                '',
                $eventType,
                false,
                0,
                ['reason' => 'unsupported_event_type']
            );
        }

        // Check payload section exists
        if (!isset($payload['payload'])) {
            throw new WebhookProcessingException(
                'Missing payload section',
                '',
                $eventType,
                false,
                0,
                ['reason' => 'missing_payload_section']
            );
        }

        // Validate required fields
        $this->validateRequiredFields($eventType, $payload['payload']);
    }

    /**
     * Validate required fields for event type
     *
     * @throws WebhookProcessingException
     */
    private function validateRequiredFields(string $eventType, array $data): void
    {
        $requiredFields = self::REQUIRED_FIELDS[$eventType] ?? self::REQUIRED_FIELDS['default'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            throw new WebhookProcessingException(
                'Missing required fields: ' . implode(', ', $missingFields),
                '',
                $eventType,
                false,
                0,
                [
                    'reason' => 'missing_required_fields',
                    'missing_fields' => $missingFields,
                ]
            );
        }
    }

    /**
     * Check if event type is supported
     */
    public function isSupported(string $eventType): bool
    {
        return in_array($eventType, self::SUPPORTED_EVENTS, true);
    }

    /**
     * Get list of supported event types
     */
    public function getSupportedEvents(): array
    {
        return self::SUPPORTED_EVENTS;
    }

    /**
     * Extract entity info from validated payload
     */
    public function extractEntityInfo(array $payload): array
    {
        $eventType = $payload['webhook']['type'] ?? 'unknown';
        $data = $payload['payload'] ?? [];

        return [
            'event_type' => $eventType,
            'entity_type' => $this->getEntityType($eventType),
            'entity_id' => $data['id'] ?? $data['customer_id'] ?? 0,
        ];
    }

    /**
     * Map event type to entity type
     */
    private function getEntityType(string $eventType): string
    {
        if (str_starts_with($eventType, 'lead_')) {
            return 'lead';
        }

        if (str_starts_with($eventType, 'contact_')) {
            return 'contact';
        }

        if (str_starts_with($eventType, 'company_')) {
            return 'company';
        }

        if (str_starts_with($eventType, 'customer')) {
            return 'customer';
        }

        if ($eventType === 'unsorted_added') {
            return 'unsorted';
        }

        if (str_starts_with($eventType, 'call_')) {
            return 'call';
        }

        if (str_starts_with($eventType, 'task_')) {
            return 'task';
        }

        return 'unknown';
    }
}
