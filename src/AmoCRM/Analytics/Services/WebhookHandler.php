<?php

namespace AmoCRM\Analytics\Services;

use AmoCRM\Analytics\Models\CallFactModel;
use AmoCRM\Analytics\Models\FirstTouchFactModel;
use AmoCRM\Analytics\Models\LeadFactModel;
use AmoCRM\Analytics\Models\LeadStatusHistoryModel;
use AmoCRM\Analytics\Models\TransactionFactModel;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Models\LeadModel;

/**
 * Class WebhookHandler
 *
 * Обработчик вебхуков от amoCRM для real-time аналитики.
 * Парсит входящие webhook-запросы и преобразует в аналитические модели.
 *
 * @package AmoCRM\Analytics\Services
 */
class WebhookHandler
{
    /**
     * Типы событий вебхуков
     */
    public const EVENT_ADD_LEAD = 'add_lead';
    public const EVENT_UPDATE_LEAD = 'update_lead';
    public const EVENT_DELETE_LEAD = 'delete_lead';
    public const EVENT_ADD_CONTACT = 'add_contact';
    public const EVENT_UPDATE_CONTACT = 'update_contact';
    public const EVENT_DELETE_CONTACT = 'delete_contact';
    public const EVENT_ADD_COMPANY = 'add_company';
    public const EVENT_UPDATE_COMPANY = 'update_company';
    public const EVENT_DELETE_COMPANY = 'delete_company';
    public const EVENT_ADD_CUSTOMER = 'add_customer';
    public const EVENT_UPDATE_CUSTOMER = 'update_customer';
    public const EVENT_DELETE_CUSTOMER = 'delete_customer';
    public const EVENT_ADD_TRANSACTION = 'add_transaction';
    public const EVENT_UPDATE_TRANSACTION = 'update_transaction';
    public const EVENT_DELETE_TRANSACTION = 'delete_transaction';
    public const EVENT_ADD_UNSORTED = 'add_unsorted';
    public const EVENT_UPDATE_UNSORTED = 'update_unsorted';
    public const EVENT_ADD_CALL = 'add_call';
    public const EVENT_UPDATE_CALL = 'update_call';

    /**
     * Все события для аналитики
     */
    public const ANALYTICS_EVENTS = [
        self::EVENT_ADD_LEAD,
        self::EVENT_UPDATE_LEAD,
        self::EVENT_DELETE_LEAD,
        self::EVENT_ADD_CONTACT,
        self::EVENT_UPDATE_CONTACT,
        self::EVENT_ADD_UNSORTED,
        self::EVENT_UPDATE_UNSORTED,
        self::EVENT_ADD_CUSTOMER,
        self::EVENT_UPDATE_CUSTOMER,
        self::EVENT_ADD_TRANSACTION,
        self::EVENT_UPDATE_TRANSACTION,
        self::EVENT_DELETE_TRANSACTION,
        self::EVENT_ADD_CALL,
        self::EVENT_UPDATE_CALL,
    ];

    /**
     * @var AmoCRMApiClient
     */
    protected $apiClient;

    /**
     * @var array Обработчики событий
     */
    protected $eventHandlers = [];

    /**
     * @var array Лог обработанных вебхуков
     */
    protected $processedLog = [];

    /**
     * @var callable|null Callback для сохранения данных
     */
    protected $saveCallback;

    /**
     * WebhookHandler constructor.
     *
     * @param AmoCRMApiClient $apiClient
     */
    public function __construct(AmoCRMApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        $this->registerDefaultHandlers();
    }

    /**
     * Зарегистрировать обработчики по умолчанию
     */
    protected function registerDefaultHandlers(): void
    {
        $this->eventHandlers[self::EVENT_ADD_LEAD] = [$this, 'handleLeadEvent'];
        $this->eventHandlers[self::EVENT_UPDATE_LEAD] = [$this, 'handleLeadEvent'];
        $this->eventHandlers[self::EVENT_DELETE_LEAD] = [$this, 'handleLeadEvent'];
        $this->eventHandlers[self::EVENT_ADD_UNSORTED] = [$this, 'handleUnsortedEvent'];
        $this->eventHandlers[self::EVENT_UPDATE_UNSORTED] = [$this, 'handleUnsortedEvent'];
        $this->eventHandlers[self::EVENT_ADD_TRANSACTION] = [$this, 'handleTransactionEvent'];
        $this->eventHandlers[self::EVENT_UPDATE_TRANSACTION] = [$this, 'handleTransactionEvent'];
        $this->eventHandlers[self::EVENT_DELETE_TRANSACTION] = [$this, 'handleTransactionEvent'];
        $this->eventHandlers[self::EVENT_ADD_CALL] = [$this, 'handleCallEvent'];
        $this->eventHandlers[self::EVENT_UPDATE_CALL] = [$this, 'handleCallEvent'];
    }

    /**
     * Обработать входящий webhook запрос
     *
     * @param array $payload Данные из POST запроса
     * @param string $eventType Тип события (из headers или payload)
     * @return array|null Обработанные данные или null
     */
    public function handle(array $payload, string $eventType): ?array
    {
        $result = null;

        if (isset($this->eventHandlers[$eventType])) {
            $handler = $this->eventHandlers[$eventType];
            $result = call_user_func($handler, $payload);
        }

        // Логируем обработку
        $this->processedLog[] = [
            'timestamp' => time(),
            'event_type' => $eventType,
            'result' => $result !== null,
            'payload_keys' => array_keys($payload),
        ];

        // Вызываем callback для сохранения
        if ($result !== null && $this->saveCallback !== null) {
            call_user_func($this->saveCallback, $eventType, $result);
        }

        return $result;
    }

    /**
     * Обработать webhook из POST запроса (сырые данные)
     *
     * @param string $rawBody Тело запроса
     * @param array $headers Заголовки запроса
     * @return array|null
     */
    public function handleFromRequest(string $rawBody, array $headers = []): ?array
    {
        $payload = json_decode($rawBody, true);

        if ($payload === null) {
            return null;
        }

        // Определяем тип события из заголовков или payload
        $eventType = $headers['X-Amojo-Signature'] ?? $payload['type'] ?? 'unknown';

        return $this->handle($payload, $eventType);
    }

    /**
     * Зарегистрировать обработчик для события
     *
     * @param string $eventType Тип события
     * @param callable $handler Обработчик
     * @return self
     */
    public function registerHandler(string $eventType, callable $handler): self
    {
        $this->eventHandlers[$eventType] = $handler;
        return $this;
    }

    /**
     * Установить callback для сохранения данных
     *
     * @param callable $callback function(string $eventType, array $data)
     * @return self
     */
    public function setSaveCallback(callable $callback): self
    {
        $this->saveCallback = $callback;
        return $this;
    }

    /**
     * Обработать событие сделки
     *
     * @param array $payload
     * @return array|null
     */
    protected function handleLeadEvent(array $payload): ?array
    {
        $leads = $payload['leads'] ?? [];
        if (empty($leads) || !isset($leads['add'][$_ = 0]) && !isset($leads['update'][$_ = 0]) && !isset($leads['delete'][$_ = 0])) {
            return null;
        }

        // Обработка изменений статуса
        if (isset($leads['status'])) {
            $statusChange = $leads['status'];
            $historyModel = LeadStatusHistoryModel::fromWebhook([
                'leads' => $statusChange,
                'account_id' => $payload['account_id'] ?? null,
            ]);

            return [
                'type' => 'status_history',
                'data' => $historyModel->toArray(),
            ];
        }

        // Обработка добавления/обновления/удаления сделки
        $items = $leads['add'] ?? $leads['update'] ?? $leads['delete'] ?? [];
        $results = [];

        foreach ($items as $leadData) {
            $factModel = LeadFactModel::fromArray([
                'lead_id' => $leadData['id'] ?? null,
                'name' => $leadData['name'] ?? null,
                'pipeline_id' => $leadData['pipeline_id'] ?? null,
                'status_id' => $leadData['status_id'] ?? null,
                'price' => $leadData['price'] ?? null,
                'responsible_user_id' => $leadData['responsible_user_id'] ?? null,
                'is_deleted' => isset($leads['delete']),
            ]);

            $results[] = $factModel->toArray();
        }

        return [
            'type' => 'lead',
            'data' => $results,
        ];
    }

    /**
     * Обработать событие неразобранного
     *
     * @param array $payload
     * @return array|null
     */
    protected function handleUnsortedEvent(array $payload): ?array
    {
        $factModel = FirstTouchFactModel::fromWebhook($payload);

        return [
            'type' => 'first_touch',
            'data' => $factModel->toArray(),
        ];
    }

    /**
     * Обработать событие транзакции
     *
     * @param array $payload
     * @return array|null
     */
    protected function handleTransactionEvent(array $payload): ?array
    {
        $factModel = TransactionFactModel::fromWebhook($payload);

        return [
            'type' => 'transaction',
            'data' => $factModel->toArray(),
        ];
    }

    /**
     * Обработать событие звонка
     *
     * @param array $payload
     * @return array|null
     */
    protected function handleCallEvent(array $payload): ?array
    {
        $factModel = CallFactModel::fromWebhook($payload);

        return [
            'type' => 'call',
            'data' => $factModel->toArray(),
        ];
    }

    /**
     * Получить лог обработки
     *
     * @return array
     */
    public function getProcessedLog(): array
    {
        return $this->processedLog;
    }

    /**
     * Очистить лог
     *
     * @return self
     */
    public function clearLog(): self
    {
        $this->processedLog = [];
        return $this;
    }

    /**
     * Валидировать webhook payload
     *
     * @param array $payload
     * @return bool
     */
    public static function validatePayload(array $payload): bool
    {
        // Проверяем наличие обязательных полей
        return isset($payload['account_id']) && isset($payload['contact']);
    }

    /**
     * Получить список поддерживаемых событий
     *
     * @return array
     */
    public static function getSupportedEvents(): array
    {
        return self::ANALYTICS_EVENTS;
    }
}