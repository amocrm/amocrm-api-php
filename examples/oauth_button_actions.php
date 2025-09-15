<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use AmoCRM\Client\AmoCRMApiClient;

$clientId = 'client_id';
$clientSecret = 'client_secret';

// Здесь нужны работающие redirect_uri и client_secret
$redirectUri = 'https://example.com/redirect';
$secretsUri = 'https://example.com/secrets';

$apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);

echo "=== Генерация обычной OAuth кнопки ===\n";
$buttonByClientId = $apiClient->getOAuthClient()->getOAuthButton(
    [
        'title' => 'title_tmedvedevv',
        'compact' => false,
        'is_kommo' => true,
        'class_name' => 'classNameTmedvedevv',
        'color' => 'red',
        'mode' => 'popup',
        'error_callback' => 'handleOauthError'
    ]
);

var_dump($buttonByClientId);

echo "\n=== Генерация кнопки с метаданными, необходимые для создания внешней интеграции ===\n";

// Генерируем кнопку с метаданными для внешней интеграции
// client_id из API клиента будет проигнорирован т.к создается новая интеграция в аккаунте
// После установки приходит 2 хука.

$buttonMetadata = $apiClient->getOAuthClient()->getOAuthButton([
    'title' => 'title_tmedvedevv',
    'compact' => true,
    'is_kommo' => false,
    'class_name' => 'classNameTmedvedevv',
    'color' => 'red',
    'mode' => 'popup',
    'error_callback' => 'handleOauthError',
    'scopes' => ['crm'],
    'is_metadata' => true, // Указываем, поскольку нужна кнопка с передачей метаданных. По умолчанию false
    'secrets_uri' => $secretsUri // url куда будут отправлены данные по интеграции
]);

var_dump($buttonMetadata);
