<?php

declare(strict_types=1);

namespace Analytics\ETL;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\Unsorted\UnsortedCollection;
use AmoCRM\Collections\BaseApiCollection;
use Analytics\Repository\SourceAttributionRepo;
use Analytics\Logger\AnalyticsLogger;
use Analytics\Models\SourceAttribution;
use Carbon\Carbon;

/**
 * Exporter for unsorted leads from amoCRM
 */
class UnsortedExporter extends BaseExporter
{
    private const ENTITY_NAME = 'unsorted';
    private SourceAttributionRepo $sourceAttributionRepo;

    public function __construct(
        AmoCRMApiClient $client,
        SourceAttributionRepo $sourceAttributionRepo,
        AnalyticsLogger $logger,
        int $batchSize = 100,
        int $maxRetries = 3
    ) {
        parent::__construct($client, $sourceAttributionRepo, $logger, $batchSize, $maxRetries);
        $this->sourceAttributionRepo = $sourceAttributionRepo;
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
        return $this->client->unsorted();
    }

    /**
     * @inheritDoc
     */
    protected function fetchPage(int $page): ?BaseApiCollection
    {
        return $this->executeWithRetry(function () use ($page) {
            return $this->client->unsorted()->get(
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
        // Unsorted doesn't support updatedAt filter, use createdAt
        return $this->executeWithRetry(function () use ($page, $since) {
            return $this->client->unsorted()->get(
                null,
                array_merge(
                    $this->buildPaginationFilter($page),
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

        /** @var \AmoCRM\Models\Unsorted\BaseUnsortedModel $unsorted */
        foreach ($collection as $unsorted) {
            $this->processUnsorted($unsorted);
            $count++;
        }

        return $count;
    }

    /**
     * Process single unsorted item
     */
    private function processUnsorted(\AmoCRM\Models\Unsorted\BaseUnsortedModel $unsorted): void
    {
        $attribution = new SourceAttribution();
        $attribution->setUnsortedId($unsorted->getId() ?? 0);
        $attribution->setUnsortedCategory($unsorted->getCategory());
        $attribution->setFirstTouchAt(Carbon::now());
        $attribution->setLastTouchAt(Carbon::now());

        // Extract source info from metadata
        $metadata = $unsorted->getMetadata();
        if ($metadata !== null) {
            if (isset($metadata['source_id'])) {
                $attribution->setSourceId((int)$metadata['source_id']);
            }
            if (isset($metadata['source_name'])) {
                $attribution->setSourceName($metadata['source_name']);
            }
            if (isset($metadata['source_external_id'])) {
                $attribution->setSourceExternalId($metadata['source_external_id']);
            }
        }

        $this->sourceAttributionRepo->insert($attribution);
    }
}
