<?php

use AmoCRM\Collections\Leads\LossReasons\LossReasonsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\Leads\LossReasons\LossReasonModel;
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

//Добавим новую причину отказа в аккаунт
$lossReasonsCollection = new LossReasonsCollection();
$lossReason = new LossReasonModel();
$lossReason
    ->setName('Причина отказа')
    ->setSort(3);

$lossReasonsCollection->add($lossReason);

$lossReasonService = $apiClient->lossReasons();
try {
    $lossReasonsCollection = $lossReasonService->add($lossReasonsCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Обновим причину отказа
/** @var LossReasonModel $lossReasonModel */
$lossReasonModel = $pipelinesCollection->first();
$lossReasonModel
    ->setName('Новое название причины отказа');

try {
    $lossReasonModel = $lossReasonService->updateOne($lossReasonModel);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Удалим причину отказа
try {
    $result = $lossReasonService->deleteOne($pipelineModel);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Получим причины отказа
try {
    $lossReasonsCollection = $lossReasonService->get();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

echo '<pre>' . $lossReasonsCollection->count() . ' причин(ы) отказа в аккаунте</pre>';
