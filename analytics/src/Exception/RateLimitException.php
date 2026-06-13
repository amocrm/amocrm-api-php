<?php

declare(strict_types=1);

namespace Analytics\Exception;

/**
 * Exception for rate limiting errors
 */
class RateLimitException extends AnalyticsException
{
    private int $retryAfter;
    private int $currentDelay;

    public function __construct(
        string $message = 'Rate limit exceeded',
        int $retryAfter = 60,
        int $currentDelay = 0,
        int $code = 429,
        array $context = []
    ) {
        parent::__construct($message, $code, null, $context);
        $this->retryAfter = $retryAfter;
        $this->currentDelay = $currentDelay;
    }

    public function getRetryAfter(): int
    {
        return $this->retryAfter;
    }

    public function getCurrentDelay(): int
    {
        return $this->currentDelay;
    }
}
