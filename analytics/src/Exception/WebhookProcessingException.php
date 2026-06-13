<?php

declare(strict_types=1);

namespace Analytics\Exception;

/**
 * Exception for webhook processing errors
 */
class WebhookProcessingException extends AnalyticsException
{
    private string $webhookId;
    private string $eventType;
    private bool $retryable;

    public function __construct(
        string $message = 'Webhook processing failed',
        string $webhookId = '',
        string $eventType = '',
        bool $retryable = true,
        int $code = 0,
        array $context = []
    ) {
        parent::__construct($message, $code, null, $context);
        $this->webhookId = $webhookId;
        $this->eventType = $eventType;
        $this->retryable = $retryable;
    }

    public function getWebhookId(): string
    {
        return $this->webhookId;
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function isRetryable(): bool
    {
        return $this->retryable;
    }
}
