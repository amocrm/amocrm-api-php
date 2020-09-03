<?php

use AmoCRM\AmoCRM\Filters\CatalogsFilter;
use AmoCRM\Collections\CustomFields\CustomFieldNestedCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\CatalogModel;
use AmoCRM\Models\CustomFields\CategoryCustomFieldModel;
use AmoCRM\Models\CustomFields\NestedModel;
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

//Получим каталоги по типу
try {
    $catalogsFilter = new CatalogsFilter();
    $catalogsFilter->setType(EntityTypesInterface::INVOICES_CATALOG_TYPE_STRING);
    $catalogsCollection = $apiClient->catalogs()->get($catalogsFilter);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

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

// обновим поле типа категория с вложенными подкатегориями
// сразу укажем вложенность через временные метки request_id|parent_request_id
/** @noinspection PhpUnhandledExceptionInspection */
$cf = (new CategoryCustomFieldModel())
    ->setId(604229)
    ->setName('Поле Категория')
    ->setSort(1)
    ->setNested(
        (new CustomFieldNestedCollection())
            ->add(
                (new NestedModel())
                    ->setValue('Категория 1')
                    ->setSort(1)
                    ->setRequestId('category1')
            )
            ->add(
                (new NestedModel())
                    ->setValue('ПодКатегория 1')
                    ->setSort(1)
                    ->setRequestId('subcategory1')
                    ->setParentRequestId('category1')
            )
            ->add(
                (new NestedModel())
                    ->setValue('ПодПодКатегория 1')
                    ->setSort(1)
                    ->setParentRequestId('subcategory1')
            )
    );

try {
    $cf = $apiClient
        ->customFields(EntityTypesInterface::CATALOGS . ':' . $catalog->getId())
        ->updateOne($cf);

    echo json_encode($cf->toArray());
} catch (AmoCRMApiException $e) {
    printError($e);
}
