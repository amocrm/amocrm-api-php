<?php

/** @since Release Spring 2022 */

use AmoCRM\Filters\CurrenciesFilter;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMApiException;
use League\OAuth2\Client\Token\AccessTokenInterface;

/** @var AmoCRMApiClient $apiClient */
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

# Получим сервис для работы с валютами
$service = $apiClient->currencies();

# Получение списка валют
try {
    $collection = $service->get();
    var_dump($collection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

# Получение первой модели из коллекции
$model = $collection->first();
var_dump($model->getCode());

# Получение из коллекции модели по коду
var_dump($collection->getByCode('EUR'));

# Подготовим фильтр
$filter = new CurrenciesFilter();
$filter->setLimit(15);
$filter->setPage(2);

# Получение списка валют с фильтром
try {
    $collection = $service->get($filter);
    var_dump($collection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

# Получение следующей страницы
var_dump($collection->getNextPageLink());

# Получение предыдущей страницы
var_dump($collection->getPrevPageLink());
