<?php

use AmoCRM\Enum\Invoices\BillStatusEnumCode;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Filters\CatalogsFilter;
use AmoCRM\Models\CustomFieldsValues\LinkedEntityCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\LinkedEntityCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NumericCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\LinkedEntityCustomFieldValueModel;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Enum\InvoicesCustomFieldsEnums;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\CatalogElementModel;
use AmoCRM\Models\CustomFieldsValues\ItemsCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\LegalEntityCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\SelectCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\ItemsCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\LegalEntityCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\SelectCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\ItemsCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\LegalEntityCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\SelectCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use AmoCRM\Models\LeadModel;
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

//Получим каталоги счетов
try {
    $catalogsFilter = new CatalogsFilter();
    $catalogsFilter->setType(EntityTypesInterface::INVOICES_CATALOG_TYPE_STRING);
    $invoicesCatalog = $apiClient->catalogs()->get($catalogsFilter)->first();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Получим счета с ссылками на оплату
try {
    $invoicesCollection = $apiClient
        ->catalogElements($invoicesCatalog->getId())
        ->get(null, [CatalogElementModel::INVOICE_LINK]);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Возьмем первый счет
$invoice = $invoicesCollection->first();

//Получим ссылку на печатную форму счета
if ($invoiceLink = $invoice->getInvoiceLink()) {
    var_dump($invoiceLink);
}

//Получим значения полей
$customFieldValues = $invoice->getCustomFieldsValues();

//Получим значение поля Статус
if ($statusValue = $customFieldValues->getBy('fieldCode', InvoicesCustomFieldsEnums::STATUS)) {
    var_dump($statusValue->getValues()->first()->getValue());

    // обновим статус счета на "Частично оплачен"
    // подробнее о статусах смотрите в examples/custom_field_select_actions.php
    $statusCustomFieldValueModel = new SelectCustomFieldValuesModel();
    $statusCustomFieldValueModel->setFieldCode(InvoicesCustomFieldsEnums::STATUS);
    $statusCustomFieldValueModel->setValues(
        (new SelectCustomFieldValueCollection())
            ->add((new SelectCustomFieldValueModel())->setEnumCode(BillStatusEnumCode::PARTIALLY_PAID))
    );

    $apiClient->catalogElements($invoicesCatalog->getId())->updateOne(
        $invoice->setCustomFieldsValues(
            (new CustomFieldsValuesCollection())->add($statusCustomFieldValueModel)
        )
    );
}
//Получим значение поля Юр. лицо
if ($legalEntityValue = $customFieldValues->getBy('fieldCode', InvoicesCustomFieldsEnums::LEGAL_ENTITY)) {
    var_dump($legalEntityValue->getValues()->first()->getValue());
}
//Получим значение поля Плательщик
if ($payerValue = $customFieldValues->getBy('fieldCode', InvoicesCustomFieldsEnums::PAYER)) {
    var_dump($payerValue->getValues()->first()->getValue());
}
//Получим значение привязанные товары
if ($itemsValue = $customFieldValues->getBy('fieldCode', InvoicesCustomFieldsEnums::ITEMS)) {
    /** @var ItemsCustomFieldValueModel $value */
    foreach ($itemsValue->getValues() as $value) {
        var_dump($value->getValue());
    }
}
//Получим значение поля Комментарий
if ($commentField = $customFieldValues->getBy('fieldCode', InvoicesCustomFieldsEnums::COMMENT)) {
    var_dump($commentField->getValues()->first()->getValue());
}
//Получим значение поля Итоговая сумма к оплате
if ($priceValue = $customFieldValues->getBy('fieldCode', InvoicesCustomFieldsEnums::PRICE)) {
    var_dump($priceValue->getValues()->first()->getValue());
}
//Получим значение поля Тип НДС
if ($vatValue = $customFieldValues->getBy('fieldCode', InvoicesCustomFieldsEnums::VAT_TYPE)) {
    var_dump($vatValue->getValues()->first()->getValue());
}
//Получим значение поля Дата оплаты, имеет значение, только если счет в статусе оплачен
if ($paymentDateValue = $customFieldValues->getBy('fieldCode', InvoicesCustomFieldsEnums::PAYMENT_DATE)) {
    var_dump($paymentDateValue->getValues()->first()->getValue());
}


//Создадим новый счет
//Обязательно должно быть название и заполнено поле статус
$newInvoice = new CatalogElementModel();
//Зададим Имя
$newInvoice->setName('Счет #238');
//Зададим дату создания
$creationDate = new DateTime('2021-05-15 10:00:00');
$newInvoice->setCreatedAt($creationDate->getTimestamp());

$invoiceCustomFieldsValues = new CustomFieldsValuesCollection();
//Зададим статус
$statusCustomFieldValueModel = new SelectCustomFieldValuesModel();
$statusCustomFieldValueModel->setFieldCode(InvoicesCustomFieldsEnums::STATUS);
$statusCustomFieldValueModel->setValues(
    (new SelectCustomFieldValueCollection())
        ->add((new SelectCustomFieldValueModel())->setValue('Оплачен в аванс')) //Текст должен совпадать с одним из значений поля статус
);
$invoiceCustomFieldsValues->add($statusCustomFieldValueModel);
//Зададим комментарий
$commentCustomFieldValueModel = new TextCustomFieldValuesModel();
$commentCustomFieldValueModel->setFieldCode(InvoicesCustomFieldsEnums::COMMENT);
$commentCustomFieldValueModel->setValues(
    (new TextCustomFieldValueCollection())
        ->add((new TextCustomFieldValueModel())->setValue('Текст комментария к счету'))
);
$invoiceCustomFieldsValues->add($commentCustomFieldValueModel);
//Зададим плательщика (до поле связанная сущность, может хранить в себе связь с сущностью (контакт или компания))
$payerCustomFieldValueModel = new LinkedEntityCustomFieldValuesModel();
$payerCustomFieldValueModel->setFieldCode(InvoicesCustomFieldsEnums::PAYER);
$payerCustomFieldValueModel->setValues(
    (new LinkedEntityCustomFieldValueCollection())
        ->add(
            (new LinkedEntityCustomFieldValueModel())
                //->setName('Вася Пупкин') //Можно передать или название сущности, или ID сущности, чтобы заполнить это поле
                ->setEntityId(11014723)
                ->setEntityType(EntityTypesInterface::CONTACTS)
        )
);
$invoiceCustomFieldsValues->add($payerCustomFieldValueModel);
//Зададим юр. лицо, от имени которого выставлен счёт
$legalEntityCustomFieldValueModel = new LegalEntityCustomFieldValuesModel();
$legalEntityCustomFieldValueModel->setFieldCode(InvoicesCustomFieldsEnums::LEGAL_ENTITY);
$legalEntityCustomFieldValueModel->setValues(
    (new LegalEntityCustomFieldValueCollection())
        ->add(
            (new LegalEntityCustomFieldValueModel())
                ->setName('ООО "Рога и копыта"')
                ->setLegalEntityType(LegalEntityCustomFieldValueModel::LEGAL_ENTITY_TYPE_JURIDICAL_PERSON)
                ->setVatId('05124214')
                ->setTaxRegistrationReasonCode('0124125125')
                ->setAddress('Москва, Красная площадь, дом 1')
                ->setKpp('124352279')
                ->setBankCode('023532795')
                ->setExternalUid('125125-4457xcsf-erhery')
        )
);
$invoiceCustomFieldsValues->add($legalEntityCustomFieldValueModel);
//Зададим товары в счете
$itemsCustomFieldValueModel = new ItemsCustomFieldValuesModel();
$itemsCustomFieldValueModel->setFieldCode(InvoicesCustomFieldsEnums::ITEMS);
$itemsCustomFieldValueModel->setValues(
    (new ItemsCustomFieldValueCollection())
        ->add(
            (new ItemsCustomFieldValueModel())
                ->setDescription('Описание товара')
                ->setExternalUid('ID товара во внешней учетной системе')
                //->setProductId('ID товара в списке товаров в amoCRM') //Необязательное поле
                ->setQuantity(10) //количество
                ->setSku('Артикул товара')
                ->setUnitPrice(150) //цена за единицу товара
                ->setUnitType('кг') //единица измерения товара
                ->setVatRateValue(20) //НДС 20%
                ->setDiscount([
                    'type' => ItemsCustomFieldValueModel::FIELD_DISCOUNT_TYPE_AMOUNT, //amount - скидка абсолютная, percentage - скидка в процентах от стоимости товара
                    'value' => 15.15 //15 рублей 15 копеек
                ])
                ->setBonusPointsPerPurchase(20) //Сколько бонусных баллов будет начислено за покупку
        )
);
$invoiceCustomFieldsValues->add($itemsCustomFieldValueModel);
//Зададим значение поля Итоговая сумма к оплате
//Отображается в списке счетов,
//при заходе в карточку счета, стоимость счета будет рассчитана с учетом товаров, ндс и отображена в карточке счета
//Если передать некорректную сумму, то до редактирования в интерфейсе, через API будет возвращаться некорректная сумма
$priceCustomFieldValueModel = new NumericCustomFieldValuesModel();
$priceCustomFieldValueModel->setFieldCode(InvoicesCustomFieldsEnums::PRICE);
$priceCustomFieldValueModel->setValues(
    (new NumericCustomFieldValueCollection())
        ->add(
            (new NumericCustomFieldValueModel())
                ->setValue(100)
        )
);
$invoiceCustomFieldsValues->add($priceCustomFieldValueModel);
//Зададим Тип НДС
$vatTypeCustomFieldValueModel = new SelectCustomFieldValuesModel();
$vatTypeCustomFieldValueModel->setFieldCode(InvoicesCustomFieldsEnums::VAT_TYPE);
$vatTypeCustomFieldValueModel->setValues(
    (new SelectCustomFieldValueCollection())
        ->add((new SelectCustomFieldValueModel())->setValue("НДС начисляется поверх стоимости"))
);
$invoiceCustomFieldsValues->add($vatTypeCustomFieldValueModel);

//Установим значения в модель и сохраним
$newInvoice->setCustomFieldsValues($invoiceCustomFieldsValues);
$catalogElementsService = $apiClient->catalogElements($invoicesCatalog->getId());
try {
    $newInvoice = $catalogElementsService->addOne($newInvoice);
    var_dump('ID счета - ' . $newInvoice->getId());
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Свяжем счет со сделкой с ID 7856057
$leadsService = $apiClient->leads();
$lead = (new LeadModel())
    ->setId(7856057);
try {
    $leadsService->link($lead, (new LinksCollection())->add($newInvoice));
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Обновим статус счета, без изменения других полей
$invoiceForUpdate = (new CatalogElementModel())
    ->setId($newInvoice->getId())
    ->setCatalogId($invoicesCatalog->getId())
    ->setCustomFieldsValues(
        (new CustomFieldsValuesCollection())
            ->add(
                (new SelectCustomFieldValuesModel())
                    ->setFieldCode(InvoicesCustomFieldsEnums::STATUS)
                    ->setValues(
                        (new SelectCustomFieldValueCollection())
                            ->add((new SelectCustomFieldValueModel())->setValue('Оплачен')) //Текст должен совпадать с одним из значений поля статус
                    )
            )
    );

try {
    $updatedInvoice = $catalogElementsService->updateOne($invoiceForUpdate);
    var_dump('ID обновленного счета - ' . $updatedInvoice->getId());
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Получим значения поля статус
$invoicesCfService = $apiClient->customFields(EntityTypesInterface::CATALOGS . ':' . $invoicesCatalog->getId());
try {
    $invoicesCfsCollection = $invoicesCfService->get();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
$invoiceStatusField = $invoicesCfsCollection->getBy('code', InvoicesCustomFieldsEnums::STATUS);
foreach ($invoiceStatusField->getEnums() as $enum) {
    var_dump('Значение поля ' . $enum->getValue() . ' с ID ' . $enum->getId());
}
