<?php

declare(strict_types=1);

namespace Analytics\ETL;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\LeadsCollection;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\LeadModel;
use Analytics\Repository\LeadSnapshotRepo;
use Analytics\Logger\AnalyticsLogger;
use Carbon\Carbon;

/**
 * Exporter for leads/deals
 */
class LeadsExporter extends BaseExporter
{
    private const ENTITY_NAME = 'leads';

    /**
     * @inheritDoc
     */
    protected function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    /**
     * @inheritDoc
     */
    protected function getService(): object
    {
        return $this->client->leads();
    }

    /**
     * @inheritDoc
     */
    protected function fetchPage(int $page): ?BaseApiCollection
    {
        return $this->executeWithRetry(function () use ($page) {
            return $this->client->leads()->get(
                null,
                array_merge(
                    $this->buildPaginationFilter($page),
                    ['with' => 'contacts,loss_reason,catalog_elements']
                )
            );
        });
    }

    /**
     * @inheritDoc
     */
    protected function fetchPageWithFilter(int $page, \DateTimeInterface $since): ?BaseApiCollection
    {
        return $this->executeWithRetry(function () use ($page, $since) {
            return $this->client->leads()->get(
                null,
                array_merge(
                    $this->buildIncrementalFilter($page, $since),
                    ['with' => 'contacts,loss_reason,catalog_elements']
                )
            );
        });
    }

    /**
     * @inheritDoc
     */
    protected function processCollection(BaseApiCollection $collection): int
    {
        $count = 0;
        $snapshots = [];

        /** @var LeadModel $lead */
        foreach ($collection as $lead) {
            $snapshot = \Analytics\Models\LeadSnapshot::fromLeadModel($lead);
            $snapshot->setSnapshotAt(Carbon::now());
            $snapshots[] = $snapshot;
            $count++;
        }

        if (!empty($snapshots)) {
            $this->repo->insertBatch($snapshots);
        }

        return $count;
    }

    /**
     * Export single lead by ID
     */
    public function exportById(int $leadId): ?\Analytics\Models\LeadSnapshot
    {
        return $this->executeWithRetry(function () use ($leadId) {
            $lead = $this->client->leads()->getOne($leadId, [
                'contacts',
                'loss_reason',
                'catalog_elements',
            ]);

            if ($lead === null) {
                return null;
            }

            $snapshot = \Analytics\Models\LeadSnapshot::fromLeadModel($lead);
            $snapshot->setSnapshotAt(Carbon::now());
            $this->repo->insert($snapshot);

            return $snapshot;
        });
    }

    /**
     * Mark lead as deleted
     */
    public function markDeleted(int $leadId): void
    {
        $this->repo->markDeleted($leadId, Carbon::now());
    }
}
