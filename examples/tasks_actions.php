<?php

use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Collections\TasksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\TaskModel;
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

//Создадим задачу
$tasksCollection = new TasksCollection();
$task = new TaskModel();
$task->setTaskTypeId(TaskModel::TASK_TYPE_ID_FOLLOW_UP)
    ->setText('Новая задач')
    ->setCompleteTill(mktime(10, 0, 0, 10, 3, 2020))
    ->setEntityType(EntityTypesInterface::LEADS)
    ->setEntityId(1)
    ->setDuration(30 * 60) //30 минут
    ->setResponsibleUserId(123);
$tasksCollection->add($task);

$tasksService = $apiClient->tasks();
try {
    $tasksCollection = $tasksService->add($tasksCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Закроем задачу, что только создали (не делайте так в продакшене)
$taskToClose = $tasksCollection->first();
$taskToClose->setIsCompleted(true)
    ->setResult('Выполнено');

try {
    //Получим актуальное состояние задачи и обновим её
    $taskToClose = $tasksService->syncOne($taskToClose);
    $taskToClose = $tasksService->updateOne($taskToClose);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
var_dump($taskToClose->toArray());


//Получим задачи с фильтром по типу
$tasksFilter = new \AmoCRM\Filters\TasksFilter();
$tasksFilter->setTaskTypeId(TaskModel::TASK_TYPE_ID_MEETING);
try {
    $tasksCollection = $tasksService->get($tasksFilter);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
var_dump($tasksCollection->toArray());
