<?php

namespace AmoCRM\Analytics\Services;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\BaseEntityFilter;

/**
 * Class BaseExportService
 *
 * Базовый класс для всех сервисов экспорта данных.
 * Обеспечивает общую функциональность для пагинации, инкрементальной выгрузки и логирования.
 *
 * @package AmoCRM\Analytics\Services
 */
abstract class BaseExportService
{
    /**
     * @var AmoCRMApiClient
     */
    protected $apiClient;

    /**
     * @var int|null Timestamp последней синхронизации
     */
    protected $lastSyncTimestamp;

    /**
     * @var int Максимальное количество страниц для выгрузки
     */
    protected $maxPages = 100;

    /**
     * @var int Размер страницы по умолчанию
     */
    protected $defaultPageSize = 250;

    /**
     * @var array|null Кэш для хранения ID обработанных записей
     */
    protected $processedIds = [];

    /**
     * @var array Лог ошибок
     */
    protected $errorLog = [];

    /**
     * BaseExportService constructor.
     *
     * @param AmoCRMApiClient $apiClient
     */
    public function __construct(AmoCRMApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * Получить API-клиент
     *
     * @return AmoCRMApiClient
     */
    public function getApiClient(): AmoCRMApiClient
    {
        return $this->apiClient;
    }

    /**
     * Получить timestamp последней синхронизации
     *
     * @return int|null
     */
    public function getLastSyncTimestamp(): ?int
    {
        return $this->lastSyncTimestamp;
    }

    /**
     * Установить timestamp последней синхронизации
     *
     * @param int $timestamp
     * @return self
     */
    public function setLastSyncTimestamp(int $timestamp): self
    {
        $this->lastSyncTimestamp = $timestamp;
        return $this;
    }

    /**
     * Получить максимальное количество страниц
     *
     * @return int
     */
    public function getMaxPages(): int
    {
        return $this->maxPages;
    }

    /**
     * Установить максимальное количество страниц
     *
     * @param int $maxPages
     * @return self
     */
    public function setMaxPages(int $maxPages): self
    {
        $this->maxPages = $maxPages;
        return $this;
    }

    /**
     * Получить размер страницы по умолчанию
     *
     * @return int
     */
    public function getDefaultPageSize(): int
    {
        return $this->defaultPageSize;
    }

    /**
     * Установить размер страницы по умолчанию
     *
     * @param int $pageSize
     * @return self
     */
    public function setDefaultPageSize(int $pageSize): self
    {
        $this->defaultPageSize = $pageSize;
        return $this;
    }

    /**
     * Получить лог ошибок
     *
     * @return array
     */
    public function getErrorLog(): array
    {
        return $this->errorLog;
    }

    /**
     * Очистить лог ошибок
     *
     * @return self
     */
    public function clearErrorLog(): self
    {
        $this->errorLog = [];
        return $this;
    }

    /**
     * Добавить ошибку в лог
     *
     * @param string $message
     * @param array $context
     * @return self
     */
    protected function logError(string $message, array $context = []): self
    {
        $this->errorLog[] = [
            'timestamp' => time(),
            'message' => $message,
            'context' => $context,
        ];
        return $this;
    }

    /**
     * Проверить, была ли уже обработана запись
     *
     * @param int|string $id
     * @return bool
     */
    public function isProcessed($id): bool
    {
        return isset($this->processedIds[$id]);
    }

    /**
     * Пометить запись как обработанную
     *
     * @param int|string $id
     * @return self
     */
    public function markAsProcessed($id): self
    {
        $this->processedIds[$id] = true;
        return $this;
    }

    /**
     * Очистить список обработанных ID
     *
     * @return self
     */
    public function clearProcessedIds(): self
    {
        $this->processedIds = [];
        return $this;
    }

    /**
     * Получить количество обработанных записей
     *
     * @return int
     */
    public function getProcessedCount(): int
    {
        return count($this->processedIds);
    }

    /**
     * Получить все данные с автоматической пагинацией
     *
     * @param BaseEntityFilter|null $filter
     * @param array $with
     * @return array Массив обработанных данных
     * @throws AmoCRMApiException
     */
    protected function fetchAllPages(?BaseEntityFilter $filter = null, array $with = []): array
    {
        $service = $this->getEntityService();
        $results = [];
        $pageCount = 0;

        // Если сервис поддерживает пагинацию
        if ($service instanceof HasPageMethodsInterface) {
            $collection = $service->get($filter, $with);

            while ($collection !== null && !$collection->isEmpty() && $pageCount < $this->maxPages) {
                $pageCount++;

                // Обрабатываем элементы страницы
                foreach ($collection as $item) {
                    $id = $item->getId();
                    if (!$this->isProcessed($id)) {
                        $processedItem = $this->processItem($item);
                        if ($processedItem !== null) {
                            $results[] = $processedItem;
                        }
                        $this->markAsProcessed($id);
                    }
                }

                // Переходим к следующей странице
                $collection = $service->nextPage($collection);
            }
        } else {
            // Сервис без пагинации
            $collection = $service->get($filter, $with);

            if ($collection !== null && !$collection->isEmpty()) {
                foreach ($collection as $item) {
                    $id = $item->getId();
                    if (!$this->isProcessed($id)) {
                        $processedItem = $this->processItem($item);
                        if ($processedItem !== null) {
                            $results[] = $processedItem;
                        }
                        $this->markAsProcessed($id);
                    }
                }
            }
        }

        return $results;
    }

    /**
     * Получить данные, обновлённые с момента последней синхронизации
     *
     * @param int|null $sinceTimestamp Timestamp начала периода
     * @param BaseEntityFilter|null $baseFilter Базовый фильтр
     * @param array $with Дополнительные связи
     * @return array Массив обработанных данных
     * @throws AmoCRMApiException
     */
    protected function fetchSince(?int $sinceTimestamp = null, ?BaseEntityFilter $baseFilter = null, array $with = []): array
    {
        if ($sinceTimestamp !== null) {
            $this->setLastSyncTimestamp($sinceTimestamp);
        }

        // Если есть timestamp последней синхронизации, добавляем фильтр по updatedAt
        if ($this->lastSyncTimestamp !== null && $baseFilter !== null) {
            $baseFilter = $this->applyUpdatedAtFilter($baseFilter, $this->lastSyncTimestamp);
        }

        return $this->fetchAllPages($baseFilter, $with);
    }

    /**
     * Применить фильтр по updatedAt к базовому фильтру
     * Переопределяется в наследниках для разных типов фильтров
     *
     * @param BaseEntityFilter $filter
     * @param int $timestamp
     * @return BaseEntityFilter
     */
    protected function applyUpdatedAtFilter(BaseEntityFilter $filter, int $timestamp): BaseEntityFilter
    {
        // По умолчанию пытаемся установить updatedAt
        if (method_exists($filter, 'setUpdatedAt')) {
            $filter->setUpdatedAt($timestamp, null);
        }
        return $filter;
    }

    /**
     * Обработать один элемент коллекции
     * Переопределяется в наследниках для преобразования в аналитическую модель
     *
     * @param mixed $item
     * @return array|null
     */
    protected function processItem($item): ?array
    {
        if ($item !== null && method_exists($item, 'toArray')) {
            return $item->toArray();
        }
        return null;
    }

    /**
     * Получить сервис сущности
     * Переопределяется в наследниках
     *
     * @return mixed
     */
    abstract protected function getEntityService();

    /**
     * Выполнить экспорт с retry при ошибках
     *
     * @param int $maxRetries Максимальное количество попыток
     * @param int $retryDelay Задержка между попытками в секундах
     * @param BaseEntityFilter|null $filter
     * @param array $with
     * @return array
     */
    public function exportWithRetry(int $maxRetries = 3, int $retryDelay = 5, ?BaseEntityFilter $filter = null, array $with = []): array
    {
        $attempts = 0;
        $lastError = null;

        while ($attempts < $maxRetries) {
            try {
                $attempts++;
                return $this->fetchAllPages($filter, $with);
            } catch (AmoCRMApiException $e) {
                $lastError = $e;
                $this->logError("Export attempt {$attempts} failed: " . $e->getMessage(), [
                    'filter' => $filter !== null ? get_class($filter) : null,
                    'with' => $with,
                ]);

                if ($attempts < $maxRetries) {
                    sleep($retryDelay);
                    $retryDelay *= 2; // Экспоненциальная задержка
                }
            }
        }

        // После всех попыток бросаем последнее исключение
        throw $lastError;
    }

    /**
     * Получить статистику экспорта
     *
     * @return array
     */
    public function getExportStats(): array
    {
        return [
            'processed_count' => $this->getProcessedCount(),
            'last_sync_timestamp' => $this->lastSyncTimestamp,
            'error_count' => count($this->errorLog),
            'max_pages' => $this->maxPages,
            'default_page_size' => $this->defaultPageSize,
        ];
    }
}