<?php

/**
 * Пример использования WebhookHandler для обработки вебхуков
 */

require_once __DIR__ . '/../bootstrap.php';

use AmoCRM\Client\AmoCRMClientFactory;
use AmoCRM\Analytics\Services\WebhookHandler;
use AmoCRM\Analytics\Services\WebhookSubscriptionService;

// Инициализация API-клиента
$accessToken = getToken();

$apiClient = AmoCRMClientFactory::createWithAccessToken(
    $accessToken,
    $accessToken->getValues()['baseDomain']
);

// Создаём обработчик вебхуков
$webhookHandler = new WebhookHandler($apiClient);

// Callback для сохранения данных в БД
$webhookHandler->setSaveCallback(function (string $eventType, array $data) {
    // Здесь должна быть логика сохранения в базу данных
    echo "[" . date('Y-m-d H:i:s') . "] Saving {$eventType}: " . json_encode($data) . PHP_EOL;
    
    // Пример сохранения в файл для отладки
    $logFile = __DIR__ . '/webhook_log.json';
    $log = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];
    $log[] = [
        'timestamp' => time(),
        'event_type' => $eventType,
        'data' => $data,
    ];
    file_put_contents($logFile, json_encode($log, JSON_PRETTY_PRINT));
});

echo "=== Webhook Handler Demo ===" . PHP_EOL . PHP_EOL;

// Демонстрация обработки тестовых вебхуков
$testPayloads = [
    // Тестовый webhook: добавление сделки
    [
        'type' => 'add_lead',
        'account_id' => 12345,
        'leads' => [
            'add' => [
                [
                    'id' => 99999,
                    'name' => 'Test Lead from Webhook',
                    'pipeline_id' => 123,
                    'status_id' => 12345,
                    'price' => 50000,
                    'responsible_user_id' => 123,
                    'created_at' => time(),
                ]
            ]
        ]
    ],
    
    // Тестовый webhook: изменение статуса
    [
        'type' => 'update_lead',
        'account_id' => 12345,
        'leads' => [
            'status' => [
                'from' => 12345,
                'to' => 67890,
            ],
            'id' => 99999,
        ]
    ],
    
    // Тестовый webhook: добавление транзакции
    [
        'type' => 'add_transaction',
        'account_id' => 12345,
        'id' => 88888,
        'customer_id' => 77777,
        'price' => 15000,
        'completed_at' => time(),
    ],
    
    // Тестовый webhook: звонок
    [
        'type' => 'add_call',
        'account_id' => 12345,
        'uniq' => 'call_' . time(),
        'duration' => 180,
        'phone' => '+79001234567',
        'direction' => 'inbound',
        'call_status' => 1,
        'responsible_user_id' => 123,
    ],
];

echo "Processing test webhooks..." . PHP_EOL . PHP_EOL;

foreach ($testPayloads as $payload) {
    $eventType = $payload['type'] ?? 'unknown';
    
    echo "--- Processing {$eventType} ---" . PHP_EOL;
    
    try {
        $result = $webhookHandler->handle($payload, $eventType);
        
        if ($result !== null) {
            echo "Result: " . json_encode($result, JSON_PRETTY_PRINT) . PHP_EOL;
        } else {
            echo "No result (unhandled event type)" . PHP_EOL;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . PHP_EOL;
    }
    
    echo PHP_EOL;
}

// Показываем лог обработки
echo "=== Processing Log ===" . PHP_EOL;
$log = $webhookHandler->getProcessedLog();
foreach ($log as $entry) {
    echo "[" . date('Y-m-d H:i:s', $entry['timestamp']) . "] {$entry['event_type']} - " . 
         ($entry['result'] ? 'OK' : 'FAILED') . PHP_EOL;
}

echo PHP_EOL . "=== Webhook Subscription Demo ===" . PHP_EOL . PHP_EOL;

// Демонстрация управления подписками
$subscriptionService = new WebhookSubscriptionService($apiClient, 'https://your-app.com/webhook');

try {
    // Проверяем текущую подписку
    $current = $subscriptionService->getCurrentSubscription();
    
    if ($current !== null) {
        echo "Current webhook URL: {$current['destination']}" . PHP_EOL;
        echo "Subscribed events: " . implode(', ', $current['settings']) . PHP_EOL;
    } else {
        echo "No active webhook subscription found." . PHP_EOL;
        
        // Подписываемся на основные события аналитики
        echo PHP_EOL . "Would subscribe to main analytics events:" . PHP_EOL;
        foreach (WebhookSubscriptionService::MAIN_ANALYTICS_EVENTS as $event) {
            echo "  - {$event}" . PHP_EOL;
        }
    }
    
    // Получаем статистику подписок
    $stats = $subscriptionService->getSubscriptionStats();
    echo PHP_EOL . "Webhook subscription stats:" . PHP_EOL;
    echo "  Total webhooks: {$stats['total_webhooks']}" . PHP_EOL;
    echo "  Analytics webhooks: {$stats['analytics_webhooks']}" . PHP_EOL;
    
} catch (AmoCRMApiException $e) {
    printError($e);
}

echo PHP_EOL . "Demo complete!" . PHP_EOL;