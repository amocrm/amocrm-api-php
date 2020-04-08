<?php

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\CatalogModel;
use GuzzleHttp\Exception\ConnectException;
use League\OAuth2\Client\Token\AccessTokenInterface;

include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/token_actions.php';
include_once __DIR__ . '/api_client.php';

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
        });

//Создадим каталог
//$catalogsCollection = new CatalogsCollection();
//$catalog = new CatalogModel();
//$catalog->setName('Новый список');
//$catalog->setCatalogType(EntityTypesInterface::DEFAULT_CATALOG_TYPE_STRING);
//$catalogsCollection->add($catalog);
//try {
//    $apiClient->catalogs()->add($catalogsCollection);
//} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
//    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode() . $e->getTitle();
//    die;
//}

//Создадим каталог счетов
//$catalogsCollection = new CatalogsCollection();
//$catalog = new CatalogModel();
//$catalog->setName('Новый список');
//$catalog->setCatalogType(EntityTypesInterface::INVOICES_CATALOG_TYPE_STRING);
//$catalogsCollection->add($catalog);
//try {
//    $apiClient->catalogs()->add($catalogsCollection);
//} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
//    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode() . $e->getTitle();
//    die;
//}

//Получим все каталоги
try {
    $catalogsCollection = $apiClient->catalogs()->get();
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode() . $e->getTitle();
    die;
}

//Получим каталог по названию
$catalog = $catalogsCollection->getBy('name', 'Новый список');
//Установим сортировку и обновим каталог
if ($catalog instanceof CatalogModel) {
    $catalog->setSort(100);
}
try {
    $apiClient->catalogs()->updateOne($catalog);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode() . $e->getTitle();
    die;
}
