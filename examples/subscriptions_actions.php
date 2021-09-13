<?php

declare(strict_types=1);

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\PagesFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;

include_once __DIR__ . '/bootstrap.php';

/** @var \AmoCRM\Client\AmoCRMApiClient $apiClient */
$accessToken = getToken();
$apiClient
    ->setAccessToken($accessToken)
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

$subscriptionsService = $apiClient->entitySubscriptions(EntityTypesInterface::LEADS);

try {
    $filer = (new PagesFilter())
        ->setLimit(3);
    $subscriptions = $subscriptionsService->getByParentId(667999631, $filer);
} catch (AmoCRMApiException $exception) {
    printError($exception);
    die;
}

var_dump($subscriptions->toArray());

try {
    $nextSubscriptions = $subscriptionsService->nextPage($subscriptions);
} catch (AmoCRMApiException $exception) {
    printError($exception);
    die;
}

var_dump($nextSubscriptions->toArray());
