<?php

use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\CatalogElementsFilter;
use AmoCRM\Models\CatalogElementModel;
use GuzzleHttp\Exception\ConnectException;
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

//Получим все каталоги
try {
    $catalogsCollection = $apiClient->catalogs()->get();
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode() . $e->getTitle();
    die;
}

//Получим каталог по названию
$catalog = $catalogsCollection->getBy('name', 'Товары');

//Добавим элемент в каталог (Список)
$catalogElementsCollection = new CatalogElementsCollection();
$catalogElement = new CatalogElementModel();
$catalogElement->setName('Новый товар');
$catalogElementsCollection->add($catalogElement);
$catalogElementsService = $apiClient->catalogElements($catalog->getId());
try {
    $catalogElementsService->add($catalogElementsCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


//Получим элементы из нужного нам каталога, где в названии или полях есть слово кросовки
$catalogElementsCollection = new CatalogElementsCollection();
$catalogElementsService = $apiClient->catalogElements($catalog->getId());
$catalogElementsFilter = new CatalogElementsFilter();
$catalogElementsFilter->setQuery('Кросовки');
try {
    $catalogElementsCollection = $catalogElementsService->get($catalogElementsFilter);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

$nikeElement = $catalogElementsCollection->getBy('name', 'Кросовки Nike');
if ($nikeElement) {
    //Установим кол-во, так как эта модель будет привязана, данное свойство используется только при привязке к сущности
    $nikeElement->setQuantity(10.22);
    //Получим сделку по ID
    try {
        $lead = $apiClient->leads()->getOne(7397517);
    } catch (AmoCRMApiException $e) {
        printError($e);
        die;
    }

    //Привяжем к сделке наш элемент
    $links = new LinksCollection();
    $links->add($nikeElement);
    try {
        $apiClient->leads()->link($lead, $links);
    } catch (AmoCRMApiException $e) {
        printError($e);
        die;
    }
}
