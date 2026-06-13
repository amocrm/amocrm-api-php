<?php

declare(strict_types=1);

namespace Analytics\Webhook\Processors;

use Analytics\Webhook\ProcessorInterface;
use Analytics\Repository\LeadSnapshotRepo;
use Analytics\Logger\AnalyticsLogger;
use Analytics\Models\LeadSnapshot;
use Carbon\Carbon;

/**
 * Processor for lead_status_changed event
 */
class LeadStatusChangedProcessor implements ProcessorInterface
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
        return 'lead_status_changed';
    }

    public function process(array $payload): void
    {
        $data = $payload['payload'] ?? [];

        if (!isset($data['id'])) {
            throw new \InvalidArgumentException('Missing lead ID in payload');
        }

        $leadId = (int)$data['id'];
        $newStatusId = (int)($data['new_status_id'] ?? 0);
        $newPipelineId = (int)($data['new_pipeline_id'] ?? 0);
        $oldStatusId = (int)($data['old_status_id'] ?? 0);
        $oldPipelineId = (int)($data['old_pipeline_id'] ?? 0);

        // Get latest snapshot
        $latestSnapshot = $this->leadSnapshotRepo->findLatestByLeadId($leadId);

        if ($latestSnapshot !== null) {
            // Mark old status
            $latestSnapshot->setIsDeleted(true);
            $this->leadSnapshotRepo->insert($latestSnapshot);
        }

        // Create new snapshot with updated status
        $newSnapshot = new LeadSnapshot();
        $newSnapshot->setLeadId($leadId);
        $newSnapshot->setPipelineId($newPipelineId ?: ($latestSnapshot?->getPipelineId() ?? 0));
        $newSnapshot->setStatusId($newStatusId);
        $newSnapshot->setPrice($latestSnapshot?->getPrice());
        $newSnapshot->setSourceId($latestSnapshot?->getSourceId());
        $newSnapshot->setResponsibleUserId($latestSnapshot?->getResponsibleUserId());
        $newSnapshot->setContactIds($latestSnapshot?->getContactIds() ?? []);
        $newSnapshot->setCompanyId($latestSnapshot?->getCompanyId());
        $newSnapshot->setCustomFields($latestSnapshot?->getCustomFields() ?? []);
        $newSnapshot->setTags($latestSnapshot?->getTags() ?? []);
        $newSnapshot->setCreatedAt($latestSnapshot?->getCreatedAt());
        $newSnapshot->setSnapshotAt(Carbon::now());

        // If moving to closed status, set closed_at
        if ($newStatusId === 142 || $newStatusId === 143) { // Won or Lost
            $newSnapshot->setClosedAt(Carbon::now());
        }

        $this->leadSnapshotRepo->insert($newSnapshot);

        $this->logger->webhook($this->getEventType(), (string)$leadId, [
            'old_status_id' => $oldStatusId,
            'new_status_id' => $newStatusId,
            'pipeline_id' => $newPipelineId,
        ]);
    }
}
