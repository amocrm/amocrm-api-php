<?php

use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\CatalogElementModel;
use AmoCRM\Models\CompanyModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\Customers\CustomerModel;
use AmoCRM\Models\LeadModel;
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

//Привяжем контакт к покупателю
$linksCollection = new LinksCollection();
$linksCollection->add((new CustomerModel())->setId(1));
try {
    $linksCollection = $apiClient->contacts()->link((new ContactModel())->setId(7143559), $linksCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Или так
$linksCollection = new LinksCollection();
$linksCollection->add((new ContactModel())->setId(7143559));
try {
    $linksCollection = $apiClient->customers()->link((new CustomerModel())->setId(1), $linksCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//А этот контакт сделаем главным
$linksCollection = new LinksCollection();
$linksCollection->add((new ContactModel())->setId(9820781)->setIsMain(true));
try {
    $linksCollection = $apiClient->customers()->link((new CustomerModel())->setId(1), $linksCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}



//Привяжем компанию к покупателю
$linksCollection = new LinksCollection();
$linksCollection->add((new CustomerModel())->setId(1));
try {
    $linksCollection = $apiClient->companies()->link((new CompanyModel())->setId(7964425), $linksCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Или так
$linksCollection = new LinksCollection();
$linksCollection->add((new CompanyModel())->setId(7964425));
try {
    $linksCollection = $apiClient->customers()->link((new CustomerModel())->setId(1), $linksCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}



//Привяжем элемент каталога к покупателю
$linksCollection = new LinksCollection();
$linksCollection->add(
    (new CatalogElementModel())
        ->setCatalogId(2079)
        ->setId(174395)
        ->setQuantity(10)
);
try {
    $linksCollection = $apiClient->customers()->link((new CustomerModel())->setId(1), $linksCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Привяжем элемент каталога к сделке с ценой, которая хранится у товаров в доп поле типа ЦЕНА с ID 123456
$linksCollection = new LinksCollection();
$linksCollection->add(
    (new CatalogElementModel())
        ->setCatalogId(2079)
        ->setId(174395)
        ->setQuantity(10)
        ->setPriceId(123456)
);
try {
    $linksCollection = $apiClient->customers()->link((new LeadModel())->setId(1), $linksCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}



//Привяжем компанию к контакту
$linksCollection = new LinksCollection();
$linksCollection->add((new ContactModel())->setId(9820777));
try {
    $linksCollection = $apiClient->companies()->link((new CompanyModel())->setId(7964425), $linksCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Или так
$linksCollection = new LinksCollection();
$linksCollection->add((new CompanyModel())->setId(7964425));
try {
    $linksCollection = $apiClient->contacts()->link((new ContactModel())->setId(9820777), $linksCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}



//Привяжем компанию к сделке
$linksCollection = new LinksCollection();
$linksCollection->add((new LeadModel())->setId(6625001));
try {
    $linksCollection = $apiClient->companies()->link((new CompanyModel())->setId(7964425), $linksCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Или так
$linksCollection = new LinksCollection();
$linksCollection->add((new CompanyModel())->setId(7964425));
try {
    $linksCollection = $apiClient->leads()->link((new LeadModel())->setId(6625001), $linksCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
