<?php

/**
 * Пример полного цикла выгрузки данных из amoCRM в DWH-хранилище.
 *
 * Перед запуском:
 * 1. Создать integration в amoCRM и заполнить .env файл (CLIENT_ID, CLIENT_SECRET, CLIENT_REDIRECT_URI)
 * 2. Настроить подключение к MySQL в переменных DB_DSN, DB_USER, DB_PASS
 * 3. Запустить миграции: пройти по файлам migrations/*.sql и выполнить их в БД
 * 4. Получить access_token: открыть examples/get_token.php в браузере
 *
 * Использование:
 *   php examples/dwh_full_sync.php
 */

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Dwh\DwhDbAdapter;
use AmoCRM\Dwh\DwhSyncOrchestrator;
use League\OAuth2\Client\Token\AccessToken;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';

// --- 1. Инициализация API-клиента ---
$apiClient = new AmoCRMApiClient(
    $_ENV['CLIENT_ID'],
    $_ENV['CLIENT_SECRET'],
    $_ENV['CLIENT_REDIRECT_URI']
);

$accessToken = getToken();

$apiClient->setAccessToken($accessToken)
    ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
    ->onAccessTokenRefresh(
        function (AccessToken $accessToken, string $baseDomain) {
            saveToken([
                'accessToken' => $accessToken->getToken(),
                'refreshToken' => $accessToken->getRefreshToken(),
                'expires' => $accessToken->getExpires(),
                'baseDomain' => $baseDomain,
            ]);
        }
    );

// --- 2. Инициализация DWH (подключение к MySQL) ---
$dsn = $_ENV['DB_DSN'] ?? 'mysql:host=127.0.0.1;dbname=amocrm_dwh;charset=utf8mb4';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';

$pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$dbAdapter = new DwhDbAdapter($pdo);

// ID аккаунта (можно получить из токена или задать явно)
$accountId = 1;

// --- 3. Полная синхронизация ---
$orchestrator = new DwhSyncOrchestrator($apiClient, $dbAdapter, $accountId);

echo "=== Starting full DWH sync ===\n";
$startTime = microtime(true);

$report = $orchestrator->fullSync(['limit' => 250]);

$duration = round(microtime(true) - $startTime, 2);

echo "=== Sync complete in {$duration}s ===\n\n";
echo "Results:\n";
foreach ($report as $entity => $count) {
    echo "  {$entity}: {$count} records\n";
}

// --- 4. Инкрементальная синхронизация (опционально) ---
// $since = new DateTime('2024-01-01 00:00:00');
// $report = $orchestrator->incrementalSync($since);
