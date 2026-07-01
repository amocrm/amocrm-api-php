<?php

declare(strict_types=1);

namespace Analytics\Webhook\Processors;

use Analytics\Webhook\ProcessorInterface;
use Analytics\Repository\SourceAttributionRepo;
use Analytics\Logger\AnalyticsLogger;
use Analytics\Models\SourceAttribution;
use Carbon\Carbon;

/**
 * Processor for unsorted_added event
 */
class UnsortedAddedProcessor implements ProcessorInterface
{
    private SourceAttributionRepo $sourceAttributionRepo;
    private AnalyticsLogger $logger;

    public function __construct(SourceAttributionRepo $sourceAttributionRepo, AnalyticsLogger $logger)
    {
        $this->sourceAttributionRepo = $sourceAttributionRepo;
        $this->logger = $logger;
    }

    public function getEventType(): string
    {
        return 'unsorted_added';
    }

    public function process(array $payload): void
    {
        $data = $payload['payload'] ?? [];

        if (!isset($data['id'])) {
            throw new \InvalidArgumentException('Missing unsorted ID in payload');
        }

        $attribution = new SourceAttribution();
        $attribution->setUnsortedId((int)$data['id']);
        $attribution->setUnsortedCategory($data['category'] ?? 'unknown');
        $attribution->setFirstTouchAt(Carbon::now());
        $attribution->setLastTouchAt(Carbon::now());

        // Extract source info
        $source = $data['source'] ?? [];
        if (!empty($source)) {
            $attribution->setSourceId($source['id'] ?? null);
            $attribution->setSourceName($source['name'] ?? null);
            $attribution->setSourceExternalId($source['external_id'] ?? null);
        }

        $this->sourceAttributionRepo->insert($attribution);

        $this->logger->webhook($this->getEventType(), (string)$data['id'], [
            'category' => $attribution->getUnsortedCategory(),
            'source_id' => $attribution->getSourceId(),
        ]);
    }
}
