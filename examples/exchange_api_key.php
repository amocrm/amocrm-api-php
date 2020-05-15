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

//Сделаем запрос с апи ключом, чтобы получить код авторизации
//Код авторизации отправляется в виде вебхука на указанный redirect_uri c GET-параметром from_exchange=1
$login = 'example@example.com';
$apiKey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
try {
    $apiClient->getOAuthClient()->exchangeApiKey($login, $apiKey);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
