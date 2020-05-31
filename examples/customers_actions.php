<?php

use AmoCRM\Collections\Customers\Segments\SegmentsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\CustomersFilter;
use AmoCRM\Models\Customers\CustomerModel;
use AmoCRM\Models\Customers\Segments\SegmentModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NullCustomFieldValueCollection;
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

$customersService = $apiClient->customers();
$contactsService = $apiClient->contacts();

//Создадим покупателя
$customer = new CustomerModel();
$customer->setName('Example');

try {
    $customer = $customersService->addOne($customer);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Привяжем контакт к созданному покупателю
try {
    $contact = $contactsService->getOne(9567095);
    $contact->setIsMain(false);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

$links = new LinksCollection();
$links->add($contact);
try {
    $customersService->link($customer, $links);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Создадим фильтр по id покупателя
$filter = new CustomersFilter();
$filter->setIds([1]);

//Получим покупателя по фильтру
try {
    $customers = $customersService->get($filter);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Обновим всех найденных покупателей
/** @var CustomerModel $customer */
foreach ($customers as $customer) {
    //Получим коллекцию значений полей покупателя
    $customFields = $customer->getCustomFieldsValues();

    //Получим значение поля по его ID
    if (!empty($customFields)) {
        $textField = $customFields->getBy('fieldId', 56446);
        $textFieldValues = $textField->getValues();
    } else {
        $textField = new CustomFieldsValuesCollection();
    }

    //Если значения нет, то создадим новый объект поля и добавим его в коллекцию значений
    if (empty($textFieldValues)) {
        $textFieldValues = (new TextCustomFieldValuesModel())->setFieldId(56446);
        $textField->add($textFieldValues);
    }

    //Установим значение поля
    $textFieldValues->setValues(
        (new TextCustomFieldValueCollection())
            ->add(
                (new TextCustomFieldValueModel())
                    ->setValue('новое значение')
            )
    );

    //Или удалим значение поля
    $textField->setValues(
        (new NullCustomFieldValueCollection())
    );

    //Установим название
    $customer->setName('Example customer');

    //Установим сегмент
    $customer->setSegments(
        (new SegmentsCollection())
            ->add(
                (new SegmentModel())
                    ->setId(38)
            )
    );
}

//Сохраним покупателей
try {
    $customersService->update($customers);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
