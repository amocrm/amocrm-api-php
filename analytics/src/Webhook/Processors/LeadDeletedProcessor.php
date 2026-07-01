<?php

declare(strict_types=1);

namespace Analytics\Webhook\Processors;

use Analytics\Webhook\ProcessorInterface;
use Analytics\Repository\LeadSnapshotRepo;
use Analytics\Logger\AnalyticsLogger;
use Carbon\Carbon;

/**
 * Processor for lead_deleted event
 */
class LeadDeletedProcessor implements ProcessorInterface
{
    private LeadSnapshotRepo $leadSnapshotRepo;
    private AnalyticsLogger $logger;

    public function __construct(LeadSnapshotRepo $leadSnapshotRepo, AnalyticsLogger $logger)
    {
        $this->leadSnapshotRepo = $leadSnapshotRepo;
        $this->logger = $logger;
    }

    public function getEventType(): string
    {
        return 'lead_deleted';
    }

    public function process(array $payload): void
    {
        $data = $payload['payload'] ?? [];

        if (!isset($data['id'])) {
            throw new \InvalidArgumentException('Missing lead ID in payload');
        }

        $leadId = (int)$data['id'];

        // Mark lead as deleted
        $this->leadSnapshotRepo->markDeleted($leadId, Carbon::now());

        $this->logger->webhook($this->getEventType(), (string)$leadId, [
            'deleted_at' => Carbon::now()->toIso8601String(),
        ]);
    }
}
