<?php

declare(strict_types=1);

use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\Rights\RightModel;
use AmoCRM\Models\UserModel;
use League\OAuth2\Client\Token\AccessTokenInterface;

include_once __DIR__ . '/bootstrap.php';

$accessToken = getToken();

/** @var \AmoCRM\Client\AmoCRMApiClient $apiClient */
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

$usersService = $apiClient->users();

$userModel = new UserModel();
$userModel
    ->setName('Иван Петров')
    ->setEmail('example12671@example.test')
    ->setPassword('ExAmPlE2o20!')
    ->setLang('ru')
    ->setRights(
        (new RightModel())
            ->setLeadsRights([
                RightModel::ACTION_ADD => RightModel::RIGHTS_DENIED,
                RightModel::ACTION_VIEW => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                RightModel::ACTION_DELETE => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                RightModel::ACTION_EXPORT => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                RightModel::ACTION_EDIT => RightModel::RIGHTS_ONLY_RESPONSIBLE,
            ])
            ->setCompaniesRights([
                RightModel::ACTION_ADD => RightModel::RIGHTS_DENIED,
                RightModel::ACTION_VIEW => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                RightModel::ACTION_DELETE => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                RightModel::ACTION_EXPORT => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                RightModel::ACTION_EDIT => RightModel::RIGHTS_ONLY_RESPONSIBLE,
            ])
            ->setContactsRights([
                RightModel::ACTION_ADD => RightModel::RIGHTS_DENIED,
                RightModel::ACTION_VIEW => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                RightModel::ACTION_DELETE => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                RightModel::ACTION_EXPORT => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                RightModel::ACTION_EDIT => RightModel::RIGHTS_ONLY_RESPONSIBLE,
            ])
            ->setTasksRights([
                RightModel::ACTION_DELETE => RightModel::RIGHTS_FULL,
                RightModel::ACTION_EDIT => RightModel::RIGHTS_FULL,
            ])
            ->setMailAccess(false)
            ->setCatalogRights(
                [
                    [
                        'catalog_id' => 5025,
                        'rights' => [
                            RightModel::ACTION_ADD => RightModel::RIGHTS_DENIED,
                            RightModel::ACTION_VIEW => RightModel::RIGHTS_FULL,
                            RightModel::ACTION_EDIT => RightModel::RIGHTS_LINKED,
                            RightModel::ACTION_DELETE => RightModel::RIGHTS_LINKED,
                            RightModel::ACTION_EXPORT => RightModel::RIGHTS_LINKED,
                        ],
                    ]
                ]
            )
            ->setStatusRights(
                [
                    [
                        'entity_type' => EntityTypesInterface::LEADS,
                        'pipeline_id' => 3858604,
                        'status_id' => 142,
                        'rights' => [
                            RightModel::ACTION_ADD => RightModel::RIGHTS_DENIED,
                            RightModel::ACTION_VIEW => RightModel::RIGHTS_FULL,
                            RightModel::ACTION_DELETE => RightModel::RIGHTS_FULL,
                            RightModel::ACTION_EXPORT => RightModel::RIGHTS_FULL,
                        ],
                    ],
                ]
            )
    );

try {
    $userModel = $usersService->addOne($userModel);
} catch (\AmoCRM\Exceptions\AmoCRMApiErrorResponseException $exception) {
    echo $exception->getMessage();
    echo json_encode($exception->getValidationErrors());
    die();
}

echo json_encode($userModel->toArray());
