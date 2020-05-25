<?php

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\CallModel;
use AmoCRM\Models\Interfaces\CallInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Ramsey\Uuid\Uuid;

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

//Добавим входящий звонок
$call = new CallModel();
$call
    ->setPhone('+7912312321')
    ->setCallStatus(CallInterface::CALL_STATUS_SUCCESS_CONVERSATION)
    ->setCallResult('Разговор состоялся')
    ->setDuration(148)
    ->setUniq(Uuid::uuid4())
    ->setSource('integration name')
    ->setDirection(CallInterface::CALL_DIRECTION_IN)
    ->setLink('https://example.test/test.mp3');

try {
    $call = $apiClient->calls()->addOne($call);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
