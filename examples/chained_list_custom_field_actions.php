<?php

/** @since Release Spring 2022 */

use AmoCRM\Models\CustomFields\ChainedListCustomFieldModel;
use AmoCRM\Models\CustomFields\ChainedLists;
use AmoCRM\Models\CustomFieldsValues\ChainedListCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\ChainedListCustomFieldValueCollection;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\CustomFieldsValuesCollection;
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
try {
    $leadsCfService = $apiClient->customFields(EntityTypesInterface::LEADS);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

# Подготовим структуру поля
$chainedListCfStruct = new ChainedListCustomFieldModel();
$chainedListCfStruct->setName('Вложенные списки');
$chainedListCfStruct->setIsVisible(true);
$chainedListCfStruct->setChainedLists(
    ChainedLists::fromArray([
        [
            'catalog_id' => 9929,
            'parent_catalog_id' => null,
            'title' => 'Модель',
        ],
        [
            'catalog_id' => 9931,
            'parent_catalog_id' => 9929,
            'title' => 'Марка',
        ],
    ])
);

# Создадим денежное поле для сделок
try {
    /** @var ChainedListCustomFieldModel $chainedListCf */
    $chainedListCf = $leadsCfService->addOne($chainedListCfStruct);
    var_dump($chainedListCf);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

# Изменим поле
try {
    $chainedListCf->setName('Авто');
    $chainedListCf = $leadsCfService->updateOne($chainedListCf);
    var_dump($chainedListCf);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

# Получим наше созданное денежное поле
try {
    $chainedListCf = $leadsCfService->getOne($chainedListCf->getId());
    var_dump($chainedListCf);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

# Получим сервис для работы со сделками
try {
    $leadsService = $apiClient->leads();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

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
$cfValuesModel = new ChainedListCustomFieldValuesModel();
$cfValuesModel->setFieldId($chainedListCf->getId());
$cfValuesModel->setValues(
    ChainedListCustomFieldValueCollection::fromArray(
        [
            # Tesla
            [
                'catalog_id' => 9929,
                'catalog_element_id' => 1600409,
            ],
            # Model S
            [
                'catalog_id' => 9931,
                'catalog_element_id' => 1600411,
            ],
        ]
    )
);
$valuesCollection = (new CustomFieldsValuesCollection())->add($cfValuesModel);

# К полученной сделке установим коллекцию значений полей сделки
$lead->setCustomFieldsValues($valuesCollection);

# Обновляем сделку
try {
    $lead = $leadsService->updateOne($lead);
    var_dump($lead);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
