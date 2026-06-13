<?php

declare(strict_types=1);

namespace Analytics\Api;

use Analytics\Config\Config;
use Analytics\Client\AmoCRMFactory;
use Analytics\Client\FileTokenStorage;
use Analytics\Repository\ConnectionFactory;
use Analytics\Repository\LeadSnapshotRepo;
use Analytics\Repository\WebhookLogRepo;
use Analytics\Repository\TransactionRepo;
use Analytics\Repository\SourceAttributionRepo;
use Analytics\Webhook\Handler;
use Analytics\Webhook\IdempotencyChecker;
use Analytics\Webhook\ProcessorFactory;
use Analytics\Webhook\Processors\LeadStatusChangedProcessor;
use Analytics\Webhook\Processors\LeadAddedProcessor;
use Analytics\Webhook\Processors\LeadUpdatedProcessor;
use Analytics\Webhook\Processors\LeadDeletedProcessor;
use Analytics\Webhook\Processors\CustomerTransactionProcessor;
use Analytics\Webhook\Processors\UnsortedAddedProcessor;
use Analytics\Logger\AnalyticsLogger;
use Analytics\Analytics\FunnelService;
use Analytics\Analytics\AttributionService;
use Analytics\Analytics\LTVService;
use Fusio\Engine\Action\Runtime;
use Fusio\Engine\Context;
use Fusio\Engine\Request;

/**
 * Base class for Analytics API actions
 */
abstract class BaseAction
{
    protected Config $config;
    protected AnalyticsLogger $logger;
    protected ConnectionFactory $connectionFactory;

    public function __construct(Config $config, AnalyticsLogger $logger, ConnectionFactory $connectionFactory)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->connectionFactory = $connectionFactory;
    }

    protected function getConnection()
    {
        return $this->connectionFactory->getConnection();
    }

    protected function createFunnelService(): FunnelService
    {
        $db = $this->getConnection();
        return new FunnelService(
            $db,
            new LeadSnapshotRepo($db),
            new \Analytics\Repository\PipelineStagesRepo($db)
        );
    }

    protected function createAttributionService(): AttributionService
    {
        $db = $this->getConnection();
        return new AttributionService(
            $db,
            new SourceAttributionRepo($db),
            new LeadSnapshotRepo($db)
        );
    }

    protected function createLTVService(): LTVService
    {
        $db = $this->getConnection();
        return new LTVService(
            $db,
            new \Analytics\Repository\CustomerLTVRepo($db),
            new TransactionRepo($db)
        );
    }

    protected function createWebhookHandler(): Handler
    {
        $db = $this->getConnection();
        $logger = $this->logger;
        
        $webhookLogRepo = new WebhookLogRepo($db);
        $leadSnapshotRepo = new LeadSnapshotRepo($db);
        
        $idempotencyChecker = new IdempotencyChecker($webhookLogRepo, $logger);
        
        $processorFactory = new ProcessorFactory(
            new LeadStatusChangedProcessor($leadSnapshotRepo, $logger),
            new LeadAddedProcessor($leadSnapshotRepo, $logger),
            new LeadUpdatedProcessor($leadSnapshotRepo, $logger),
            new LeadDeletedProcessor($leadSnapshotRepo, $logger),
            new CustomerTransactionProcessor(new TransactionRepo($db), $logger),
            new UnsortedAddedProcessor(new SourceAttributionRepo($db), $logger)
        );

        return new Handler(
            $idempotencyChecker,
            $processorFactory,
            $webhookLogRepo,
            $leadSnapshotRepo,
            $logger,
            $this->config->getAmoCRMConfig()['client_secret'] ?? ''
        );
    }

    protected function jsonResponse(array $data, int $statusCode = 200): array
    {
        return [
            'statusCode' => $statusCode,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($data),
        ];
    }

    protected function errorResponse(string $message, int $statusCode = 400, array $details = []): array
    {
        return $this->jsonResponse([
            'error' => [
                'code' => 'VALIDATION_ERROR',
                'message' => $message,
                'details' => $details,
            ],
        ], $statusCode);
    }
}