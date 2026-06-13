<?php

declare(strict_types=1);

namespace Analytics\Webhook;

use Analytics\Webhook\Processors\LeadStatusChangedProcessor;
use Analytics\Webhook\Processors\LeadAddedProcessor;
use Analytics\Webhook\Processors\LeadUpdatedProcessor;
use Analytics\Webhook\Processors\LeadDeletedProcessor;
use Analytics\Webhook\Processors\CustomerTransactionProcessor;
use Analytics\Webhook\Processors\UnsortedAddedProcessor;

/**
 * Factory for creating webhook processors
 */
class ProcessorFactory
{
    private array $processors;

    public function __construct(
        LeadStatusChangedProcessor $leadStatusChangedProcessor,
        LeadAddedProcessor $leadAddedProcessor,
        LeadUpdatedProcessor $leadUpdatedProcessor,
        LeadDeletedProcessor $leadDeletedProcessor,
        CustomerTransactionProcessor $customerTransactionProcessor,
        UnsortedAddedProcessor $unsortedAddedProcessor
    ) {
        $this->processors = [
            'lead_status_changed' => $leadStatusChangedProcessor,
            'lead_added' => $leadAddedProcessor,
            'lead_updated' => $leadUpdatedProcessor,
            'lead_deleted' => $leadDeletedProcessor,
            'customer_transaction_added' => $customerTransactionProcessor,
            'unsorted_added' => $unsortedAddedProcessor,
        ];
    }

    /**
     * Create processor for event type
     */
    public function createProcessor(string $eventType): ?ProcessorInterface
    {
        return $this->processors[$eventType] ?? null;
    }

    /**
     * Get all supported event types
     */
    public function getSupportedTypes(): array
    {
        return array_keys($this->processors);
    }
}
