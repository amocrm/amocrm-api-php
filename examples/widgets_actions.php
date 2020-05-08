<?php

use AmoCRM\Exceptions\AmoCRMApiException;
use League\OAuth2\Client\Token\AccessTokenInterface;

include_once __DIR__ . '/bootstrap.php';

$accessToken = getToken();

$apiClient->setAccessToken($accessToken)
    ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
    ->onAccessTokenRefresh(
        function (AccessTokenInterface $accessToken, string $baseDomain) {
            saveToken(
                [
                    'accessToken' => $accessToken->getToken(),
                    'refreshToken' => $accessToken->getRefreshToken(),
                    'expires' => $accessToken->getExpires(),
                    'baseDomain' => $baseDomain,
                ]
            );
        }
    );

$widgetsService = $apiClient->widgets();
//Получим виджет
try {
    $widget = $widgetsService->getOne('amo_asterisk');
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

$widget->setSettings([
    'login' => 'example',
    'password' => 'SuchAnEasyPassword',
    'script_path' => 'https://example.com/amocrm_asterisk/',
    'phones' => [
        504141 => 459 //id пользователя => добавочный номер
    ],
]);

//Установим виджет
try {
    $widget = $widgetsService->install($widget);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Отключим виджет
try {
    $widget = $widgetsService->uninstall($widget);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
