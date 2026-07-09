<?php

namespace AmoCRM\Analytics\Services;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\Customers\Transactions\TransactionsCollection;
use AmoCRM\Collections\Customers\CustomersCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\Customers\Transactions\TransactionModel;

/**
 * Class TransactionExportService
 *
 * Сервис для экспорта транзакций (покупок) из amoCRM.
 * Транзакции привязаны к покупателям, поэтому требуется итерация по customerId.
 *
 * @package AmoCRM\Analytics\Services
 */
class TransactionExportService extends BaseExportService
{
    /**
     * @var \AmoCRM\EntitiesServices\Customers Сервис покупателей
     */
    protected $customersService;

    /**
     * @var \AmoCRM\EntitiesServices\Customers\Transactions Сервис транзакций
     */
    protected $transactionsService;

    /**
     * @var array|null Кэш покупателей для связи customer_id с транзакциями
     */
    protected $customersCache;

    /**
     * TransactionExportService constructor.
     *
     * @param AmoCRMApiClient $apiClient
     */
    public function __construct(AmoCRMApiClient $apiClient)
    {
        parent::__construct($apiClient);
        $this->customersService = $apiClient->customers();
        $this->transactionsService = $apiClient->transactions();
    }

    /**
     * Получить сервис сущности транзакций
     *
     * @return \AmoCRM\EntitiesServices\Customers\Transactions
     */
    protected function getEntityService()
    {
        return $this->transactionsService;
    }

    /**
     * Получить транзакции покупателя
     *
     * @param int $customerId ID покупателя
     * @param bool $accrueBonus Начислять ли бонусы
     * @return TransactionsCollection
     * @throws AmoCRMApiException
     */
    public function getCustomerTransactions(int $customerId, bool $accrueBonus = false): TransactionsCollection
    {
        $this->transactionsService->setCustomerId($customerId);
        $this->transactionsService->setAccrueBonus($accrueBonus);

        /** @var TransactionsCollection $collection */
        $collection = $this->transactionsService->get();

        $service = $this->transactionsService;
        while ($service instanceof \AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface && $collection->count() > 0) {
            $collection = $service->nextPage($collection);
        }

        return $collection;
    }

    /**
     * Получить все транзакции всех покупателей
     *
     * @param int|null $sinceTimestamp Начало периода
     * @param int|null $untilTimestamp Конец периода
     * @return array Массив транзакций
     * @throws AmoCRMApiException
     */
    public function getAllTransactions(?int $sinceTimestamp = null, ?int $untilTimestamp = null): array
    {
        $results = [];

        // Получаем всех покупателей
        $customers = $this->getAllCustomers($sinceTimestamp, $untilTimestamp);

        foreach ($customers as $customer) {
            $customerId = $customer->getId();
            if ($customerId === null) {
                continue;
            }

            try {
                $transactions = $this->getCustomerTransactions($customerId);

                foreach ($transactions as $transaction) {
                    /** @var TransactionModel $transaction */
                    // Фильтруем по дате, если указана
                    $completedAt = $transaction->getCompletedAt();
                    if ($sinceTimestamp !== null && $completedAt !== null && $completedAt < $sinceTimestamp) {
                        continue;
                    }
                    if ($untilTimestamp !== null && $completedAt !== null && $completedAt > $untilTimestamp) {
                        continue;
                    }

                    $results[] = \AmoCRM\Analytics\Models\TransactionFactModel::fromApiModel($transaction)->toArray();
                }
            } catch (AmoCRMApiException $e) {
                $this->logError("Failed to get transactions for customer {$customerId}: " . $e->getMessage());
            }
        }

        return $results;
    }

    /**
     * Получить всех покупателей
     *
     * @param int|null $sinceTimestamp Начало периода
     * @param int|null $untilTimestamp Конец периода
     * @return CustomersCollection
     * @throws AmoCRMApiException
     */
    protected function getAllCustomers(?int $sinceTimestamp = null, ?int $untilTimestamp = null): CustomersCollection
    {
        $filter = new \AmoCRM\Filters\CustomersFilter();
        $filter->setLimit($this->defaultPageSize);

        if ($sinceTimestamp !== null) {
            $filter->setUpdatedAt($sinceTimestamp, $untilTimestamp);
        }

        /** @var CustomersCollection $collection */
        $collection = $this->customersService->get($filter);

        $service = $this->customersService;
        while ($service instanceof \AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface && $collection->count() > 0) {
            $collection = $service->nextPage($collection);
        }

        return $collection;
    }

    /**
     * Получить транзакции за период
     *
     * @param int $startTimestamp Начало периода
     * @param int|null $endTimestamp Конец периода
     * @return array
     * @throws AmoCRMApiException
     */
    public function getTransactionsBetween(int $startTimestamp, ?int $endTimestamp = null): array
    {
        return $this->getAllTransactions($startTimestamp, $endTimestamp);
    }

    /**
     * Получить статистику по транзакциям
     *
     * @param int $startTimestamp Начало периода
     * @param int|null $endTimestamp Конец периода
     * @return array
     * @throws AmoCRMApiException
     */
    public function getTransactionStats(int $startTimestamp, ?int $endTimestamp = null): array
    {
        $transactions = $this->getAllTransactions($startTimestamp, $endTimestamp);

        $stats = [
            'total_count' => 0,
            'total_price' => 0,
            'by_customer' => [],
            'average_price' => 0,
        ];

        foreach ($transactions as $transaction) {
            $stats['total_count']++;
            $price = $transaction['price'] ?? 0;
            $stats['total_price'] += $price;

            $customerId = $transaction['customer_id'] ?? null;
            if ($customerId !== null) {
                if (!isset($stats['by_customer'][$customerId])) {
                    $stats['by_customer'][$customerId] = [
                        'count' => 0,
                        'total_price' => 0,
                    ];
                }
                $stats['by_customer'][$customerId]['count']++;
                $stats['by_customer'][$customerId]['total_price'] += $price;
            }
        }

        $stats['average_price'] = $stats['total_count'] > 0
            ? $stats['total_price'] / $stats['total_count']
            : 0;

        return $stats;
    }

    /**
     * Получить топ покупателей по сумме покупок
     *
     * @param int $startTimestamp Начало периода
     * @param int|null $endTimestamp Конец периода
     * @param int $limit Количество покупателей
     * @return array
     * @throws AmoCRMApiException
     */
    public function getTopCustomers(int $startTimestamp, ?int $endTimestamp = null, int $limit = 10): array
    {
        $stats = $this->getTransactionStats($startTimestamp, $endTimestamp);

        $customers = $stats['by_customer'];
        uasort($customers, function ($a, $b) {
            return $b['total_price'] - $a['total_price'];
        });

        return array_slice($customers, 0, $limit, true);
    }

    /**
     * Применить фильтр по createdAt к базовому фильтру
     *
     * @param \AmoCRM\Filters\CustomersFilter $filter
     * @param int $timestamp
     * @return \AmoCRM\Filters\CustomersFilter
     */
    protected function applyUpdatedAtFilter($filter, int $timestamp): \AmoCRM\Filters\CustomersFilter
    {
        $filter->setUpdatedAt($timestamp, null);
        return $filter;
    }

    /**
     * Обработать один элемент коллекции
     *
     * @param TransactionModel $item
     * @return array|null
     */
    protected function processItem($item): ?array
    {
        if ($item === null || !($item instanceof TransactionModel)) {
            return null;
        }

        return \AmoCRM\Analytics\Models\TransactionFactModel::fromApiModel($item)->toArray();
    }
}