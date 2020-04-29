<?php

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\AccountModel;
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


//Получим свойства аккаунта со всеми доступными свойствами
try {
    $account = $apiClient->account()->getCurrent(AccountModel::getAvailableWith());
} catch (AmoCRMApiException $e) {
    printError($e);
}

var_dump($account->toArray());
