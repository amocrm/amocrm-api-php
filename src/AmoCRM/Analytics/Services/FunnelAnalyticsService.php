<?php

namespace AmoCRM\Analytics\Services;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\Leads\Pipelines\PipelinesCollection;
use AmoCRM\Collections\Leads\Pipelines\Statuses\StatusesCollection;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\Leads\Pipelines\PipelineModel;
use AmoCRM\Models\Leads\Pipelines\Statuses\StatusModel;

/**
 * Class FunnelAnalyticsService
 *
 * Сервис аналитики воронки продаж.
 * Рассчитывает конверсию по этапам, среднее время на этапах, средний чек.
 *
 * @package AmoCRM\Analytics\Services
 */
class FunnelAnalyticsService
{
    /**
     * @var LeadExportService
     */
    protected $leadExportService;

    /**
     * @var EventExportService
     */
    protected $eventExportService;

    /**
     * @var AmoCRMApiClient
     */
    protected $apiClient;

    /**
     * @var array|null Кэш воронок и статусов
     */
    protected $pipelinesCache;

    /**
     * FunnelAnalyticsService constructor.
     *
     * @param AmoCRMApiClient $apiClient
     */
    public function __construct(AmoCRMApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        $this->leadExportService = new LeadExportService($apiClient);
        $this->eventExportService = new EventExportService($apiClient);
    }

    /**
     * Получить структуру воронок
     *
     * @return array
     */
    public function getPipelinesStructure(): array
    {
        if ($this->pipelinesCache !== null) {
            return $this->pipelinesCache;
        }

        $pipelines = $this->apiClient->pipelines()->get();
        $this->pipelinesCache = [];

        /** @var PipelineModel $pipeline */
        foreach ($pipelines as $pipeline) {
            $pipelineId = $pipeline->getId();

            $this->pipelinesCache[$pipelineId] = [
                'id' => $pipelineId,
                'name' => $pipeline->getName(),
                'is_main' => $pipeline->getIsMain(),
                'statuses' => [],
            ];

            /** @var StatusModel $status */
            foreach ($pipeline->getStatuses() as $status) {
                $this->pipelinesCache[$pipelineId]['statuses'][$status->getId()] = [
                    'id' => $status->getId(),
                    'name' => $status->getName(),
                    'sort' => $status->getSort(),
                    'color' => $status->getColor(),
                    'type' => $status->getType(),
                ];
            }
        }

        return $this->pipelinesCache;
    }

    /**
     * Рассчитать статистику воронки за период
     *
     * @param int $startTimestamp Начало периода
     * @param int|null $endTimestamp Конец периода
     * @param int|null $pipelineId ID воронки (null = все)
     * @return array
     */
    public function calculateFunnelStats(int $startTimestamp, ?int $endTimestamp = null, ?int $pipelineId = null): array
    {
        $filter = $this->leadExportService->createFilter($startTimestamp, $endTimestamp);

        if ($pipelineId !== null) {
            $filter->setPipelineIds([$pipelineId]);
        }

        $leads = $this->leadExportService->getAllLeads($filter, [
            LeadModel::CONTACTS,
            LeadModel::COMPANY,
        ]);

        $pipelines = $this->getPipelinesStructure();
        $stats = [];

        // Инициализация статусов
        foreach ($pipelines as $pId => $pipeline) {
            $stats[$pId] = [
                'pipeline_name' => $pipeline['name'],
                'statuses' => [],
                'total_count' => 0,
                'won_count' => 0,
                'lost_count' => 0,
                'total_price' => 0,
                'won_price' => 0,
            ];

            foreach ($pipeline['statuses'] as $sId => $status) {
                $stats[$pId]['statuses'][$sId] = [
                    'name' => $status['name'],
                    'count' => 0,
                    'price' => 0,
                    'conversion_from_prev' => 0,
                ];
            }
        }

        // Подсчёт сделок
        foreach ($leads as $lead) {
            /** @var LeadModel $lead */
            $pId = $lead->getPipelineId();
            $sId = $lead->getStatusId();
            $price = $lead->getPrice() ?? 0;

            if (!isset($stats[$pId])) {
                continue;
            }

            $stats[$pId]['total_count']++;
            $stats[$pId]['total_price'] += $price;

            if (isset($stats[$pId]['statuses'][$sId])) {
                $stats[$pId]['statuses'][$sId]['count']++;
                $stats[$pId]['statuses'][$sId]['price'] += $price;
            }

            if ($lead->getStatusId() === LeadModel::WON_STATUS_ID) {
                $stats[$pId]['won_count']++;
                $stats[$pId]['won_price'] += $price;
            } elseif ($lead->getStatusId() === LeadModel::LOST_STATUS_ID) {
                $stats[$pId]['lost_count']++;
            }
        }

        // Расчёт конверсии
        foreach ($stats as $pId => &$pipelineStats) {
            $prevCount = 0;
            foreach ($pipelineStats['statuses'] as $sId => &$statusStats) {
                if ($prevCount > 0) {
                    $statusStats['conversion_from_prev'] = ($statusStats['count'] / $prevCount) * 100;
                }
                $prevCount = $statusStats['count'];
            }
        }

        return $stats;
    }

    /**
     * Рассчитать конверсию из первого статуса в победу
     *
     * @param int $pipelineId ID воронки
     * @param int $startTimestamp Начало периода
     * @param int|null $endTimestamp Конец периода
     * @return array
     */
    public function calculateConversionRate(int $pipelineId, int $startTimestamp, ?int $endTimestamp = null): array
    {
        $filter = $this->leadExportService->createFilter($startTimestamp, $endTimestamp, [$pipelineId]);
        $leads = $this->leadExportService->getAllLeads($filter, []);

        $pipelines = $this->getPipelinesStructure();

        if (!isset($pipelines[$pipelineId])) {
            return [];
        }

        $firstStatusId = null;
        $statuses = $pipelines[$pipelineId]['statuses'];
        uasort($statuses, function ($a, $b) {
            return $a['sort'] - $b['sort'];
        });
        $firstStatusId = array_key_first($statuses);

        $totalLeads = 0;
        $wonLeads = 0;
        $byStatus = [];

        foreach ($leads as $lead) {
            /** @var LeadModel $lead */
            $totalLeads++;
            $statusId = $lead->getStatusId();

            if (!isset($byStatus[$statusId])) {
                $byStatus[$statusId] = [
                    'name' => $statuses[$statusId]['name'] ?? 'Unknown',
                    'count' => 0,
                    'won_count' => 0,
                ];
            }

            $byStatus[$statusId]['count']++;

            if ($lead->getStatusId() === LeadModel::WON_STATUS_ID) {
                $wonLeads++;
                $byStatus[$statusId]['won_count']++;
            }
        }

        return [
            'pipeline_id' => $pipelineId,
            'pipeline_name' => $pipelines[$pipelineId]['name'],
            'total_leads' => $totalLeads,
            'won_leads' => $wonLeads,
            'conversion_rate' => $totalLeads > 0 ? ($wonLeads / $totalLeads) * 100 : 0,
            'by_status' => $byStatus,
        ];
    }

    /**
     * Рассчитать среднее время нахождения на каждом этапе
     *
     * @param int $pipelineId ID воронки
     * @param int $startTimestamp Начало периода
     * @param int|null $endTimestamp Конец периода
     * @return array
     */
    public function calculateAverageTimeOnStage(int $pipelineId, int $startTimestamp, ?int $endTimestamp = null): array
    {
        $transitions = $this->eventExportService->getLeadStatusHistory($startTimestamp, $endTimestamp);

        $pipelines = $this->getPipelinesStructure();

        if (!isset($pipelines[$pipelineId])) {
            return [];
        }

        $statuses = $pipelines[$pipelineId]['statuses'];
        $durations = [];

        foreach ($transitions as $transition) {
            $statusId = $transition['status_id_from'] ?? null;
            $duration = $transition['duration_seconds'] ?? null;

            if ($statusId !== null && $duration !== null && $duration > 0 && $duration < 86400 * 30) {
                // Игнорируем аномально длинные периоды (> 30 дней)
                if (!isset($durations[$statusId])) {
                    $durations[$statusId] = [
                        'name' => $statuses[$statusId]['name'] ?? 'Unknown',
                        'total' => 0,
                        'count' => 0,
                    ];
                }
                $durations[$statusId]['total'] += $duration;
                $durations[$statusId]['count']++;
            }
        }

        $result = [];
        foreach ($durations as $statusId => $data) {
            $result[$statusId] = [
                'name' => $data['name'],
                'avg_seconds' => $data['count'] > 0 ? $data['total'] / $data['count'] : 0,
                'avg_hours' => $data['count'] > 0 ? ($data['total'] / $data['count']) / 3600 : 0,
                'count' => $data['count'],
            ];
        }

        return $result;
    }

    /**
     * Рассчитать средний чек по воронке
     *
     * @param int $pipelineId ID воронки
     * @param int $startTimestamp Начало периода
     * @param int|null $endTimestamp Конец периода
     * @return array
     */
    public function calculateAverageDealSize(int $pipelineId, int $startTimestamp, ?int $endTimestamp = null): array
    {
        $filter = $this->leadExportService->createFilter($startTimestamp, $endTimestamp, [$pipelineId], [LeadModel::WON_STATUS_ID]);
        $leads = $this->leadExportService->getAllLeads($filter, []);

        $prices = [];
        $totalPrice = 0;
        $wonCount = 0;

        foreach ($leads as $lead) {
            /** @var LeadModel $lead */
            $price = $lead->getPrice() ?? 0;
            if ($price > 0) {
                $prices[] = $price;
                $totalPrice += $price;
                $wonCount++;
            }
        }

        sort($prices);
        $median = count($prices) > 0 ? $prices[(int)(count($prices) / 2)] : 0;

        return [
            'pipeline_id' => $pipelineId,
            'total_revenue' => $totalPrice,
            'won_count' => $wonCount,
            'average_check' => $wonCount > 0 ? $totalPrice / $wonCount : 0,
            'median_check' => $median,
            'min_check' => count($prices) > 0 ? min($prices) : 0,
            'max_check' => count($prices) > 0 ? max($prices) : 0,
        ];
    }

    /**
     * Получить топ сделок за период
     *
     * @param int $startTimestamp Начало периода
     * @param int|null $endTimestamp Конец периода
     * @param int $limit Количество сделок
     * @param bool $byPrice Сортировка по цене (false = по score)
     * @return array
     */
    public function getTopDeals(int $startTimestamp, ?int $endTimestamp = null, int $limit = 10, bool $byPrice = true): array
    {
        $filter = $this->leadExportService->createFilter($startTimestamp, $endTimestamp);
        $filter->setLimit(500); // Получаем больше для сортировки

        $leads = $this->leadExportService->getAllLeads($filter, [LeadModel::CONTACTS]);

        $result = [];
        foreach ($leads as $lead) {
            /** @var LeadModel $lead */
            $result[] = [
                'id' => $lead->getId(),
                'name' => $lead->getName(),
                'price' => $lead->getPrice(),
                'score' => $lead->getScore(),
                'status_id' => $lead->getStatusId(),
                'responsible_user_id' => $lead->getResponsibleUserId(),
                'created_at' => $lead->getCreatedAt(),
                'is_won' => $lead->getStatusId() === LeadModel::WON_STATUS_ID,
            ];
        }

        usort($result, function ($a, $b) use ($byPrice) {
            if ($byPrice) {
                return ($b['price'] ?? 0) - ($a['price'] ?? 0);
            }
            return ($b['score'] ?? 0) - ($a['score'] ?? 0);
        });

        return array_slice($result, 0, $limit);
    }
}