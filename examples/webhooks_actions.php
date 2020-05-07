<?php

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\WebhookModel;
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

//Подпишемся на вебхук добавления сделки
$webhook = new WebhookModel();
$webhook->setDestination('https://example.com/')
    ->setSettings([
        'add_lead'
    ]);

try {
    $webhook = $apiClient->webhooks()->subscribe($webhook);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Отпишемся от хука
try {
    if ($apiClient->webhooks()->unsubscribe($webhook)) {
        echo "Успешно";
    } else {
        //Сюда не должны попасть никогда, так как в случае ошибки будет эксепшн
        echo "Не успешно";
    }
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
