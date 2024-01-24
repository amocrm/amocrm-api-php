<?php

declare(strict_types=1);

use AmoCRM\AmoCRM\Models\Sources\WebsiteButtonModel;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\PagesFilter;
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


// Получим 10 кнопок на сайт(CrmPlugin)
try {
    $buttons = $apiClient
        ->websiteButtons()
        ->get((new PagesFilter())->setLimit(10));
    var_dump($buttons->toArray());
} catch (AmoCRMApiException $e) {
    printError($e);
}

// Получим кнопку по id источника с кодом для вставки на сайт
try {
    $button = $apiClient
        ->websiteButtons()
        ->getOne(13236545, WebsiteButtonModel::getAvailableWith());
    var_dump($button->toArray());
} catch (AmoCRMApiException $e) {
    printError($e);
}
