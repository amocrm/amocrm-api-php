<?php

use AmoCRM\Exceptions\AmoCRMApiException;

include_once __DIR__ . '/bootstrap.php';

$accessToken = getToken();

try {
    $accountSubdomainModel = $apiClient->getOAuthClient()
        ->getAccountSubdomain($accessToken);

    var_dump($accountSubdomainModel->toArray());
    var_dump($accountSubdomainModel->toJson());

    $apiClient->setAccountBaseDomain($accountSubdomainModel->getSubdomain());

    var_dump($apiClient->getAccountBaseDomain());
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
