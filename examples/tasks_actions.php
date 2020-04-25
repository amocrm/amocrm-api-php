<?php

use AmoCRM\Collections\TasksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\TaskModel;
use GuzzleHttp\Exception\ConnectException;
use League\OAuth2\Client\Token\AccessTokenInterface;

include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/token_actions.php';
include_once __DIR__ . '/api_client.php';

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

//Создадим задачу
$tasksCollection = new TasksCollection();
$task = new TaskModel();
$task->setTaskTypeId(TaskModel::TASK_TYPE_ID_CALL)
    ->setText('Новая задач')
    ->setCompleteTill(mktime(10, 0, 0, 10, 3, 2020))
    ->setEntityType('lead') //todo check and constant
    ->setEntityId(1)
    ->setDuration(30 * 60 * 60) //30 минут
    ->setResponsibleUserId(123);
$tasksCollection->add($task);

try {
    $apiClient->tasks()->add($tasksCollection);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode() . $e->getTitle();
    die;
}

//Получим все задачи
try {
    $tasksCollection = $apiClient->tasks()->get();
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode() . $e->getTitle();
    die;
}

