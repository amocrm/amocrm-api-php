<?php

declare(strict_types=1);

namespace Analytics\Tests\Unit;

use Analytics\Webhook\IdempotencyChecker;
use Analytics\Repository\WebhookLogRepo;
use Analytics\Logger\AnalyticsLogger;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class IdempotencyCheckerTest extends TestCase
{
    private MockObject $webhookLogRepo;
    private MockObject $logger;
    private IdempotencyChecker $checker;

    protected function setUp(): void
    {
        $this->webhookLogRepo = $this->createMock(WebhookLogRepo::class);
        $this->logger = $this->createMock(AnalyticsLogger::class);
        $this->checker = new IdempotencyChecker($this->webhookLogRepo, $this->logger);
    }

    public function testIsProcessedReturnsFalseWhenNotFound(): void
    {
        $this->webhookLogRepo
            ->expects($this->once())
            ->method('findByWebhookId')
            ->with('webhook_123')
            ->willReturn(null);

        $this->assertFalse($this->checker->isProcessed('webhook_123'));
    }

    public function testIsProcessedReturnsTrueWhenStatusIsProcessed(): void
    {
        $this->webhookLogRepo
            ->expects($this->once())
            ->method('findByWebhookId')
            ->with('webhook_123')
            ->willReturn(['status' => 'processed']);

        $this->assertTrue($this->checker->isProcessed('webhook_123'));
    }

    public function testIsProcessedReturnsFalseWhenStatusIsPending(): void
    {
        $this->webhookLogRepo
            ->expects($this->once())
            ->method('findByWebhookId')
            ->with('webhook_123')
            ->willReturn(['status' => 'pending']);

        $this->assertFalse($this->checker->isProcessed('webhook_123'));
    }

    public function testIsProcessingReturnsTrueWhenStatusIsProcessing(): void
    {
        $this->webhookLogRepo
            ->expects($this->once())
            ->method('findByWebhookId')
            ->with('webhook_123')
            ->willReturn(['status' => 'processing']);

        $this->assertTrue($this->checker->isProcessing('webhook_123'));
    }

    public function testIsProcessingReturnsFalseWhenStatusIsProcessed(): void
    {
        $this->webhookLogRepo
            ->expects($this->once())
            ->method('findByWebhookId')
            ->with('webhook_123')
            ->willReturn(['status' => 'processed']);

        $this->assertFalse($this->checker->isProcessing('webhook_123'));
    }

    public function testReserveReturnsTrueOnSuccess(): void
    {
        $this->webhookLogRepo
            ->expects($this->once())
            ->method('insert')
            ->with([
                'webhook_id' => 'webhook_123',
                'event_type' => 'lead_added',
                'entity_type' => 'lead',
                'entity_id' => 456,
                'payload' => ['test' => 'data'],
                'status' => 'processing',
            ])
            ->willReturn(true);

        $result = $this->checker->reserve(
            'webhook_123',
            'lead_added',
            'lead',
            456,
            ['test' => 'data']
        );

        $this->assertTrue($result);
    }

    public function testReserveReturnsFalseOnDuplicateKey(): void
    {
        $this->webhookLogRepo
            ->expects($this->once())
            ->method('insert')
            ->willThrowException(
                new \Doctrine\DBAL\Exception\UniqueConstraintViolationException(
                    'Duplicate key',
                    new \Doctrine\DBAL\Driver\PDOException(
                        new \PDOException('Duplicate entry')
                    )
                )
            );

        $this->logger
            ->expects($this->once())
            ->method('webhook');

        $result = $this->checker->reserve(
            'webhook_123',
            'lead_added',
            'lead',
            456,
            ['test' => 'data']
        );

        $this->assertFalse($result);
    }

    public function testMarkProcessedCallsUpdateStatus(): void
    {
        $this->webhookLogRepo
            ->expects($this->once())
            ->method('updateStatus')
            ->with('webhook_123', 'processed');

        $this->checker->markProcessed('webhook_123');
    }

    public function testMarkFailedCallsUpdateStatusWithError(): void
    {
        $this->webhookLogRepo
            ->expects($this->once())
            ->method('updateStatus')
            ->with('webhook_123', 'failed', 'Something went wrong');

        $this->checker->markFailed('webhook_123', 'Something went wrong');
    }

    public function testResetToPendingCallsResetMethod(): void
    {
        $this->webhookLogRepo
            ->expects($this->once())
            ->method('resetToPending')
            ->with('webhook_123');

        $this->checker->resetToPending('webhook_123');
    }
}