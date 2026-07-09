<?php

namespace AmoCRM\Analytics\Services;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\LeadsFilter;
use AmoCRM\Models\LeadModel;

/**
 * Class LeadExportService
 *
 * Сервис для экспорта сделок из amoCRM.
 * Поддерживает инкрементальную выгрузку и связанные сущности.
 *
 * @package AmoCRM\Analytics\Services
 */
class LeadExportService extends BaseExportService
{
    /**
     * Константы для типов событий изменения статусов
     */
    public const EVENT_STATUS_CHANGED = 'lead_status_changed';
    public const EVENT_LEAD_ADDED = 'lead_added';
    public const EVENT_LEAD_UPDATED = 'lead_updated';
    public const EVENT_LEAD_DELETED = 'lead_deleted';

    /**
     * Связи для загрузки с каждой сделкой
     * @var array
     */
    protected $defaultWith = [
        LeadModel::CONTACTS,
        LeadModel::COMPANY,
        LeadModel::SOURCE,
        LeadModel::CATALOG_ELEMENTS,
    ];

    /**
     * @var array|null Кэш воронок для преобразования pipeline_id в название
     */
    protected $pipelinesCache;

    /**
     * @var array|null Кэш статусов для преобразования status_id в название
     */
    protected $statusesCache;

    /**
     * LeadExportService constructor.
     *
     * @param AmoCRMApiClient $apiClient
     */
    public function __construct(AmoCRMApiClient $apiClient)
    {
        parent::__construct($apiClient);
    }

    /**
     * Получить сервис сущности сделок
     *
     * @return \AmoCRM\EntitiesServices\Leads
     */
    protected function getEntityService()
    {
        return $this->apiClient->leads();
    }

    /**
     * Получить фильтр сделок
     *
     * @param int|null $sinceTimestamp Начало периода (updatedAt)
     * @param int|null $untilTimestamp Конец периода
     * @param array $pipelineIds Фильтр по ID воронок
     * @param array $statusIds Фильтр по ID статусов
     * @return LeadsFilter
     */
    public function createFilter(
        ?int $sinceTimestamp = null,
        ?int $untilTimestamp = null,
        array $pipelineIds = [],
        array $statusIds = []
    ): LeadsFilter {
        $filter = new LeadsFilter();
        $filter->setLimit($this->defaultPageSize);

        if ($sinceTimestamp !== null) {
            $filter->setUpdatedAt($sinceTimestamp, $untilTimestamp);
        }

        if (!empty($pipelineIds)) {
            $filter->setPipelineIds($pipelineIds);
        }

        if (!empty($statusIds)) {
            $filter->setStatuses($statusIds);
        }

        return $filter;
    }

    /**
     * Получить все сделки с заданными фильтрами
     *
     * @param LeadsFilter|null $filter
     * @param array|null $with Связи для загрузки
     * @return LeadsCollection
     * @throws AmoCRMApiException
     */
    public function getAllLeads(?LeadsFilter $filter = null, ?array $with = null): LeadsCollection
    {
        if ($filter === null) {
            $filter = new LeadsFilter();
            $filter->setLimit($this->defaultPageSize);
        }

        $with = $with ?? $this->defaultWith;

        /** @var LeadsCollection $collection */
        $collection = $this->getEntityService()->get($filter, $with);

        // Собираем все страницы
        $service = $this->getEntityService();
        while ($service instanceof \AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface && $collection->count() > 0) {
            $collection = $service->nextPage($collection);
        }

        return $collection;
    }

    /**
     * Получить сделки, обновлённые с момента последней синхронизации
     *
     * @param int|null $sinceTimestamp Timestamp начала периода
     * @param array $with Связи для загрузки
     * @return LeadsCollection
     * @throws AmoCRMApiException
     */
    public function getLeadsUpdatedSince(?int $sinceTimestamp = null, array $with = []): LeadsCollection
    {
        $with = empty($with) ? $this->defaultWith : $with;

        $filter = new LeadsFilter();
        $filter->setLimit($this->defaultPageSize);

        if ($sinceTimestamp !== null) {
            $filter->setUpdatedAt($sinceTimestamp, null);
            $this->setLastSyncTimestamp($sinceTimestamp);
        }

        return $this->getAllLeads($filter, $with);
    }

    /**
     * Получить сделки за период
     *
     * @param int $startTimestamp Начало периода (createdAt)
     * @param int|null $endTimestamp Конец периода
     * @param array $with Связи для загрузки
     * @return LeadsCollection
     * @throws AmoCRMApiException
     */
    public function getLeadsCreatedBetween(int $startTimestamp, ?int $endTimestamp = null, array $with = []): LeadsCollection
    {
        $with = empty($with) ? $this->defaultWith : $with;

        $filter = new LeadsFilter();
        $filter->setLimit($this->defaultPageSize);
        $filter->setCreatedAt($startTimestamp, $endTimestamp);

        return $this->getAllLeads($filter, $with);
    }

    /**
     * Получить сделки по ID воронки
     *
     * @param int $pipelineId ID воронки
     * @param int|null $sinceTimestamp Начало периода
     * @param array $with Связи для загрузки
     * @return LeadsCollection
     * @throws AmoCRMApiException
     */
    public function getLeadsByPipeline(int $pipelineId, ?int $sinceTimestamp = null, array $with = []): LeadsCollection
    {
        $with = empty($with) ? $this->defaultWith : $with;

        $filter = $this->createFilter($sinceTimestamp, null, [$pipelineId]);

        return $this->getAllLeads($filter, $with);
    }

    /**
     * Получить побеждённые сделки за период
     *
     * @param int $startTimestamp Начало периода
     * @param int|null $endTimestamp Конец периода
     * @param array $with Связи для загрузки
     * @return LeadsCollection
     * @throws AmoCRMApiException
     */
    public function getWonLeads(int $startTimestamp, ?int $endTimestamp = null, array $with = []): LeadsCollection
    {
        return $this->getLeadsByStatus(LeadModel::WON_STATUS_ID, $startTimestamp, $endTimestamp, $with);
    }

    /**
     * Получить проигранные сделки за период
     *
     * @param int $startTimestamp Начало периода
     * @param int|null $endTimestamp Конец периода
     * @param array $with Связи для загрузки
     * @return LeadsCollection
     * @throws AmoCRMApiException
     */
    public function getLostLeads(int $startTimestamp, ?int $endTimestamp = null, array $with = []): LeadsCollection
    {
        return $this->getLeadsByStatus(LeadModel::LOST_STATUS_ID, $startTimestamp, $endTimestamp, $with);
    }

    /**
     * Получить сделки по ID статуса
     *
     * @param int $statusId ID статуса
     * @param int|null $sinceTimestamp Начало периода
     * @param int|null $endTimestamp Конец периода
     * @param array $with Связи для загрузки
     * @return LeadsCollection
     * @throws AmoCRMApiException
     */
    public function getLeadsByStatus(int $statusId, ?int $sinceTimestamp = null, ?int $endTimestamp = null, array $with = []): LeadsCollection
    {
        $with = empty($with) ? $this->defaultWith : $with;

        $filter = new LeadsFilter();
        $filter->setLimit($this->defaultPageSize);
        $filter->setStatuses([$statusId]);

        if ($sinceTimestamp !== null) {
            $filter->setCreatedAt($sinceTimestamp, $endTimestamp);
        }

        return $this->getAllLeads($filter, $with);
    }

    /**
     * Получить статистику по сделкам за период
     *
     * @param int $startTimestamp Начало периода
     * @param int|null $endTimestamp Конец периода
     * @param array $pipelineIds Фильтр по воронкам
     * @return array
     * @throws AmoCRMApiException
     */
    public function getLeadsStats(int $startTimestamp, ?int $endTimestamp = null, array $pipelineIds = []): array
    {
        $filter = new LeadsFilter();
        $filter->setCreatedAt($startTimestamp, $endTimestamp);
        $filter->setLimit($this->defaultPageSize);

        if (!empty($pipelineIds)) {
            $filter->setPipelineIds($pipelineIds);
        }

        $totalPrice = 0;
        $wonPrice = 0;
        $totalCount = 0;
        $wonCount = 0;
        $lostCount = 0;
        $byPipeline = [];
        $byStatus = [];

        $collection = $this->getAllLeads($filter, [LeadModel::CONTACTS, LeadModel::COMPANY]);

        foreach ($collection as $lead) {
            /** @var LeadModel $lead */
            $totalCount++;
            $totalPrice += $lead->getPrice() ?? 0;

            // Группировка по воронке
            $pipelineId = $lead->getPipelineId();
            if (!isset($byPipeline[$pipelineId])) {
                $byPipeline[$pipelineId] = [
                    'count' => 0,
                    'price' => 0,
                    'won_count' => 0,
                    'won_price' => 0,
                    'lost_count' => 0,
                ];
            }
            $byPipeline[$pipelineId]['count']++;
            $byPipeline[$pipelineId]['price'] += $lead->getPrice() ?? 0;

            // Группировка по статусу
            $statusId = $lead->getStatusId();
            if (!isset($byStatus[$statusId])) {
                $byStatus[$statusId] = [
                    'count' => 0,
                    'price' => 0,
                ];
            }
            $byStatus[$statusId]['count']++;
            $byStatus[$statusId]['price'] += $lead->getPrice() ?? 0;

            // Победы и проигрыши
            if ($lead->getStatusId() === LeadModel::WON_STATUS_ID) {
                $wonCount++;
                $wonPrice += $lead->getPrice() ?? 0;
                $byPipeline[$pipelineId]['won_count']++;
                $byPipeline[$pipelineId]['won_price'] += $lead->getPrice() ?? 0;
            } elseif ($lead->getStatusId() === LeadModel::LOST_STATUS_ID) {
                $lostCount++;
                $byPipeline[$pipelineId]['lost_count']++;
            }
        }

        return [
            'total_count' => $totalCount,
            'total_price' => $totalPrice,
            'won_count' => $wonCount,
            'won_price' => $wonPrice,
            'lost_count' => $lostCount,
            'by_pipeline' => $byPipeline,
            'by_status' => $byStatus,
            'average_price' => $totalCount > 0 ? $totalPrice / $totalCount : 0,
            'win_rate' => $totalCount > 0 ? ($wonCount / $totalCount) * 100 : 0,
        ];
    }

    /**
     * Обработать один элемент коллекции
     *
     * @param LeadModel $item
     * @return array|null
     */
    protected function processItem($item): ?array
    {
        if ($item === null || !($item instanceof LeadModel)) {
            return null;
        }

        return \AmoCRM\Analytics\Models\LeadFactModel::fromApiModel($item)->toArray();
    }

    /**
     * Применить фильтр по updatedAt к базовому фильтру
     *
     * @param \AmoCRM\Filters\LeadsFilter $filter
     * @param int $timestamp
     * @return LeadsFilter
     */
    protected function applyUpdatedAtFilter($filter, int $timestamp): LeadsFilter
    {
        $filter->setUpdatedAt($timestamp, null);
        return $filter;
    }
}