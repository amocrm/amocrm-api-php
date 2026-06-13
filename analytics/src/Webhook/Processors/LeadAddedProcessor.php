<?php

declare(strict_types=1);

namespace Analytics\Webhook\Processors;

use Analytics\Webhook\ProcessorInterface;
use Analytics\Repository\LeadSnapshotRepo;
use Analytics\Logger\AnalyticsLogger;
use Analytics\Models\LeadSnapshot;
use Carbon\Carbon;

/**
 * Processor for lead_added event
 */
class LeadAddedProcessor implements ProcessorInterface
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
        return 'lead_added';
    }

    public function process(array $payload): void
    {
        $data = $payload['payload'] ?? [];

        if (!isset($data['id'])) {
            throw new \InvalidArgumentException('Missing lead ID in payload');
        }

        $snapshot = new LeadSnapshot();
        $snapshot->setLeadId((int)$data['id']);
        $snapshot->setPipelineId((int)($data['pipeline_id'] ?? 0));
        $snapshot->setStatusId((int)($data['status_id'] ?? 0));
        $snapshot->setPrice(isset($data['price']) ? (float)$data['price'] : null);
        $snapshot->setResponsibleUserId(isset($data['responsible_user_id']) ? (int)$data['responsible_user_id'] : null);
        $snapshot->setCreatedAt($this->parseTimestamp($data['created_at'] ?? null));
        $snapshot->setUpdatedAt($this->parseTimestamp($data['updated_at'] ?? null));
        $snapshot->setSnapshotAt(Carbon::now());

        $this->leadSnapshotRepo->insert($snapshot);

        $this->logger->webhook($this->getEventType(), (string)$data['id'], [
            'pipeline_id' => $snapshot->getPipelineId(),
            'status_id' => $snapshot->getStatusId(),
            'price' => $snapshot->getPrice(),
        ]);
    }

    private function parseTimestamp(mixed $value): ?Carbon
    {
        if ($value === null) {
            return null;
        }

        if (is_numeric($value)) {
            return Carbon::createFromTimestamp((int)$value);
        }

        return Carbon::parse((string)$value);
    }
}
