<?php

namespace AmoCRM\Analytics\Services;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\EventsCollections;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\EventsFilter;
use AmoCRM\Models\EventModel;
use AmoCRM\Models\LeadModel;

/**
 * Class EventExportService
 *
 * Сервис для экспорта событий из amoCRM.
 * Используется для получения истории изменений статусов сделок.
 *
 * @package AmoCRM\Analytics\Services
 */
class EventExportService extends BaseExportService
{
    /**
     * Типы событий для аналитики сделок
     */
    public const TYPE_LEAD_STATUS_CHANGED = 'lead_status_changed';
    public const TYPE_LEAD_ADDED = 'lead_added';
    public const TYPE_LEAD_PRICE_CHANGED = 'lead_price_changed';
    public const TYPE_LEAD_RESPONSIBLE_CHANGED = 'lead_responsible_changed';
    public const TYPE_CONTACT_ADDED = 'contact_added';
    public const TYPE_CONTACT_UPDATED = 'contact_updated';
    public const TYPE_COMPANY_ADDED = 'company_added';
    public const TYPE_CUSTOMER_ADDED = 'customer_added';

    /**
     * Все типы событий сделок
     */
    public const LEAD_EVENT_TYPES = [
        self::TYPE_LEAD_STATUS_CHANGED,
        self::TYPE_LEAD_ADDED,
        self::TYPE_LEAD_PRICE_CHANGED,
        self::TYPE_LEAD_RESPONSIBLE_CHANGED,
    ];

    /**
     * @var array|null Кэш для хранения истории переходов
     */
    protected $statusTransitionsCache = [];

    /**
     * @var array|null Кэш для хранения timestamp предыдущих событий
     */
    protected $previousEventTimestamps = [];

    /**
     * EventExportService constructor.
     *
     * @param AmoCRMApiClient $apiClient
     */
    public function __construct(AmoCRMApiClient $apiClient)
    {
        parent::__construct($apiClient);
    }

    /**
     * Получить сервис сущности событий
     *
     * @return \AmoCRM\EntitiesServices\Events
     */
    protected function getEntityService()
    {
        return $this->apiClient->events();
    }

    /**
     * Создать фильтр событий
     *
     * @param int|null $sinceTimestamp Начало периода
     * @param int|null $untilTimestamp Конец периода
     * @param array $entityTypes Типы сущностей (leads, contacts, etc.)
     * @param array $eventTypes Типы событий
     * @return EventsFilter
     */
    public function createFilter(
        ?int $sinceTimestamp = null,
        ?int $untilTimestamp = null,
        array $entityTypes = ['leads'],
        array $eventTypes = []
    ): EventsFilter {
        $filter = new EventsFilter();

        if ($sinceTimestamp !== null) {
            $filter->setCreatedAt($sinceTimestamp, $untilTimestamp);
        }

        if (!empty($entityTypes)) {
            $filter->setEntity($entityTypes);
        }

        if (!empty($eventTypes)) {
            $filter->setTypes($eventTypes);
        }

        return $filter;
    }

    /**
     * Получить историю изменений статусов сделок
     *
     * @param int|null $sinceTimestamp Начало периода
     * @param int|null $untilTimestamp Конец периода
     * @param array $leadIds Фильтр по ID сделок (пустой = все)
     * @return array Массив переходов по статусам
     * @throws AmoCRMApiException
     */
    public function getLeadStatusHistory(
        ?int $sinceTimestamp = null,
        ?int $untilTimestamp = null,
        array $leadIds = []
    ): array {
        $filter = $this->createFilter(
            $sinceTimestamp,
            $untilTimestamp,
            ['leads'],
            [self::TYPE_LEAD_STATUS_CHANGED]
        );

        if (!empty($leadIds)) {
            $filter->setEntityIds($leadIds);
        }

        return $this->getAllEventsAsStatusHistory($filter);
    }

    /**
     * Получить все события как историю статусов
     *
     * @param EventsFilter $filter
     * @return array
     * @throws AmoCRMApiException
     */
    protected function getAllEventsAsStatusHistory(EventsFilter $filter): array
    {
        $service = $this->getEntityService();
        $results = [];

        /** @var EventsCollections $collection */
        $collection = $service->get($filter);

        $this->previousEventTimestamps = [];

        while ($collection !== null && !$collection->isEmpty()) {
            foreach ($collection as $event) {
                /** @var EventModel $event */
                if ($event->getType() === self::TYPE_LEAD_STATUS_CHANGED) {
                    $leadId = $event->getEntityId();
                    $previousTimestamp = $this->previousEventTimestamps[$leadId] ?? null;

                    $historyItem = \AmoCRM\Analytics\Models\LeadStatusHistoryModel::fromApiModel(
                        $event,
                        $previousTimestamp
                    )->toArray();

                    $results[] = $historyItem;

                    // Сохраняем timestamp для расчёта duration
                    $this->previousEventTimestamps[$leadId] = $event->getCreatedAt();
                }
            }

            // Пагинация
            if ($service instanceof \AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface) {
                $collection = $service->nextPage($collection);
            } else {
                break;
            }
        }

        return $results;
    }

    /**
     * Получить все события изменений статусов для расчёта времени на этапах
     *
     * @param int $leadId ID сделки
     * @return array
     * @throws AmoCRMApiException
     */
    public function getLeadStatusTransitions(int $leadId): array
    {
        $filter = $this->createFilter(null, null, ['leads'], [self::TYPE_LEAD_STATUS_CHANGED]);
        $filter->setEntityIds([$leadId]);

        return $this->getAllEventsAsStatusHistory($filter);
    }

    /**
     * Рассчитать среднее время нахождения на каждом этапе
     *
     * @param int $pipelineId ID воронки
     * @param int|null $sinceTimestamp Начало периода
     * @param int|null $untilTimestamp Конец периода
     * @return array [status_id => avg_duration_seconds]
     * @throws AmoCRMApiException
     */
    public function calculateAverageStageDuration(int $pipelineId, ?int $sinceTimestamp = null, ?int $untilTimestamp = null): array
    {
        $transitions = $this->getLeadStatusHistory($sinceTimestamp, $untilTimestamp);

        $durationsByStatus = [];
        $countByStatus = [];

        foreach ($transitions as $transition) {
            $statusId = $transition['status_id_from'];
            $duration = $transition['duration_seconds'] ?? null;

            if ($duration !== null && $duration > 0) {
                if (!isset($durationsByStatus[$statusId])) {
                    $durationsByStatus[$statusId] = 0;
                    $countByStatus[$statusId] = 0;
                }
                $durationsByStatus[$statusId] += $duration;
                $countByStatus[$statusId]++;
            }
        }

        $averages = [];
        foreach ($durationsByStatus as $statusId => $totalDuration) {
            $averages[$statusId] = $countByStatus[$statusId] > 0
                ? $totalDuration / $countByStatus[$statusId]
                : 0;
        }

        return $averages;
    }

    /**
     * Получить все события сделки
     *
     * @param int $leadId ID сделки
     * @param int|null $sinceTimestamp Начало периода
     * @return array
     * @throws AmoCRMApiException
     */
    public function getLeadEvents(int $leadId, ?int $sinceTimestamp = null): array
    {
        $filter = new EventsFilter();
        $filter->setEntity(['leads']);
        $filter->setEntityIds([$leadId]);

        if ($sinceTimestamp !== null) {
            $filter->setCreatedAt($sinceTimestamp, null);
        }

        $service = $this->getEntityService();
        $results = [];

        /** @var EventsCollections $collection */
        $collection = $service->get($filter);

        while ($collection !== null && !$collection->isEmpty()) {
            foreach ($collection as $event) {
                /** @var EventModel $event */
                $results[] = $event->toArray();
            }

            if ($service instanceof \AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface) {
                $collection = $service->nextPage($collection);
            } else {
                break;
            }
        }

        return $results;
    }

    /**
     * Получить статистику событий за период
     *
     * @param int $startTimestamp Начало периода
     * @param int|null $endTimestamp Конец периода
     * @param array $entityTypes Типы сущностей
     * @return array
     * @throws AmoCRMApiException
     */
    public function getEventStats(int $startTimestamp, ?int $endTimestamp = null, array $entityTypes = ['leads']): array
    {
        $filter = $this->createFilter($startTimestamp, $endTimestamp, $entityTypes);

        $service = $this->getEntityService();
        $stats = [
            'total_count' => 0,
            'by_type' => [],
            'by_entity' => [],
        ];

        /** @var EventsCollections $collection */
        $collection = $service->get($filter);

        while ($collection !== null && !$collection->isEmpty()) {
            foreach ($collection as $event) {
                /** @var EventModel $event */
                $stats['total_count']++;

                $eventType = $event->getType();
                if (!isset($stats['by_type'][$eventType])) {
                    $stats['by_type'][$eventType] = 0;
                }
                $stats['by_type'][$eventType]++;

                $entityType = $event->getEntityType();
                if (!isset($stats['by_entity'][$entityType])) {
                    $stats['by_entity'][$entityType] = 0;
                }
                $stats['by_entity'][$entityType]++;
            }

            if ($service instanceof \AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface) {
                $collection = $service->nextPage($collection);
            } else {
                break;
            }
        }

        return $stats;
    }

    /**
     * Применить фильтр по createdAt к базовому фильтру
     *
     * @param EventsFilter $filter
     * @param int $timestamp
     * @return EventsFilter
     */
    protected function applyUpdatedAtFilter($filter, int $timestamp): EventsFilter
    {
        $filter->setCreatedAt($timestamp, null);
        return $filter;
    }

    /**
     * Обработать один элемент коллекции
     *
     * @param EventModel $item
     * @return array|null
     */
    protected function processItem($item): ?array
    {
        if ($item === null || !($item instanceof EventModel)) {
            return null;
        }

        return $item->toArray();
    }
}