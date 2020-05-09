<?php

use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\Collections\CompaniesCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\CatalogElementsFilter;
use AmoCRM\Filters\CompanyFilter;
use AmoCRM\Models\CatalogElementModel;
use AmoCRM\Models\CompanyModel;
use League\OAuth2\Client\Token\AccessTokenInterface;

include_once __DIR__ . 'bootstrap.php';

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

//Создадим компанию
$company = new CompanyModel();
$company->setName('Example');

$companiesCollection = new CompaniesCollection();
$companiesCollection->add($company);
try {
    $apiClient->companies()->add($companiesCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Получим сделку по ID, сделку и првяжем компанию к сделке
try {
    $lead = $apiClient->leads()->getOne(3916883);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

$links = new LinksCollection();
$links->add($lead);

//TODO проверить, можно ли реально привязать элемент каталога к компании
//Получим элементы из нужного нам катагола, где в названии или полях есть слово кросовки
$catalogElementsCollection = new CatalogElementsCollection();
$catalogElementsService = $apiClient->catalogElements();
$catalogElementsService->setEntityType(1001);
$catalogElementsFilter = new CatalogElementsFilter();
$catalogElementsFilter->setQuery('Кросовки');
try {
    $catalogElementsCollection = $catalogElementsService->get($catalogElementsFilter);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

/** @var CatalogElementModel $nikeElement */
$nikeElement = $catalogElementsCollection->getBy('name', 'Кросовки Nike');
if ($nikeElement) {
    //Установим кол-во, так как эта модель будет привязана, данное свойство используется только при привязке к сущности
    $nikeElement->setQuantity(10);
    $links->add($nikeElement);
}

try {
    $apiClient->companies()->link($company, $links);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Создадим фильтр по id компании
$filter = new CompanyFilter();
$filter->setIds([1]);

//Получим компании по фильтру
try {
    $companies = $apiClient->companies()->get($filter, [CompanyModel::CONTACTS, CompanyModel::LEADS, CompanyModel::CATALOG_ELEMENTS]);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

var_dump($companies->toArray());
