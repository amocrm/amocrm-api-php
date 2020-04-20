<?php

use AmoCRM\AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Collections\CustomFieldGroupsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\CustomFieldGroupModel;
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

//Сервис групп полей
$customFieldGroupsService = $apiClient->customFieldGroups(EntityTypesInterface::LEADS);

//Создадим группу полей
$customFieldGroupsCollection = new CustomFieldGroupsCollection();
$cfGroup = new CustomFieldGroupModel();
$cfGroup->setName('Группа полей');
$cfGroup->setSort(15);
$customFieldGroupsCollection->add($cfGroup);
try {
    //Добавим группу
    $customFieldGroupsService->add($customFieldGroupsCollection);

    //Получим объект группы и удалим его
    $groupToDelete = $customFieldGroupsCollection->getBy('name', 'Группа полей');
    $customFieldGroupsService->deleteOne($groupToDelete);
    //TODO оповестить объект об удалении

    //Получим группы (это же пример :) )
    $customFieldGroupsCollection = $customFieldGroupsService->get();
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}

var_dump($customFieldGroupsCollection);
die;
