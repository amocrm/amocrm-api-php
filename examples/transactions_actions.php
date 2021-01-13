<?php

use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\Collections\Customers\Transactions\TransactionsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\CatalogElementModel;
use AmoCRM\Models\Customers\Transactions\TransactionModel;
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

//Добавим новую транзакцию в покупателя с id = 1 c товарами
$transactionsCollection = new TransactionsCollection();
$transaction = new TransactionModel();

$catalogElementsCollection = new CatalogElementsCollection();
$catalogElementsCollection->add(
    (new CatalogElementModel())
        ->setCatalogId(4255)
        ->setId(483287)
        ->setQuantity(5)
);
$transaction
    ->setComment('Транзакция')
    ->setPrice(123124)
    ->setCatalogElements($catalogElementsCollection);

$transactionsCollection->add($transaction);

//Обязательно необходимо указать ID покупателя, к которому будет добавлена транзакция
$transactionsService = $apiClient->transactions();
$transactionsService->setCustomerId(1);
try {
    $transactionsCollection = $transactionsService->add($transactionsCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Удалим транзакцию
try {
    $result = $transactionsService->deleteOne($transaction);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Получим транзакции
try {
    $transactionsCollection = $transactionsService->get();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

echo '<pre>' . $transactionsCollection->count() . ' транзакции на данной странице</pre>';
