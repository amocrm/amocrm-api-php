<?php

use AmoCRM\Collections\CustomFields\CustomFieldEnumsCollection;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Collections\CustomFields\CustomFieldsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\CustomFields\CheckboxCustomFieldModel;
use AmoCRM\Models\CustomFields\CustomFieldModel;
use AmoCRM\Models\CustomFields\EnumModel;
use AmoCRM\Models\CustomFields\SelectCustomFieldModel;
use AmoCRM\Models\CustomFields\TextCustomFieldModel;
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

//Сервис кастом полей для сегментов
//$customFieldsService = $apiClient->customFields(EntityTypesInterface::CUSTOMERS_SEGMENTS);

//Сервис кастом полей для каталога (id каталога указывается через :)
//$customFieldsService = $apiClient->customFields(EntityTypesInterface::CATALOGS . ':' . 4255);

//Получим поля
try {
    $result = $customFieldsService->get();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Создадим модель поля и освежим его
$customFieldModel = new CustomFieldModel();
$customFieldModel->setId(269303);

try {
    $customFieldModel = $customFieldsService->syncOne($customFieldModel);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


//Создадим несколько полей
$customFieldsCollection = new CustomFieldsCollection();
$cf = new TextCustomFieldModel();
$cf
    ->setName('Поле Текст')
    ->setSort(10);

$customFieldsCollection->add($cf);

$cf = new CheckboxCustomFieldModel();
$cf
    ->setName('Поле Чекбокс')
    ->setSort(20)
    ->setCode('MYSUPERCHECKBOX100');

$customFieldsCollection->add($cf);

$cf = new SelectCustomFieldModel();
$cf
    ->setName('Поле Список')
    ->setSort(30)
    ->setCode('MYSUPERLISTCF100')
    ->setEnums(
        (new CustomFieldEnumsCollection())
            ->add(
                (new EnumModel())
                    ->setValue('Значение 1')
                    ->setSort(10)
            )
            ->add(
                (new EnumModel())
                    ->setValue('Значение 2')
                    ->setSort(20)
            )
            ->add(
                (new EnumModel())
                    ->setValue('Значение 3')
                    ->setSort(30)
            )
    );

$customFieldsCollection->add($cf);

try {
    //Добавим поля в аккаунт
    $customFieldsCollection = $customFieldsService->add($customFieldsCollection);

    //Получим объект поля и удалим его
    $fieldToDelete = $customFieldsCollection->getBy('code', 'MYSUPERCHECKBOX100');
    if ($fieldToDelete) {
        $customFieldsService->deleteOne($fieldToDelete);
    }

    //Получим объект группы и обновим, добавим enum и сделаем поле доступным для редактирования только через API
    $fieldToUpdate = $customFieldsCollection->getBy('code', 'MYSUPERLISTCF100');
    $enums = $fieldToUpdate->getEnums();
    if ($enums) {
        $enums->add(
            (new EnumModel())
                ->setValue('Значение 4')
                ->setSort(40)
        );
    }
    $fieldToUpdate->setEnums($enums);
    $fieldToUpdate->setIsApiOnly(true);
    $fieldToUpdate = $customFieldsService->updateOne($fieldToUpdate);
    var_dump($fieldToUpdate->toArray());
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
