<?php

declare(strict_types=1);

namespace Analytics\Api;

use Analytics\Config\Config;
use Analytics\Logger\AnalyticsLogger;
use Analytics\Repository\ConnectionFactory;
use Carbon\Carbon;
use Fusio\Engine\Action\Runtime;
use Fusio\Engine\Context;
use Fusio\Engine\Request;

/**
 * Funnel API action for Fusio
 * 
 * GET /api/analytics/funnel
 */
class FunnelAction extends BaseAction
{
    public function handle(Request $request, Context $context, Runtime $runtime): array
    {
        try {
            $queryParams = $request->getUri()->getParameters();

            // Parse parameters
            $pipelineId = isset($queryParams['pipeline_id']) ? (int)$queryParams['pipeline_id'] : null;
            $dateFrom = isset($queryParams['date_from']) ? Carbon::parse($queryParams['date_from']) : null;
            $dateTo = isset($queryParams['date_to']) ? Carbon::parse($queryParams['date_to']) : null;
            $groupBy = $queryParams['group_by'] ?? null;

            if ($pipelineId === null) {
                return $this->errorResponse('pipeline_id is required', 400);
            }

            $funnelService = $this->createFunnelService();
            $result = $funnelService->getFunnel($pipelineId, $dateFrom, $dateTo, $groupBy);

            return $this->jsonResponse($result);

        } catch (\Exception $e) {
            $this->logger->apiError('Funnel API error: ' . $e->getMessage(), [
                'params' => $request->getUri()->getParameters(),
            ]);

            return $this->jsonResponse([
                'error' => [
                    'code' => 'INTERNAL_ERROR',
                    'message' => 'Failed to generate funnel report',
                ],
            ], 500);
        }
    }
}