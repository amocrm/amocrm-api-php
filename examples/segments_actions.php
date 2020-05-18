<?php

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\Customers\Segments\SegmentModel;
use AmoCRM\Models\CustomFields\TextCustomFieldModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
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

//Сервис сегментов
$segmentsService = $apiClient->customersSegments();

//Получим сегменты аккаунта
try {
    $segmentsCollection = $segmentsService->get();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


//Создадим сегмент
$segmentModel = new SegmentModel();
$segmentModel
    ->setName('Новый сегмент')
    ->setColor('ff5376');

try {
    $segmentModel = $segmentsService->addOne($segmentModel);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

$segmentsCustomFieldsService = $apiClient->customFields(EntityTypesInterface::CUSTOMERS_SEGMENTS);

try {
    $field = $segmentsCustomFieldsService->addOne(
        (new TextCustomFieldModel())
            ->setName('Поле текст')
    );
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

$segmentModel->setCustomFieldsValues(
    (new CustomFieldsValuesCollection())
        ->add(
            (new TextCustomFieldValuesModel())
                ->setFieldId($field->getId())
                ->setValues(
                    (new TextCustomFieldValueCollection())
                        ->add((new TextCustomFieldValueModel())->setValue('Текст'))
                )
        )
);

//Обновим покупателя
try {
    $segmentModel = $segmentsService->updateOne($segmentModel);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


//Освежим модель
try {
    $segmentModel = $segmentsService->syncOne($segmentModel);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

var_dump($segmentModel);
