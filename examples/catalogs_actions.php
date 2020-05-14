<?php

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\CatalogModel;
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

//Создадим каталог
//$catalogsCollection = new CatalogsCollection();
//$catalog = new CatalogModel();
//$catalog->setName('Новый список');
//$catalog->setCatalogType(EntityTypesInterface::DEFAULT_CATALOG_TYPE_STRING);
//$catalogsCollection->add($catalog);
//try {
//    $apiClient->catalogs()->add($catalogsCollection);
//} catch (AmoCRMApiException $e) {
//    printError($e);
//    die;
//}

//Создадим каталог счетов
//$catalog = new CatalogModel();
//$catalog->setName('Новый список');
//$catalog->setCatalogType(EntityTypesInterface::INVOICES_CATALOG_TYPE_STRING);
//$catalog->setCanBeDeleted(false);
//
//try {
//    $catalog = $apiClient->catalogs()->addOne($catalog);
//} catch (AmoCRMApiException $e) {
//    printError($e);
//    die;
//}

//Получим все каталоги
try {
    $catalogsCollection = $apiClient->catalogs()->get();
} catch (AmoCRMApiException $e) {
    printError($e);
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
} catch (AmoCRMApiException $e) {
    printError($e);
}
