<?php

use AmoCRM\Collections\UsersCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\Rights\RightModel;
use AmoCRM\Models\UserModel;
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

//Сервис пользователей
$usersService = $apiClient->users();

//Создадим пользователей
$usersCollection = new UsersCollection();

$userModel = new UserModel();
$userModel
    ->setName('Иван Петров')
    ->setEmail('example3@example.test')
    ->setPassword('ExAmPlE2o20!')
    ->setLang('ru')
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
                        ],
                    ],
                ]
            )
            ->setGroupId(286084)
    );
$usersCollection->add($userModel);

$freeUserModel = new UserModel();
$freeUserModel
    ->setName('Иван Мещеряков')
    ->setEmail('example2@example.test')
    ->setPassword('ExAmPlE2o18!')
    ->setLang('ru')
    ->setRights(
        (new RightModel())
            ->setIsFree(true)
    );
$usersCollection->add($freeUserModel);

$userWithRole = new UserModel();
$userWithRole
    ->setName('Елена Иванова')
    ->setEmail('example1@example.test')
    ->setPassword('ExAmPlE2o19!')
    ->setLang('ru')
    ->setRights(
        (new RightModel())
            ->setRoleId(107995)
    );
$usersCollection->add($userWithRole);

try {
    $usersCollection = $usersService->add($usersCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Получим всех пользователей аккаунта
try {
    $usersCollection = $usersService->get();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

var_dump($usersCollection->toArray());
