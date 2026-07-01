<?php

declare(strict_types=1);

namespace Analytics\Api;

use Analytics\Config\Config;
use Analytics\Logger\AnalyticsLogger;
use Analytics\Repository\ConnectionFactory;
use Fusio\Engine\Action\Runtime;
use Fusio\Engine\Context;
use Fusio\Engine\Request;

/**
 * Webhook endpoint action for Fusio
 * 
 * POST /webhook/amocrm
 */
class WebhookAction extends BaseAction
{
    public function handle(Request $request, Context $context, Runtime $runtime): array
    {
        try {
            $body = $request->getBody();
            $payload = json_decode($body, true);

            if ($payload === null) {
                return $this->errorResponse('Invalid JSON payload', 400);
            }

            // Get signature if present
            $signature = $request->getHeader('X-Signature');

            $this->logger->webhook(
                $payload['webhook']['type'] ?? 'unknown',
                (string)($payload['payload']['id'] ?? 0),
                ['source' => 'webhook']
            );

            $handler = $this->createWebhookHandler();
            $result = $handler->handle($payload, $signature);

            if (($result['duplicate'] ?? false) === true) {
                return $this->jsonResponse([
                    'status' => 'ok',
                    'webhook_id' => $result['webhook_id'],
                    'message' => 'Duplicate webhook, already processed',
                ]);
            }

            return $this->jsonResponse([
                'status' => 'ok',
                'webhook_id' => $result['webhook_id'],
                'processed' => $result['processed'] ?? true,
                'duration' => $result['duration'] ?? null,
            ]);

        } catch (\Analytics\Exception\WebhookProcessingException $e) {
            $this->logger->apiError('Webhook processing failed: ' . $e->getMessage(), [
                'webhook_id' => $e->getWebhookId(),
                'event_type' => $e->getEventType(),
            ]);

            $statusCode = $e->isRetryable() ? 500 : 400;

            return $this->jsonResponse([
                'status' => 'error',
                'code' => 'WEBHOOK_ERROR',
                'message' => $e->getMessage(),
                'webhook_id' => $e->getWebhookId(),
            ], $statusCode);

        } catch (\Exception $e) {
            $this->logger->apiError('Webhook error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->jsonResponse([
                'status' => 'error',
                'code' => 'INTERNAL_ERROR',
                'message' => 'Internal server error',
            ], 500);
        }
    }
}