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

$contextUserId = 123;
$apiClient->setContextUserId($contextUserId);

//Получим свойства аккаунта и сравним юезра
try {
    $account = $apiClient->account()->getCurrent();

    echo 'Текущий юзер, тот кого вы передали? - ' . ($account->getCurrentUserId() === $contextUserId ? 'да' : 'нет');
} catch (AmoCRMApiException $e) {
    printError($e);
}
