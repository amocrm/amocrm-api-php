<?php

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\ShortLinks\ShortLinkModel;
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

//Сервис коротких ссылок
$shortLinksService = $apiClient->shortLinks();

//Создадим ссылку
$shortLink = new ShortLinkModel();
$shortLink
    ->setUrl('https://example.com')
    ->setEntityType(EntityTypesInterface::CONTACTS)
    ->setEntityId(11070881);

try {
    $shortLink = $shortLinksService->addOne($shortLink);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

var_dump($shortLink->toArray());
