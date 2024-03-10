<?php

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\LongLivedAccessToken;
use AmoCRM\Exceptions\AmoCRMApiException;

include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/error_printer.php';

$accessToken = 'XXX';
$accountUrl = 'example.amocrm.ru';

$apiClient = new AmoCRMApiClient();
try {
    $longLivedAccessToken = new LongLivedAccessToken($accessToken);
} catch (\AmoCRM\Exceptions\InvalidArgumentException $e) {
    printError($e);
    die;
}

$apiClient->setAccessToken($longLivedAccessToken)
    ->setAccountBaseDomain($accountUrl);

//Получим информацию об аккаунте
try {
    $account = $apiClient->account()->getCurrent();
} catch (AmoCRMApiException $e) {
    var_dump($e->getTraceAsString());
    printError($e);
    die;
}

echo $account->getName();
