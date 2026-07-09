<?php

namespace AmoCRM\Analytics;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Models\BaseApiModel;

/**
 * Interface AnalyticsServiceInterface
 *
 * Базовый интерфейс для всех сервисов аналитики
 *
 * @package AmoCRM\Analytics
 */
interface AnalyticsServiceInterface
{
    /**
     * Получить данные с фильтрацией
     *
     * @param BaseEntityFilter|null $filter Фильтр для выборки данных
     * @param array $with Дополнительные связи для загрузки
     * @return BaseApiCollection Коллекция аналитических данных
     */
    public function get(?BaseEntityFilter $filter = null, array $with = []): ?BaseApiCollection;

    /**
     * Получить одну запись по ID
     *
     * @param int|string $id ID записи
     * @param array $with Дополнительные связи для загрузки
     * @return BaseApiModel|null Модель аналитических данных
     */
    public function getOne($id, array $with = []): ?BaseApiModel;

    /**
     * Преобразовать API-модель в модель аналитики
     *
     * @param BaseApiModel $apiModel Исходная модель из API
     * @return BaseApiModel Модель аналитики
     */
    public function convertToAnalyticsModel(BaseApiModel $apiModel): BaseApiModel;

    /**
     * Получить timestamp последней синхронизации
     *
     * @return int|null Unix timestamp или null если синхронизация не выполнялась
     */
    public function getLastSyncTimestamp(): ?int;

    /**
     * Установить timestamp последней синхронизации
     *
     * @param int $timestamp Unix timestamp
     * @return self
     */
    public function setLastSyncTimestamp(int $timestamp): self;
}