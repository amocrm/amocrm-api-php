<?php

declare(strict_types=1);

use AmoCRM\Models\Talks\TalkCloseActionModel;
use AmoCRM\Exceptions\AmoCRMApiException;
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

$talksService = $apiClient->talks();

try {
    $talk = $talksService->getOne('114');
} catch (AmoCRMApiException $exception) {
    printError($exception);
    die;
}

try {
    $talksService->close(new TalkCloseActionModel($talk->getTalkId(), true));
} catch (AmoCRMApiException $exception) {
    printError($exception);
    die;
}
