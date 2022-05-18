<?php

/** @since Release Spring 2022 */

use AmoCRM\Models\CustomFields\MonetaryCustomFieldModel;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\LeadsFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\LeadModel;
use League\OAuth2\Client\Token\AccessTokenInterface;

/** @var AmoCRMApiClient $apiClient */
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


# Сервис кастомных полей для сделок
$leadsCfService = $apiClient->customFields(EntityTypesInterface::LEADS);

# Получим сервис для работы с валютами
$currenciesService = $apiClient->currencies();

# Получим список валют с 3мя валютами
$currencies = $currenciesService->get();
# Получение первой модели из коллекции
$currency = $currencies->first();

# Подготовим структуру денежного поля
$monetaryCfStruct = new MonetaryCustomFieldModel();
$monetaryCfStruct->setName('Денежное поле');
$monetaryCfStruct->setCurrency($currency->getCode());
$monetaryCfStruct->setIsApiOnly(true);

# Создадим денежное поле для сделок
try {
    /** @var MonetaryCustomFieldModel $monetaryCf */
    $monetaryCf = $leadsCfService->addOne($monetaryCfStruct);
    var_dump($monetaryCf);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


# Установим валюту поля на EUR
$currency = $currencies->getByCode('EUR');
$monetaryCf->setCurrency($currency->getCode());

# Изменим поле
try {
    $monetaryCf = $leadsCfService->updateOne($monetaryCf);
    var_dump($monetaryCf);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


# Получим наше созданное денежное поле
try {
    $leadsCf = $leadsCfService->getOne($monetaryCf->getId());
    var_dump($leadsCf);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

# Получим сервис для работы со сделками
$leadsService = $apiClient->leads();

# Получим любую 1 сделку
try {
    /** @var LeadModel $lead */
    $leads = $leadsService->get(
        (new LeadsFilter())->setLimit(1)
    );
    var_dump($leads);

    # Получим модель сделки
    $lead = $leads->first();
    var_dump($lead);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

# Подготовим структуру значение денежного поля
$monetaryValuesModel = new \AmoCRM\Models\CustomFieldsValues\MonetaryCustomFieldValuesModel();
$monetaryValuesModel->setFieldId($monetaryCf->getId());
$monetaryValuesModel->setValues(
    (new \AmoCRM\Models\CustomFieldsValues\ValueCollections\MonetaryCustomFieldValueCollection())->add(
        (new \AmoCRM\Models\CustomFieldsValues\ValueModels\MonetaryCustomFieldValueModel())->setValue(100)
    )
);
$valuesCollection = (new \AmoCRM\Collections\CustomFieldsValuesCollection())->add(
    $monetaryValuesModel
);

# К полученной сделке установим коллекцию значений полей сделки
$lead->setCustomFieldsValues($valuesCollection);

# Получим модель значения созданного денежного поля
$lead = $leadsService->updateOne($lead);
var_dump($lead);
