<?php

declare(strict_types=1);

namespace Analytics\Api;

use Carbon\Carbon;
use Fusio\Engine\Action\Runtime;
use Fusio\Engine\Context;
use Fusio\Engine\Request;

/**
 * Attribution API action for Fusio
 * 
 * GET /api/analytics/attribution
 */
class AttributionAction extends BaseAction
{
    public function handle(Request $request, Context $context, Runtime $runtime): array
    {
        try {
            $queryParams = $request->getUri()->getParameters();

            // Parse parameters
            $type = $queryParams['type'] ?? 'first_touch';
            $dateFrom = isset($queryParams['date_from']) ? Carbon::parse($queryParams['date_from']) : null;
            $dateTo = isset($queryParams['date_to']) ? Carbon::parse($queryParams['date_to']) : null;
            $sourceId = isset($queryParams['source_id']) ? (int)$queryParams['source_id'] : null;
            $limit = isset($queryParams['limit']) ? min((int)$queryParams['limit'], 100) : 50;
            $offset = isset($queryParams['offset']) ? (int)$queryParams['offset'] : 0;

            // Validate type
            if (!in_array($type, ['first_touch', 'last_touch'])) {
                return $this->errorResponse('Invalid type. Must be first_touch or last_touch', 400);
            }

            $attributionService = $this->createAttributionService();
            $result = $attributionService->getAttributionReport(
                $type,
                $dateFrom,
                $dateTo,
                $sourceId,
                $limit,
                $offset
            );

            return $this->jsonResponse($result);

        } catch (\Exception $e) {
            $this->logger->apiError('Attribution API error: ' . $e->getMessage(), [
                'params' => $request->getUri()->getParameters(),
            ]);

            return $this->jsonResponse([
                'error' => [
                    'code' => 'INTERNAL_ERROR',
                    'message' => 'Failed to generate attribution report',
                ],
            ], 500);
        }
    }
}