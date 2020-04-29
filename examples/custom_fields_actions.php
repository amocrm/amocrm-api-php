<?php

use AmoCRM\AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Collections\CustomFieldsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\CustomFieldModel;
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

//Сервис кастом полей для сделок
$customFieldsService = $apiClient->customFields(EntityTypesInterface::LEADS);

//Создадим несколько полей
$customFieldsCollection = new CustomFieldsCollection();
$cf = new CustomFieldModel();
$cf->setName('Поле Текст');
$cf->setType(CustomFieldModel::TYPE_TEXT);
$cf->setSort(10);
$customFieldsCollection->add($cf);

$cf = new CustomFieldModel();
$cf->setName('Поле Чекбокс');
$cf->setType(CustomFieldModel::TYPE_CHECKBOX);
$cf->setSort(20);
$customFieldsCollection->add($cf);

$cf = new CustomFieldModel();
$cf->setName('Поле Список');
$cf->setType(CustomFieldModel::TYPE_SELECT);
$cf->setSort(30);
$cf->setEnums([
    [
        'value' => 'Значение 1',
        'sort' => 10,
    ],
    [
        'value' => 'Значение 2',
        'sort' => 20,
    ],
    [
        'value' => 'Значение 3',
        'sort' => 30,
    ],
]);
$customFieldsCollection->add($cf);

try {
    //Добавим поля в аккаунт
    $customFieldsService->add($customFieldsCollection);

    //Получим поля сделок со всеми параметрами
    $customFieldsCollection = $customFieldsService->get(
        null,
        [
            CustomFieldModel::REQUIRED_STATUSES,
            CustomFieldModel::GROUP_ID,
            CustomFieldModel::ENUMS,
        ]
    );

    //Получим объект поля и удалим его
    $fieldToDelete = $customFieldsCollection->getBy('name', 'Поле Чекбокс');
    $customFieldsService->deleteOne($fieldToDelete);
    //TODO оповестить объект об удалении (событийка)

    //Получим объект группы и обновим, добавим enum и сделаем поле доступным для редактирования только через API
    /** @var CustomFieldModel $fieldToUpdate */
    $fieldToUpdate = $customFieldsCollection->getBy('name', 'Поле Список');
    $enums = $fieldToUpdate->getEnums();
    $enums[] = [
        'value' => 'Значение 4',
        'sort' => 40,
    ];
    $fieldToUpdate->setEnums($enums);
    $fieldToUpdate->setIsApiOnly(true);
    $fieldToUpdate = $customFieldsService->updateOne($fieldToUpdate);
    var_dump($fieldToUpdate->toArray());
} catch (AmoCRMApiException $e) {
    printError($e);
}
