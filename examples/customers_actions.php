<?php

use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
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
////Создадим покупателя
//$customer = new CustomerModel();
//$customer->setName('Example');
//
//$customersCollection = new CustomersCollection();
//$customersCollection->add($customer);
//try {
//    $apiClient->customers()->add($customersCollection);
//} catch (AmoCRMApiException $e) {
//    printError($e);
//    die;
//}
//
//die;

//Получим покупателя по ID и привяжем контакт
try {
    $customer = $customersService->getOne(1);
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

//
//
//$links = new LinksCollection();
//$links->add($customers);
//try {
//    $apiClient->contacts()->link($contact, $links);
//} catch (AmoCRMApiException $e) {
//    printError($e);
//    die;
//}
//
////Создадим фильтр по id контакта
//$filter = new ContactFilter();
//$filter->setIds([3]);
//
////Получим сделки по фильтру
//try {
//    $contacts = $apiClient->contacts()->get($filter);
//} catch (AmoCRMApiException $e) {
//    printError($e);
//    die;
//}
//
////Обновим все найденные сделки
///** @var ContactModel $contact */
//foreach ($contacts as $contact) {
////    //Получим коллекцию значений полей сделки
////    $customFields = $contact->getCustomFieldsValues();
////    /** @var CustomFieldValueModel $textField */
////    //Получем значение поля по его ID
////    $textField = $customFields->getBy('fieldId', 231189);
////    //Если значения нет, то создадим новый объект поля и добавим его в колекцию значенй
////    if (empty($textField)) {
////        $textField = (new CustomFieldValueModel())->setFieldId(231189);
////        $customFields->add($textField);
////    }
////
////    //Установим значение поля
////    $textField->setValues(
////        [
////            [
////                'value' => 'asfasf',
////            ],
////        ]
////    );
//
//    //Установим название
//    $contact->setName('Example contact');
//}
//
////Сохраним сделку
//try {
//    $apiClient->contacts()->update($contacts);
//} catch (AmoCRMApiException $e) {
//    printError($e);
//    die;
//}
