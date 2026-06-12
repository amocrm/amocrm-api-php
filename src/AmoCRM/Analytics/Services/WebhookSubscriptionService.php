<?php

namespace AmoCRM\Analytics\Services;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\WebhooksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\WebhooksFilter;
use AmoCRM\Models\WebhookModel;

/**
 * Class WebhookSubscriptionService
 *
 * Сервис для управления подписками на вебхуки amoCRM.
 * Позволяет подписываться и отписываться от событий для аналитики.
 *
 * @package AmoCRM\Analytics\Services
 */
class WebhookSubscriptionService
{
    /**
     * События для сквозной аналитики
     */
    public const ANALYTICS_EVENTS = [
        // Сделки
        'add_lead',
        'update_lead',
        'delete_lead',
        // Контакты
        'add_contact',
        'update_contact',
        'delete_contact',
        // Компании
        'add_company',
        'update_company',
        'delete_company',
        // Покупатели
        'add_customer',
        'update_customer',
        'delete_customer',
        // Транзакции
        'add_transaction',
        'update_transaction',
        'delete_transaction',
        // Неразобранное
        'add_unsorted',
        'update_unsorted',
        // Звонки
        'add_call',
        'update_call',
    ];

    /**
     * Основные события для аналитики (без удалений)
     */
    public const MAIN_ANALYTICS_EVENTS = [
        'add_lead',
        'update_lead',
        'add_contact',
        'update_contact',
        'add_unsorted',
        'update_unsorted',
        'add_customer',
        'update_customer',
        'add_transaction',
        'update_transaction',
        'add_call',
    ];

    /**
     * @var AmoCRMApiClient
     */
    protected $apiClient;

    /**
     * @var string|null Текущий URL подписки
     */
    protected $webhookUrl;

    /**
     * WebhookSubscriptionService constructor.
     *
     * @param AmoCRMApiClient $apiClient
     * @param string|null $webhookUrl URL для вебхуков
     */
    public function __construct(AmoCRMApiClient $apiClient, ?string $webhookUrl = null)
    {
        $this->apiClient = $apiClient;
        $this->webhookUrl = $webhookUrl;
    }

    /**
     * Установить URL для вебхуков
     *
     * @param string $url
     * @return self
     */
    public function setWebhookUrl(string $url): self
    {
        $this->webhookUrl = $url;
        return $this;
    }

    /**
     * Подписаться на все события аналитики
     *
     * @param string|null $destination URL для получения вебхуков
     * @param array|null $events Список событий (null = все аналитикс события)
     * @return WebhookModel|null
     * @throws AmoCRMApiException
     */
    public function subscribe(?string $destination = null, ?array $events = null): ?WebhookModel
    {
        $destination = $destination ?? $this->webhookUrl;

        if ($destination === null) {
            throw new \InvalidArgumentException('Webhook destination URL is required');
        }

        $events = $events ?? self::ANALYTICS_EVENTS;

        $webhook = new WebhookModel();
        $webhook->setDestination($destination)
            ->setSettings($events);

        return $this->apiClient->webhooks()->subscribe($webhook);
    }

    /**
     * Подписаться на основные события аналитики
     *
     * @param string|null $destination URL для получения вебхуков
     * @return WebhookModel|null
     * @throws AmoCRMApiException
     */
    public function subscribeMain(?string $destination = null): ?WebhookModel
    {
        return $this->subscribe($destination, self::MAIN_ANALYTICS_EVENTS);
    }

    /**
     * Отписаться от вебхуков
     *
     * @param WebhookModel $webhook Модель вебхука для отписки
     * @return bool
     * @throws AmoCRMApiException
     */
    public function unsubscribe(WebhookModel $webhook): bool
    {
        return $this->apiClient->webhooks()->unsubscribe($webhook);
    }

    /**
     * Отписаться от всех вебхуков аналитики
     *
     * @param string|null $destination URL вебхука для отписки
     * @return int Количество удалённых подписок
     * @throws AmoCRMApiException
     */
    public function unsubscribeAll(?string $destination = null): int
    {
        $deletedCount = 0;
        $webhooks = $this->getActiveWebhooks();

        foreach ($webhooks as $webhook) {
            /** @var WebhookModel $webhook */
            $webhookDestination = $webhook->getDestination();

            // Если указан destination, удаляем только совпадающие
            if ($destination !== null && $webhookDestination !== $destination) {
                continue;
            }

            // Проверяем, содержит ли вебхук события аналитики
            $settings = $webhook->getSettings() ?? [];
            $hasAnalyticsEvents = array_intersect($settings, self::ANALYTICS_EVENTS);

            if (!empty($hasAnalyticsEvents)) {
                if ($this->unsubscribe($webhook)) {
                    $deletedCount++;
                }
            }
        }

        return $deletedCount;
    }

    /**
     * Получить все активные подписки
     *
     * @param WebhooksFilter|null $filter
     * @return WebhooksCollection
     * @throws AmoCRMApiException
     */
    public function getActiveWebhooks(?WebhooksFilter $filter = null): WebhooksCollection
    {
        if ($filter === null) {
            $filter = new WebhooksFilter();
        }

        return $this->apiClient->webhooks()->get($filter);
    }

    /**
     * Проверить, активна ли подписка для URL
     *
     * @param string $destination URL для проверки
     * @return bool
     * @throws AmoCRMApiException
     */
    public function isSubscribed(string $destination): bool
    {
        $filter = new WebhooksFilter();
        $filter->setDestination($destination);

        $webhooks = $this->getActiveWebhooks($filter);

        return !$webhooks->isEmpty();
    }

    /**
     * Получить информацию о текущей подписке аналитики
     *
     * @return array|null
     * @throws AmoCRMApiException
     */
    public function getCurrentSubscription(): ?array
    {
        if ($this->webhookUrl === null) {
            return null;
        }

        $filter = new WebhooksFilter();
        $filter->setDestination($this->webhookUrl);

        $webhooks = $this->getActiveWebhooks($filter);

        if ($webhooks->isEmpty()) {
            return null;
        }

        /** @var WebhookModel $webhook */
        $webhook = $webhooks->first();

        return [
            'destination' => $webhook->getDestination(),
            'settings' => $webhook->getSettings(),
            'id' => method_exists($webhook, 'getId') ? $webhook->getId() : null,
        ];
    }

    /**
     * Обновить подписку на новые события
     *
     * @param array $newEvents Новый список событий
     * @param string|null $destination URL вебхука
     * @return WebhookModel|null
     * @throws AmoCRMApiException
     */
    public function updateSubscription(array $newEvents, ?string $destination = null): ?WebhookModel
    {
        $destination = $destination ?? $this->webhookUrl;

        if ($destination === null) {
            throw new \InvalidArgumentException('Webhook destination URL is required');
        }

        // Отписываемся от старой подписки
        $this->unsubscribeAll($destination);

        // Подписываемся на новые события
        return $this->subscribe($destination, $newEvents);
    }

    /**
     * Добавить события к текущей подписке
     *
     * @param array $additionalEvents Дополнительные события
     * @param string|null $destination URL вебхука
     * @return WebhookModel|null
     * @throws AmoCRMApiException
     */
    public function addEvents(array $additionalEvents, ?string $destination = null): ?WebhookModel
    {
        $current = $this->getCurrentSubscription();

        $currentEvents = $current['settings'] ?? [];
        $newEvents = array_unique(array_merge($currentEvents, $additionalEvents));

        return $this->updateSubscription($newEvents, $destination);
    }

    /**
     * Удалить события из текущей подписки
     *
     * @param array $eventsToRemove События для удаления
     * @param string|null $destination URL вебхука
     * @return WebhookModel|null
     * @throws AmoCRMApiException
     */
    public function removeEvents(array $eventsToRemove, ?string $destination = null): ?WebhookModel
    {
        $current = $this->getCurrentSubscription();

        $currentEvents = $current['settings'] ?? [];
        $newEvents = array_values(array_diff($currentEvents, $eventsToRemove));

        if (empty($newEvents)) {
            // Удаляем подписку полностью
            $this->unsubscribeAll($destination ?? $this->webhookUrl);
            return null;
        }

        return $this->updateSubscription($newEvents, $destination);
    }

    /**
     * Получить статистику подписок
     *
     * @return array
     * @throws AmoCRMApiException
     */
    public function getSubscriptionStats(): array
    {
        $webhooks = $this->getActiveWebhooks();

        $stats = [
            'total_webhooks' => 0,
            'analytics_webhooks' => 0,
            'by_events' => [],
        ];

        foreach ($webhooks as $webhook) {
            /** @var WebhookModel $webhook */
            $stats['total_webhooks']++;

            $settings = $webhook->getSettings() ?? [];
            $hasAnalytics = false;

            foreach ($settings as $event) {
                if (in_array($event, self::ANALYTICS_EVENTS, true)) {
                    $hasAnalytics = true;
                    if (!isset($stats['by_events'][$event])) {
                        $stats['by_events'][$event] = 0;
                    }
                    $stats['by_events'][$event]++;
                }
            }

            if ($hasAnalytics) {
                $stats['analytics_webhooks']++;
            }
        }

        return $stats;
    }
}