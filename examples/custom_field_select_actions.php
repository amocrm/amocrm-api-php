<?php

/** @since Release Spring 2022 */

use AmoCRM\Enum\Invoices\BillStatusEnumCode;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\CustomFields\CustomFieldEnumsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Enum\InvoicesCustomFieldsEnums;
use AmoCRM\Filters\CatalogsFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\AccountModel;
use AmoCRM\Models\CatalogElementModel;
use AmoCRM\Models\Customers\CustomerModel;
use AmoCRM\Models\CustomFields\EnumModel;
use AmoCRM\Models\CustomFields\SelectCustomFieldModel;
use AmoCRM\Models\CustomFieldsValues\SelectCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\SelectCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\SelectCustomFieldValueModel;
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


// покупатели
$customers = $apiClient->customers();
// включаем покупателей
$customers->setMode(AccountModel::SEGMENTS);
// каталог "счета/покупки"
$invoices = $apiClient->catalogs()->get(
    (new CatalogsFilter())->setType(EntityTypesInterface::INVOICES_CATALOG_TYPE_STRING)
)->first();

// создание поле select для покупателей с указанием enums - enum-code
$customersSelectField = $apiClient->customFields(EntityTypesInterface::CUSTOMERS)->addOne(makeSelectField());
var_dump($customersSelectField);

// получение полей
$customersCustomFields = $apiClient->customFields(EntityTypesInterface::CUSTOMERS)->get();
var_dump($customersCustomFields);

// создание покупателя
$customerForCreate = new CustomerModel();
$customerForCreate->setName('Test customer');
$customerCustomFieldsValues = new CustomFieldsValuesCollection();
// указываем "Второй вариант" по коду энама
$variant = (new SelectCustomFieldValuesModel())
    ->setFieldId($customersSelectField->getId())
    ->setValues((new SelectCustomFieldValueCollection())->add(
        (new SelectCustomFieldValueModel())->setEnumCode('second')
    ));
$customerCustomFieldsValues->add($variant);
$customerForCreate->setCustomFieldsValues($customerCustomFieldsValues);
$createdCustomer = $apiClient->customers()->addOne($customerForCreate);
var_dump($createdCustomer);


// создаем счет в статусе "Создан"
$invoicesCustomFields = $apiClient->customFields(
    EntityTypesInterface::CATALOGS . ':' . $invoices->getId()
)->get();
// получим поле "Статус" по его коду
$statusField = $invoicesCustomFields->getBy('code', InvoicesCustomFieldsEnums::STATUS);
var_dump($statusField);

$invoiceModelForCreate = new CatalogElementModel();
$invoiceModelForCreate->setName('Test invoice');
$invoiceModelForCreate->setCatalogId($invoices->getId());
$invoicesCustomFieldsValues = new CustomFieldsValuesCollection();
// Указываем статус "Создан"
$statusFieldValue = (new SelectCustomFieldValuesModel())
    ->setFieldCode(InvoicesCustomFieldsEnums::STATUS)
    ->setValues((new SelectCustomFieldValueCollection())->add(
        (new SelectCustomFieldValueModel())->setEnumCode(BillStatusEnumCode::CREATED)
    ));
$invoicesCustomFieldsValues->add($statusFieldValue);
$invoiceModelForCreate->setCustomFieldsValues($invoicesCustomFieldsValues);
// создаем счет
$createdInvoice = $apiClient->catalogElements($invoices->getId())->addOne($invoiceModelForCreate);
var_dump($createdInvoice);

// еще раз получим наш созданный счет
$createdInvoice = $apiClient->catalogElements($invoices->getId())->getOne($createdInvoice->getId());
var_dump($createdInvoice);

$invoicesCustomFieldsValues = new CustomFieldsValuesCollection();
// Указываем статус "Оплачен"
$statusFieldValue = (new SelectCustomFieldValuesModel())
    ->setFieldCode(InvoicesCustomFieldsEnums::STATUS)
    ->setValues((new SelectCustomFieldValueCollection())->add(
        (new SelectCustomFieldValueModel())->setEnumCode(BillStatusEnumCode::PAID)
    ));
$invoicesCustomFieldsValues->add($statusFieldValue);
$createdInvoice->setCustomFieldsValues($invoicesCustomFieldsValues);
// обновляем счет на статус "Оплачен"
$updatedInvoice = $apiClient->catalogElements($invoices->getId())->updateOne($createdInvoice);
var_dump($updatedInvoice);

/**
 * Сборка поля типа "список" {@see \AmoCRM\Models\CustomFields\CustomFieldModel::TYPE_SELECT}
 *
 * @return SelectCustomFieldModel
 */
function makeSelectField(): SelectCustomFieldModel
{
    $model = new SelectCustomFieldModel();
    $model->setName('Test select field with enums codes');

    $enums = new CustomFieldEnumsCollection();

    $first = new EnumModel();
    $first->setSort(1);
    $first->setCode('first');
    $first->setValue('Первый вариант');
    $enums->add($first);

    $second = clone $first;
    $second->setSort(2);
    $second->setCode('second');
    $second->setValue('Второй вариант');
    $enums->add($second);

    $model->setEnums($enums);

    return $model;
}
