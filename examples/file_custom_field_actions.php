<?php

/** @since Release Spring 2022 */

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\CustomFields\CustomFieldsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\LeadsFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\CustomFields\CustomFieldModel;
use AmoCRM\Models\CustomFields\FileCustomFieldModel;
use AmoCRM\Models\CustomFieldsValues\FileCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\FileCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\FileCustomFieldValueModel;
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

# Получение полей сделки
/** @var CustomFieldsCollection $leadsCfCollection */
$leadsCfCollection = $leadsCfService->get();

$fileCfModel = null;
if ($leadsCfCollection !== null) {
    # Получение поля типа "Файл"
    /** @var null|FileCustomFieldModel $fileCfModel */
    $fileCfModel = $leadsCfCollection->getBy('type', CustomFieldModel::TYPE_FILE);
}

if ($fileCfModel === null) {
    # Создание поля
    $fileCfModel = new FileCustomFieldModel();
    $fileCfModel->setName('Поле типа "Файл"');
    $fileCfModel->setIsVisible(true);

    /** @var FileCustomFieldModel $fileCfModel */
    $fileCfModel = $leadsCfService->addOne($fileCfModel);
}

# Изменение поля
$fileCfModel->setName('Поле типа "Файл" CHANGED ');
$fileCfModel = $leadsCfService->updateOne($fileCfModel);

# Получим сервис для работы со сделками
$leadsService = $apiClient->leads();

/** @var LeadModel $lead */
$leads = $leadsService->get((new LeadsFilter())->setLimit(1));

# Получим модель сделки
$lead = $leads->first();
if ($lead === null) {
    # Если нет сделок - создадим
    $lead = $leadsService->addOne(
        (new LeadModel())->setName('Сделка для тестирование поля "Файл"')
    );
}

# Подготовим структуру значение поля типа "Файл"
$cfValuesModel = new FileCustomFieldValuesModel();
$cfValuesModel->setFieldId($fileCfModel->getId());
# Готовим коллекцию значений
$cfValueCollection = new FileCustomFieldValueCollection();
# Готовим модель значения
$cfValueModel = new FileCustomFieldValueModel();
// в результате загрузки файла будет возвращена информация по файлу
// нас интересует свойство file_uuid {@see FileCustomFieldValueModel}
// @link todo тут ссылка на документацию по загрузке файлов
$cfValueModel->setFileUuid('832637d2-54da-4e0b-b1cb-05b70566e3cc');
# Добавляем значение в коллекцию значений
$cfValueCollection->add($cfValueModel);
# Устанавливаем коллекцию в модель
$cfValuesModel->setValues($cfValueCollection);
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

# Удаление поля
///** @var bool $isDeleted */
//$isDeleted = $leadsCfService->deleteOne($fileCfModel);
//var_dump($isDeleted);
