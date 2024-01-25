<?php

declare(strict_types=1);

use AmoCRM\Models\Sources\WebsiteButtonCreateRequestModel;
use AmoCRM\Models\Sources\WebsiteButtonModel;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\PagesFilter;
use AmoCRM\Models\Sources\WebsiteButtonUpdateRequestModel;
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

// Создадим модель кнопки на сайт(CrmPlugin) и укажем воронку и доверенные домены
$buttonModel = new WebsiteButtonCreateRequestModel(
    7072170,
    [
        'amocrm.ru'
    ]
);

try {
    // Добавим данную кнопку в аккаунт как источник сделок
    $source = $apiClient
        ->websiteButtons()
        ->createAsync($buttonModel);
    var_dump($source->toArray());
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

// Добавим еще один доверенный адрес для нашей кнопки
$buttonModel  = new WebsiteButtonUpdateRequestModel(
    [
        'kommo.com'
    ],
    $source->getSourceId()
);
try {
    $updatedSource = $apiClient
        ->websiteButtons()
        ->updateAsync($buttonModel);
    var_dump($updatedSource->toArray());
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

// Теперь добавим в нашу созданную кнопку Онлайн-чат
try {
    $apiClient
        ->websiteButtons()
        ->addOnlineChatAsync($source->getSourceId());
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

// Получим 10 кнопок на сайт(CrmPlugin)
try {
    $buttons = $apiClient
        ->websiteButtons()
        ->get((new PagesFilter())->setLimit(10));
    var_dump($buttons->toArray());
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

// Получим кнопку по id источника с кодом для вставки на сайт
try {
    $button = $apiClient
        ->websiteButtons()
        ->getOne($source->getSourceId(), WebsiteButtonModel::getAvailableWith());
    var_dump($button->toArray());
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
