<?php

/**
 * Пример использования Analytics Services для выгрузки сделок
 */

require_once __DIR__ . '/../bootstrap.php';

use AmoCRM\Client\AmoCRMClientFactory;
use AmoCRM\Analytics\Services\LeadExportService;
use AmoCRM\Analytics\Services\EventExportService;
use AmoCRM\Analytics\Services\UnsortedExportService;
use AmoCRM\Analytics\Models\LeadFactModel;
use AmoCRM\Models\LeadModel;

// Инициализация API-клиента
$accessToken = getToken();

$apiClient = AmoCRMClientFactory::createWithAccessToken(
    $accessToken,
    $accessToken->getValues()['baseDomain'],
    function ($token, $domain) {
        saveToken([
            'accessToken' => $token->getToken(),
            'refreshToken' => $token->getRefreshToken(),
            'expires' => $token->getExpires(),
            'baseDomain' => $domain,
        ]);
    }
);

echo "=== Analytics: Export Leads ===" . PHP_EOL . PHP_EOL;

// Создаём сервис экспорта сделок
$leadExportService = new LeadExportService($apiClient);

// Получаем сделки за последние 30 дней
$startTimestamp = strtotime('-30 days');
$endTimestamp = time();

// Фильтр по воронке (опционально)
// $filter = $leadExportService->createFilter($startTimestamp, $endTimestamp, [$pipelineId]);
$filter = $leadExportService->createFilter($startTimestamp, $endTimestamp);

try {
    // Получаем все сделки
    echo "Fetching leads from " . date('Y-m-d H:i:s', $startTimestamp) . " to " . date('Y-m-d H:i:s', $endTimestamp) . PHP_EOL;
    
    $leadsCollection = $leadExportService->getAllLeads($filter, [
        LeadModel::CONTACTS,
        LeadModel::COMPANY,
        LeadModel::SOURCE,
    ]);
    
    echo "Total leads: " . $leadsCollection->count() . PHP_EOL . PHP_EOL;
    
    // Обрабатываем каждую сделку
    $analyticsData = [];
    foreach ($leadsCollection as $lead) {
        /** @var LeadModel $lead */
        $factModel = LeadFactModel::fromApiModel($lead);
        $analyticsData[] = $factModel->toArray();
    }
    
    // Выводим первые 5 для примера
    echo "Sample lead data (first 5):" . PHP_EOL;
    for ($i = 0; $i < min(5, count($analyticsData)); $i++) {
        $lead = $analyticsData[$i];
        echo "  - ID: {$lead['lead_id']}, Name: {$lead['name']}, Price: {$lead['price']}, Status: {$lead['status_id']}" . PHP_EOL;
    }
    
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

echo PHP_EOL . "=== Event Export ===" . PHP_EOL . PHP_EOL;

// Получаем историю изменений статусов
$eventExportService = new EventExportService($apiClient);

try {
    $statusHistory = $eventExportService->getLeadStatusHistory($startTimestamp, $endTimestamp);
    
    echo "Status transitions found: " . count($statusHistory) . PHP_EOL;
    
    // Группируем по сделкам
    $leadTransitions = [];
    foreach ($statusHistory as $transition) {
        $leadId = $transition['lead_id'];
        if (!isset($leadTransitions[$leadId])) {
            $leadTransitions[$leadId] = [];
        }
        $leadTransitions[$leadId][] = $transition;
    }
    
    echo "Leads with status changes: " . count($leadTransitions) . PHP_EOL;
    
} catch (AmoCRMApiException $e) {
    printError($e);
}

echo PHP_EOL . "=== Unsorted Export ===" . PHP_EOL . PHP_EOL;

// Получаем неразобранное (первые касания)
$unsortedExportService = new UnsortedExportService($apiClient);

try {
    $firstTouches = $unsortedExportService->getUnsortedBetween($startTimestamp, $endTimestamp);
    
    echo "First touches (unsorted) found: " . count($firstTouches) . PHP_EOL;
    
    // Статистика по категориям
    $categoryStats = $unsortedExportService->getCategoryStats($startTimestamp, $endTimestamp);
    
    echo "Category breakdown:" . PHP_EOL;
    foreach ($categoryStats['by_category'] as $category => $count) {
        echo "  - {$category}: {$count}" . PHP_EOL;
    }
    
} catch (AmoCRMApiException $e) {
    printError($e);
}

echo PHP_EOL . "=== Lead Stats ===" . PHP_EOL . PHP_EOL;

// Получаем статистику по сделкам
try {
    $stats = $leadExportService->getLeadsStats($startTimestamp, $endTimestamp);
    
    echo "Total leads: {$stats['total_count']}" . PHP_EOL;
    echo "Won: {$stats['won_count']} ({$stats['win_rate']}%)" . PHP_EOL;
    echo "Lost: {$stats['lost_count']}" . PHP_EOL;
    echo "Total revenue: {$stats['total_price']}" . PHP_EOL;
    echo "Average price: {$stats['average_price']}" . PHP_EOL;
    
} catch (AmoCRMApiException $e) {
    printError($e);
}

echo PHP_EOL . "Export complete!" . PHP_EOL;