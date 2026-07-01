<?php

declare(strict_types=1);

namespace Analytics\Api;

use Carbon\Carbon;
use Fusio\Engine\Action\Runtime;
use Fusio\Engine\Context;
use Fusio\Engine\Request;

/**
 * LTV API action for Fusio
 * 
 * GET /api/analytics/ltv
 */
class LTVAction extends BaseAction
{
    public function handle(Request $request, Context $context, Runtime $runtime): array
    {
        try {
            $queryParams = $request->getUri()->getParameters();

            // Parse parameters
            $segment = $queryParams['segment'] ?? 'all';
            $minTransactions = isset($queryParams['min_transactions']) ? (int)$queryParams['min_transactions'] : null;
            $dateFrom = isset($queryParams['date_from']) ? Carbon::parse($queryParams['date_from']) : null;
            $dateTo = isset($queryParams['date_to']) ? Carbon::parse($queryParams['date_to']) : null;
            $sortBy = $queryParams['sort_by'] ?? 'ltv';
            $sortOrder = $queryParams['sort_order'] ?? 'desc';
            $limit = isset($queryParams['limit']) ? min((int)$queryParams['limit'], 100) : 50;
            $offset = isset($queryParams['offset']) ? (int)$queryParams['offset'] : 0;

            // Validate segment
            if (!in_array($segment, ['all', 'active', 'churned'])) {
                return $this->errorResponse('Invalid segment. Must be all, active, or churned', 400);
            }

            $ltvService = $this->createLTVService();
            $result = $ltvService->getLTVReport(
                $segment,
                $minTransactions,
                $dateFrom,
                $dateTo,
                $sortBy,
                $sortOrder,
                $limit,
                $offset
            );

            return $this->jsonResponse($result);

        } catch (\Exception $e) {
            $this->logger->apiError('LTV API error: ' . $e->getMessage(), [
                'params' => $request->getUri()->getParameters(),
            ]);

            return $this->jsonResponse([
                'error' => [
                    'code' => 'INTERNAL_ERROR',
                    'message' => 'Failed to generate LTV report',
                ],
            ], 500);
        }
    }
}