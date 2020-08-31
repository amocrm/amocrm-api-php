<?php

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\Leads\Pipelines\PipelinesCollection;
use AmoCRM\Collections\Leads\Pipelines\Statuses\StatusesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\Leads\Pipelines\PipelineModel;
use AmoCRM\Models\Leads\Pipelines\Statuses\StatusModel;
use League\OAuth2\Client\Token\AccessTokenInterface;

include_once __DIR__ . '/bootstrap.php';

$accessToken = getToken();

/**
 * @noinspection PhpRedundantVariableDocTypeInspection
 * @var AmoCRMApiClient $apiClient
 */
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

//Добавим новую главную воронку со статусами
//Сортировка для главной воронки проставится автоматически
$pipelinesCollection = new PipelinesCollection();
$pipeline = new PipelineModel();
$statusesCollection = new StatusesCollection();
$statusesCollection->add(
    (new StatusModel())
        ->setName('Новый статус')
        ->setColor('#fffd7f') /** @see StatusModel::COLORS */
)->add(
    (new StatusModel())
        ->setName('Новый статус 2')
        ->setColor('#ccc8f9') /** @see StatusModel::COLORS */
);

$pipeline
    ->setName('Новая главная поронка')
    ->setIsMain(true)
    ->setStatuses($statusesCollection);

$pipelinesCollection->add($pipeline);

$pipelinesService = $apiClient->pipelines();
try {
    $pipelinesCollection = $pipelinesService->add($pipelinesCollection);
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
