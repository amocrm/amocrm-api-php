<?php

namespace AmoCRM\Analytics\Services;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\Leads\Unsorted\UnsortedCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\UnsortedFilter;
use AmoCRM\Models\Unsorted\BaseUnsortedModel;

/**
 * Class UnsortedExportService
 *
 * Сервис для экспорта неразобранного (первых касаний) из amoCRM.
 * Используется для аналитики источников привлечения.
 *
 * @package AmoCRM\Analytics\Services
 */
class UnsortedExportService extends BaseExportService
{
    /**
     * Категории неразобранного
     */
    public const CATEGORY_SIP = BaseUnsortedModel::CATEGORY_CODE_SIP;
    public const CATEGORY_MAIL = BaseUnsortedModel::CATEGORY_CODE_MAIL;
    public const CATEGORY_FORMS = BaseUnsortedModel::CATEGORY_CODE_FORMS;
    public const CATEGORY_CHATS = BaseUnsortedModel::CATEGORY_CODE_CHATS;

    /**
     * Все категории
     */
    public const ALL_CATEGORIES = [
        self::CATEGORY_SIP,
        self::CATEGORY_MAIL,
        self::CATEGORY_FORMS,
        self::CATEGORY_CHATS,
    ];

    /**
     * @var array|null Кэш для сводки по неразобранному
     */
    protected $summaryCache;

    /**
     * UnsortedExportService constructor.
     *
     * @param AmoCRMApiClient $apiClient
     */
    public function __construct(AmoCRMApiClient $apiClient)
    {
        parent::__construct($apiClient);
    }

    /**
     * Получить сервис сущности неразобранного
     *
     * @return \AmoCRM\EntitiesServices\Unsorted
     */
    protected function getEntityService()
    {
        return $this->apiClient->unsorted();
    }

    /**
     * Создать фильтр неразобранного
     *
     * @param int|null $sinceTimestamp Начало периода
     * @param int|null $untilTimestamp Конец периода
     * @param array $categories Категории для фильтрации
     * @param int|null $pipelineId ID воронки
     * @return UnsortedFilter
     */
    public function createFilter(
        ?int $sinceTimestamp = null,
        ?int $untilTimestamp = null,
        array $categories = [],
        ?int $pipelineId = null
    ): UnsortedFilter {
        $filter = new UnsortedFilter();

        if ($sinceTimestamp !== null) {
            $filter->setCreatedAt($sinceTimestamp, $untilTimestamp);
        }

        if (!empty($categories)) {
            $filter->setCategory($categories);
        }

        if ($pipelineId !== null) {
            $filter->setPipelineId($pipelineId);
        }

        return $filter;
    }

    /**
     * Получить все обращения неразобранного
     *
     * @param UnsortedFilter|null $filter
     * @return UnsortedCollection
     * @throws AmoCRMApiException
     */
    public function getAllUnsorted(?UnsortedFilter $filter = null): UnsortedCollection
    {
        if ($filter === null) {
            $filter = new UnsortedFilter();
        }

        /** @var UnsortedCollection $collection */
        $collection = $this->getEntityService()->get($filter);

        $service = $this->getEntityService();
        while ($service instanceof \AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface && $collection->count() > 0) {
            $collection = $service->nextPage($collection);
        }

        return $collection;
    }

    /**
     * Получить обращения за период
     *
     * @param int $startTimestamp Начало периода
     * @param int|null $endTimestamp Конец периода
     * @param array $categories Категории
     * @return array
     * @throws AmoCRMApiException
     */
    public function getUnsortedBetween(int $startTimestamp, ?int $endTimestamp = null, array $categories = []): array
    {
        $filter = $this->createFilter($startTimestamp, $endTimestamp, $categories);

        $collection = $this->getAllUnsorted($filter);
        $results = [];

        foreach ($collection as $item) {
            /** @var BaseUnsortedModel $item */
            $results[] = \AmoCRM\Analytics\Models\FirstTouchFactModel::fromApiModel($item)->toArray();
        }

        return $results;
    }

    /**
     * Получить сводку по неразобранному
     *
     * @param int|null $sinceTimestamp Начало периода
     * @param int|null $untilTimestamp Конец периода
     * @return array
     * @throws AmoCRMApiException
     */
    public function getSummary(?int $sinceTimestamp = null, ?int $untilTimestamp = null): array
    {
        $filter = new UnsortedFilter();

        if ($sinceTimestamp !== null) {
            $filter->setCreatedAt($sinceTimestamp, $untilTimestamp);
        }

        return $this->getEntityService()->summary($filter)->toArray();
    }

    /**
     * Получить статистику по категориям
     *
     * @param int $startTimestamp Начало периода
     * @param int|null $endTimestamp Конец периода
     * @return array
     * @throws AmoCRMApiException
     */
    public function getCategoryStats(int $startTimestamp, ?int $endTimestamp = null): array
    {
        $filter = $this->createFilter($startTimestamp, $endTimestamp);
        $collection = $this->getAllUnsorted($filter);

        $stats = [
            'total' => 0,
            'by_category' => [],
            'by_pipeline' => [],
        ];

        foreach ($collection as $item) {
            /** @var BaseUnsortedModel $item */
            $stats['total']++;

            $category = $item->getCategory();
            if (!isset($stats['by_category'][$category])) {
                $stats['by_category'][$category] = 0;
            }
            $stats['by_category'][$category]++;

            $pipelineId = $item->getPipelineId();
            if (!isset($stats['by_pipeline'][$pipelineId])) {
                $stats['by_pipeline'][$pipelineId] = 0;
            }
            $stats['by_pipeline'][$pipelineId]++;
        }

        return $stats;
    }

    /**
     * Получить "горячие" обращения (SIP и чаты)
     *
     * @param int|null $sinceTimestamp Начало периода
     * @param int|null $untilTimestamp Конец периода
     * @return array
     * @throws AmoCRMApiException
     */
    public function getHotLeads(?int $sinceTimestamp = null, ?int $untilTimestamp = null): array
    {
        $filter = $this->createFilter(
            $sinceTimestamp,
            $untilTimestamp,
            [self::CATEGORY_SIP, self::CATEGORY_CHATS]
        );

        $collection = $this->getAllUnsorted($filter);
        $results = [];

        foreach ($collection as $item) {
            /** @var BaseUnsortedModel $item */
            $results[] = \AmoCRM\Analytics\Models\FirstTouchFactModel::fromApiModel($item)->toArray();
        }

        return $results;
    }

    /**
     * Получить обращения из форм
     *
     * @param int|null $sinceTimestamp Начало периода
     * @param int|null $untilTimestamp Конец периода
     * @return array
     * @throws AmoCRMApiException
     */
    public function getFormSubmissions(?int $sinceTimestamp = null, ?int $untilTimestamp = null): array
    {
        $filter = $this->createFilter(
            $sinceTimestamp,
            $untilTimestamp,
            [self::CATEGORY_FORMS]
        );

        $collection = $this->getAllUnsorted($filter);
        $results = [];

        foreach ($collection as $item) {
            /** @var BaseUnsortedModel $item */
            $results[] = \AmoCRM\Analytics\Models\FirstTouchFactModel::fromApiModel($item)->toArray();
        }

        return $results;
    }

    /**
     * Применить фильтр по createdAt к базовому фильтру
     *
     * @param UnsortedFilter $filter
     * @param int $timestamp
     * @return UnsortedFilter
     */
    protected function applyUpdatedAtFilter($filter, int $timestamp): UnsortedFilter
    {
        $filter->setCreatedAt($timestamp, null);
        return $filter;
    }

    /**
     * Обработать один элемент коллекции
     *
     * @param BaseUnsortedModel $item
     * @return array|null
     */
    protected function processItem($item): ?array
    {
        if ($item === null || !($item instanceof BaseUnsortedModel)) {
            return null;
        }

        return \AmoCRM\Analytics\Models\FirstTouchFactModel::fromApiModel($item)->toArray();
    }
}