<?php

/**
 * Пример использования FunnelAnalyticsService для аналитики воронки продаж
 */

require_once __DIR__ . '/../bootstrap.php';

use AmoCRM\Client\AmoCRMClientFactory;
use AmoCRM\Analytics\Services\FunnelAnalyticsService;

// Инициализация API-клиента
$accessToken = getToken();

$apiClient = AmoCRMClientFactory::createWithAccessToken(
    $accessToken,
    $accessToken->getValues()['baseDomain']
);

// Создаём сервис аналитики воронки
$funnelService = new FunnelAnalyticsService($apiClient);

echo "=== Funnel Analytics Demo ===" . PHP_EOL . PHP_EOL;

// Получаем структуру воронок
echo "--- Pipelines Structure ---" . PHP_EOL;
$pipelines = $funnelService->getPipelinesStructure();

foreach ($pipelines as $pipelineId => $pipeline) {
    echo "Pipeline #{$pipelineId}: {$pipeline['name']}" . PHP_EOL;
    echo "  Main: " . ($pipeline['is_main'] ? 'Yes' : 'No') . PHP_EOL;
    echo "  Statuses:" . PHP_EOL;
    
    foreach ($pipeline['statuses'] as $statusId => $status) {
        echo "    - {$statusId}: {$status['name']} (sort: {$status['sort']})" . PHP_EOL;
    }
    echo PHP_EOL;
}

// Период для анализа: последние 30 дней
$startTimestamp = strtotime('-30 days');
$endTimestamp = time();

// Выбираем первую воронку для демонстрации
$firstPipelineId = !empty($pipelines) ? array_key_first($pipelines) : null;

if ($firstPipelineId !== null) {
    echo "--- Conversion Rate for Pipeline #{$firstPipelineId} ---" . PHP_EOL;
    
    try {
        $conversion = $funnelService->calculateConversionRate($firstPipelineId, $startTimestamp, $endTimestamp);
        
        if (!empty($conversion)) {
            echo "Pipeline: {$conversion['pipeline_name']}" . PHP_EOL;
            echo "Total leads: {$conversion['total_leads']}" . PHP_EOL;
            echo "Won leads: {$conversion['won_leads']}" . PHP_EOL;
            echo "Conversion rate: " . number_format($conversion['conversion_rate'], 2) . "%" . PHP_EOL;
            
            echo PHP_EOL . "By status:" . PHP_EOL;
            foreach ($conversion['by_status'] as $statusId => $data) {
                $wonPct = $data['count'] > 0 ? ($data['won_count'] / $data['count']) * 100 : 0;
                echo "  {$data['name']}: {$data['count']} leads, {$data['won_count']} won (" . 
                     number_format($wonPct, 1) . "%)" . PHP_EOL;
            }
        }
    } catch (Exception $e) {
        echo "Error calculating conversion: " . $e->getMessage() . PHP_EOL;
    }
    
    echo PHP_EOL . "--- Average Time on Stage ---" . PHP_EOL;
    
    try {
        $avgTime = $funnelService->calculateAverageTimeOnStage($firstPipelineId, $startTimestamp, $endTimestamp);
        
        foreach ($avgTime as $statusId => $data) {
            $hours = number_format($data['avg_hours'], 1);
            echo "  {$data['name']}: avg {$hours} hours ({$data['count']} transitions)" . PHP_EOL;
        }
    } catch (Exception $e) {
        echo "Error calculating avg time: " . $e->getMessage() . PHP_EOL;
    }
    
    echo PHP_EOL . "--- Average Deal Size ---" . PHP_EOL;
    
    try {
        $dealSize = $funnelService->calculateAverageDealSize($firstPipelineId, $startTimestamp, $endTimestamp);
        
        if (!empty($dealSize)) {
            echo "Total revenue: {$dealSize['total_revenue']}" . PHP_EOL;
            echo "Won deals: {$dealSize['won_count']}" . PHP_EOL;
            echo "Average check: " . number_format($dealSize['average_check'], 2) . PHP_EOL;
            echo "Median check: " . number_format($dealSize['median_check'], 2) . PHP_EOL;
            echo "Min: {$dealSize['min_check']}, Max: {$dealSize['max_check']}" . PHP_EOL;
        }
    } catch (Exception $e) {
        echo "Error calculating deal size: " . $e->getMessage() . PHP_EOL;
    }
}

echo PHP_EOL . "--- Full Funnel Stats (all pipelines) ---" . PHP_EOL;

try {
    $funnelStats = $funnelService->calculateFunnelStats($startTimestamp, $endTimestamp);
    
    foreach ($funnelStats as $pipelineId => $stats) {
        echo PHP_EOL . "Pipeline: {$stats['pipeline_name']}" . PHP_EOL;
        echo "  Total: {$stats['total_count']} leads" . PHP_EOL;
        echo "  Won: {$stats['won_count']} ({$stats['won_price']} revenue)" . PHP_EOL;
        echo "  Lost: {$stats['lost_count']}" . PHP_EOL;
        
        echo "  Status breakdown:" . PHP_EOL;
        foreach ($stats['statuses'] as $statusId => $statusStats) {
            $conv = number_format($statusStats['conversion_from_prev'], 1);
            echo "    {$statusStats['name']}: {$statusStats['count']} leads, " .
                 "price: {$statusStats['price']}, conv: {$conv}%" . PHP_EOL;
        }
    }
} catch (Exception $e) {
    echo "Error calculating funnel stats: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "--- Top Deals ---" . PHP_EOL;

try {
    $topDeals = $funnelService->getTopDeals($startTimestamp, $endTimestamp, 5, true);
    
    $rank = 1;
    foreach ($topDeals as $deal) {
        $won = $deal['is_won'] ? ' (WON)' : '';
        echo "  #{$rank}: ID {$deal['id']} - {$deal['name']} - {$deal['price']}{$won}" . PHP_EOL;
        $rank++;
    }
} catch (Exception $e) {
    echo "Error getting top deals: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "Demo complete!" . PHP_EOL;