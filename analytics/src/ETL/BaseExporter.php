<?php

declare(strict_types=1);

namespace Analytics\ETL;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\BaseApiCollection;
use Analytics\Repository\LeadSnapshotRepo;
use Analytics\Logger\AnalyticsLogger;
use Analytics\Exception\RateLimitException;
use Analytics\Exception\AnalyticsException;

/**
 * Base exporter with pagination and rate limiting support
 */
abstract class BaseExporter
{
    protected AmoCRMApiClient $client;
    protected LeadSnapshotRepo $repo;
    protected AnalyticsLogger $logger;
    protected int $batchSize;
    protected int $maxRetries;
    protected int $retryDelay = 1;

    public function __construct(
        AmoCRMApiClient $client,
        LeadSnapshotRepo $repo,
        AnalyticsLogger $logger,
        int $batchSize = 100,
        int $maxRetries = 3
    ) {
        $this->client = $client;
        $this->repo = $repo;
        $this->logger = $logger;
        $this->batchSize = $batchSize;
        $this->maxRetries = $maxRetries;
    }

    /**
     * Run full export (initial load)
     */
    public function fullExport(): int
    {
        $start = microtime(true);
        $total = 0;
        $page = 1;

        $this->logger->etl($this->getEntityName(), 'full_export_start');

        do {
            $collection = $this->fetchPage($page);

            if ($collection !== null && !$collection->isEmpty()) {
                $count = $this->processCollection($collection);
                $total += $count;

                $this->logger->sync($this->getEntityName(), $total, $total + $collection->count());
            }

            $page++;
            $collection = $this->fetchNextPage($collection);

        } while ($collection !== null && !$collection->isEmpty());

        $duration = microtime(true) - $start;
        $this->logger->performance($this->getEntityName() . '.full_export', $duration, ['total' => $total]);
        $this->logger->etl($this->getEntityName(), 'full_export_complete', ['total' => $total, 'duration' => $duration]);

        return $total;
    }

    /**
     * Run incremental export since last sync
     */
    public function incrementalExport(\DateTimeInterface $since): int
    {
        $start = microtime(true);
        $total = 0;
        $page = 1;

        $this->logger->etl($this->getEntityName(), 'incremental_export_start', ['since' => $since->format('c')]);

        do {
            $collection = $this->fetchPageWithFilter($page, $since);

            if ($collection !== null && !$collection->isEmpty()) {
                $count = $this->processCollection($collection);
                $total += $count;

                $this->logger->sync($this->getEntityName(), $total, $total + $collection->count());
            }

            $page++;
            $collection = $this->fetchNextPage($collection);

        } while ($collection !== null && !$collection->isEmpty());

        $duration = microtime(true) - $start;
        $this->logger->performance($this->getEntityName() . '.incremental_export', $duration, ['total' => $total]);
        $this->logger->etl($this->getEntityName(), 'incremental_export_complete', ['total' => $total, 'duration' => $duration]);

        return $total;
    }

    /**
     * Get entity name for logging
     */
    abstract protected function getEntityName(): string;

    /**
     * Get service for this entity
     */
    abstract protected function getService(): object;

    /**
     * Fetch a single page of data
     */
    abstract protected function fetchPage(int $page): ?BaseApiCollection;

    /**
     * Fetch page with updatedAt filter for incremental export
     */
    abstract protected function fetchPageWithFilter(int $page, \DateTimeInterface $since): ?BaseApiCollection;

    /**
     * Process a collection of entities
     */
    abstract protected function processCollection(BaseApiCollection $collection): int;

    /**
     * Fetch next page if pagination available
     */
    protected function fetchNextPage(?BaseApiCollection $collection): ?BaseApiCollection
    {
        if ($collection === null) {
            return null;
        }

        // Check if collection has next page method
        if (method_exists($collection, 'nextPage')) {
            return $collection->nextPage($collection);
        }

        return null;
    }

    /**
     * Build filter for pagination
     */
    protected function buildPaginationFilter(int $page): array
    {
        return [
            'page' => $page,
            'limit' => $this->batchSize,
        ];
    }

    /**
     * Build filter with updatedAt for incremental export
     */
    protected function buildIncrementalFilter(int $page, \DateTimeInterface $since): array
    {
        return array_merge(
            $this->buildPaginationFilter($page),
            [
                'filter' => [
                    'updated_at' => [
                        'from' => $since->getTimestamp(),
                    ],
                ],
            ]
        );
    }

    /**
     * Handle rate limit with exponential backoff
     *
     * @throws RateLimitException
     */
    protected function handleRateLimitException(RateLimitException $e): void
    {
        $delay = $e->getRetryAfter();

        $this->logger->rateLimit($delay, [
            'entity' => $this->getEntityName(),
            'current_delay' => $this->retryDelay,
        ]);

        sleep(min($delay, 60)); // Cap at 60 seconds

        $this->retryDelay = min($this->retryDelay * 2, 60); // Exponential backoff
    }

    /**
     * Reset retry delay after successful request
     */
    protected function resetRetryDelay(): void
    {
        $this->retryDelay = 1;
    }

    /**
     * Execute with retry logic
     *
     * @template T
     * @param callable(): T $operation
     * @return T
     * @throws AnalyticsException
     */
    protected function executeWithRetry(callable $operation): mixed
    {
        $attempts = 0;
        $lastException = null;

        while ($attempts < $this->maxRetries) {
            try {
                $this->resetRetryDelay();
                return $operation();
            } catch (RateLimitException $e) {
                $attempts++;
                $this->handleRateLimitException($e);
                $lastException = $e;
            } catch (\AmoCRM\Exceptions\AmoCRMApiTooManyRequestsException $e) {
                $attempts++;

                // Default retry after 60 seconds
                $this->logger->rateLimit(60, ['entity' => $this->getEntityName()]);
                sleep(60);
                $lastException = $e;
            } catch (\AmoCRM\Exceptions\AmoCRMApiException $e) {
                // Non-retryable error
                $this->logger->apiError($e->getMessage(), [
                    'entity' => $this->getEntityName(),
                    'code' => $e->getCode(),
                    'attempts' => $attempts,
                ]);
                throw $e;
            }
        }

        if ($lastException !== null) {
            throw $lastException;
        }

        throw new AnalyticsException('Max retries exceeded');
    }
}
