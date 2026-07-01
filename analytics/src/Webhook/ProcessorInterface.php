<?php

declare(strict_types=1);

namespace Analytics\Webhook;

/**
 * Interface for webhook processors
 */
interface ProcessorInterface
{
    /**
     * Process webhook payload
     */
    public function process(array $payload): void;

    /**
     * Get supported event type
     */
    public function getEventType(): string;
}
