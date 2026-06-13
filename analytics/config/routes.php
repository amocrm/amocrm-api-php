<?php

declare(strict_types=1);

/**
 * Fusio route definitions for Analytics API
 * 
 * Copy this file to your Fusio configuration or import via Fusio CLI
 */

return [
    // Webhook endpoint
    [
        'name' => 'Webhook.amocrm',
        'path' => '/webhook/amocrm',
        'controller' => \Analytics\Api\WebhookAction::class,
        'method' => 'POST',
        'authentication' => false,
    ],

    // Analytics API endpoints
    [
        'name' => 'Analytics.funnel',
        'path' => '/api/analytics/funnel',
        'controller' => \Analytics\Api\FunnelAction::class,
        'method' => 'GET',
        'authentication' => true,
        'scopes' => ['analytics:read'],
    ],
    [
        'name' => 'Analytics.attribution',
        'path' => '/api/analytics/attribution',
        'controller' => \Analytics\Api\AttributionAction::class,
        'method' => 'GET',
        'authentication' => true,
        'scopes' => ['analytics:read'],
    ],
    [
        'name' => 'Analytics.ltv',
        'path' => '/api/analytics/ltv',
        'controller' => \Analytics\Api\LTVAction::class,
        'method' => 'GET',
        'authentication' => true,
        'scopes' => ['analytics:read'],
    ],
    [
        'name' => 'Analytics.overview',
        'path' => '/api/analytics/overview',
        'controller' => \Analytics\Api\OverviewAction::class,
        'method' => 'GET',
        'authentication' => true,
        'scopes' => ['analytics:read'],
    ],
];