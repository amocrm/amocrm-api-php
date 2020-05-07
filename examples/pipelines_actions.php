<?php

use AmoCRM\Collections\Leads\Pipelines\PipelinesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\Leads\Pipelines\PipelineModel;
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

//Добавим новую главную воронку
//Сортировка для главной воронки проставится автоматически
$pipelinesCollection = new PipelinesCollection();
$pipeline = new PipelineModel();
$pipeline
    ->setName('Новая главная поронка')
    ->setIsMain(true);

$pipelinesCollection->add($pipeline);

$pipelinesService = $apiClient->pipelines();
try {
    $pipelinesCollection = $pipelinesService->add($sipUnsortedCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Обновим воронку
/** @var PipelineModel $pipelineModel */
$pipelineModel = $pipelinesCollection->first();
$pipelineModel
    ->setName('Новое название воронки')
    ->setIsMain(false);

try {
    $pipelineModel = $pipelinesService->updateOne($pipelineModel);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Удалим воронку
try {
    $result = $pipelinesService->deleteOne($pipelineModel);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Получим воронки
try {
    $pipelinesCollection = $pipelinesService->get();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

echo '<pre>' . $pipelinesCollection->count() . ' воронок в аккаунте</pre>';
