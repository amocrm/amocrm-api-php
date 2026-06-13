<?php

declare(strict_types=1);

namespace Analytics\ETL;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\EventsCollection;
use AmoCRM\Collections\BaseApiCollection;
use Analytics\Repository\AnalyticsEventRepo;
use Analytics\Logger\AnalyticsLogger;

/**
 * Exporter for events from amoCRM
 */
class EventsExporter extends BaseExporter
{
    private const ENTITY_NAME = 'events';

    public function __construct(
        AmoCRMApiClient $client,
        AnalyticsEventRepo $repo,
        AnalyticsLogger $logger,
        int $batchSize = 100,
        int $maxRetries = 3
    ) {
        parent::__construct($client, $repo, $logger, $batchSize, $maxRetries);
    }

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
        return $this->client->events();
    }

    /**
     * @inheritDoc
     */
    protected function fetchPage(int $page): ?BaseApiCollection
    {
        return $this->executeWithRetry(function () use ($page) {
            return $this->client->events()->get(
                null,
                $this->buildPaginationFilter($page)
            );
        });
    }

    /**
     * @inheritDoc
     */
    protected function fetchPageWithFilter(int $page, \DateTimeInterface $since): ?BaseApiCollection
    {
        return $this->executeWithRetry(function () use ($page, $since) {
            return $this->client->events()->get(
                null,
                array_merge(
                    $this->buildIncrementalFilter($page, $since),
                    ['filter' => ['created_at' => ['from' => $since->getTimestamp()]]]
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

        /** @var \AmoCRM\Models\EventModel $event */
        foreach ($collection as $event) {
            $this->processEvent($event);
            $count++;
        }

        return $count;
    }

    /**
     * Process single event
     */
    private function processEvent(\AmoCRM\Models\EventModel $event): void
    {
        // Extract before/after values for status changes
        $valueBefore = null;
        $valueAfter = null;

        $value = $event->getValue();
        if ($value !== null) {
            if (isset($value['before'])) {
                $valueBefore = $value['before'];
            }
            if (isset($value['after'])) {
                $valueAfter = $value['after'];
            }
        }

        $this->repo->insert([
            'entity_type' => $event->getEntityType() ?? 'unknown',
            'entity_id' => $event->getEntityId() ?? 0,
            'event_type' => $event->getType() ?? 'unknown',
            'old_value' => $valueBefore,
            'new_value' => $valueAfter,
            'created_at' => $event->getCreatedAt() ? \Carbon\Carbon::createFromTimestamp($event->getCreatedAt()) : null,
        ]);
    }
}
