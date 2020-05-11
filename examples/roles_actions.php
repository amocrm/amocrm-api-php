<?php

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\Rights\RightModel;
use AmoCRM\Models\RoleModel;
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

//Сервис ролей
$rolesService = $apiClient->roles();

//Создадим роль
$roleModel = new RoleModel();
$roleModel
    ->setName('Новая роль')
    ->setRights(
        (new RightModel())
            ->setLeadsRights([
                RightModel::ACTION_ADD => RightModel::RIGHTS_DENIED,
                RightModel::ACTION_VIEW => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                RightModel::ACTION_DELETE => RightModel::RIGHTS_FULL,
                RightModel::ACTION_EXPORT => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                RightModel::ACTION_EDIT => RightModel::RIGHTS_FULL,
            ])
            ->setCompaniesRights([
                RightModel::ACTION_ADD => RightModel::RIGHTS_DENIED,
                RightModel::ACTION_VIEW => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                RightModel::ACTION_DELETE => RightModel::RIGHTS_FULL,
                RightModel::ACTION_EXPORT => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                RightModel::ACTION_EDIT => RightModel::RIGHTS_FULL,
            ])
            ->setContactsRights([
                RightModel::ACTION_ADD => RightModel::RIGHTS_DENIED,
                RightModel::ACTION_VIEW => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                RightModel::ACTION_DELETE => RightModel::RIGHTS_FULL,
                RightModel::ACTION_EXPORT => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                RightModel::ACTION_EDIT => RightModel::RIGHTS_FULL,
            ])
            ->setTasksRights([
                RightModel::ACTION_DELETE => RightModel::RIGHTS_FULL,
                RightModel::ACTION_EDIT => RightModel::RIGHTS_FULL,
            ])
            ->setMailAccess(false)
            ->setCatalogAccess(true)
            ->setStatusRights(
                [
                    [
                        'entity_type' => EntityTypesInterface::LEADS,
                        'pipeline_id' => 3166396,
                        'status_id' => 142,
                        'rights' => [
                            RightModel::ACTION_ADD => RightModel::RIGHTS_DENIED,
                            RightModel::ACTION_VIEW => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                            RightModel::ACTION_DELETE => RightModel::RIGHTS_FULL,
                            RightModel::ACTION_EXPORT => RightModel::RIGHTS_FULL,
                        ]
                    ],
                ]
            )
    );

try {
    $roleModel = $rolesService->addOne($roleModel);
} catch (AmoCRMApiException $e) {
    printError($e);
}


//Изменим имя роли
$roleModel->setName('Новое имя');
$roleModel->getRights()->setMailAccess(true);
try {
    $roleModel = $rolesService->updateOne($roleModel);
} catch (AmoCRMApiException $e) {
    printError($e);
}

//Получим роли аккаунта
try {
    $rolesCollection = $rolesService->get(null, [RoleModel::USERS]);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
