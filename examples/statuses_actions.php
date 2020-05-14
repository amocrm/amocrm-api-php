<?php

use AmoCRM\Collections\Leads\Pipelines\Statuses\StatusesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\Leads\Pipelines\PipelineModel;
use AmoCRM\Models\Leads\Pipelines\Statuses\StatusModel;
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

//Получим воронки
$pipelinesService = $apiClient->pipelines();
try {
    $pipelinesCollection = $pipelinesService->get();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
/** @var PipelineModel $pipeline */
$pipeline = $pipelinesCollection->getBy('name', 'Воронка');
//Создадим сервис для работы со статусами
$statusesService = $apiClient->statuses($pipeline->getId());

//Добавим статус в воронку
$statusesCollection = new StatusesCollection();
$statusModel = new StatusModel();
$statusModel->setName('Новый статус')
    ->setSort(200)
    ->setColor('#fffd7f'); /** @see StatusModel::COLORS */
$statusesCollection->add($statusModel);

try {
    $statusesCollection = $statusesService->add($statusesCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Обновим статус
/** @var StatusModel $statusModel */
$statusModel = $statusesCollection->first();
$statusModel
    ->setName('Новое название статуса');

try {
    $statusModel = $statusesService->updateOne($statusModel);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


//Удалим статус
try {
    $result = $statusesService->deleteOne($statusModel);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Получим статусы поронки
try {
    $statusesCollection = $statusesService->get();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

echo '<pre>' . $statusesCollection->count() . ' статусов в аккаунте в воронке ' . $statusesService->getEntityId() . '</pre>';
