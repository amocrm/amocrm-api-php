<?php

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\ContactFilter;
use AmoCRM\Models\ContactModel;
use GuzzleHttp\Exception\ConnectException;
use League\OAuth2\Client\Token\AccessTokenInterface;

include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/token_actions.php';
include_once __DIR__ . '/api_client.php';

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

//Создадим контакт
$contact = new ContactModel();
$contact->setName('Example');

$contactsCollection = new ContactsCollection();
$contactsCollection->add($contact);
try {
    $apiClient->contacts()->add($contactsCollection);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}

//Получим сделку по ID, сделку и првяжем контакт к сделке
try {
    $lead = $apiClient->leads()->getOne(3916883);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}

$links = new LinksCollection();
$links->add($lead);
try {
    $apiClient->contacts()->link($contact, $links);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}

//Создадим фильтр по id контакта
$filter = new ContactFilter();
$filter->setIds([3]);

//Получим сделки по фильтру
try {
    $contacts = $apiClient->contacts()->get($filter);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}

//Обновим все найденные сделки
/** @var ContactModel $contact */
foreach ($contacts as $contact) {
//    //Получим коллекцию значений полей сделки
//    $customFields = $contact->getCustomFieldsValues();
//    /** @var CustomFieldValueModel $textField */
//    //Получем значение поля по его ID
//    $textField = $customFields->getBy('fieldId', 231189);
//    //Если значения нет, то создадим новый объект поля и добавим его в колекцию значенй
//    if (empty($textField)) {
//        $textField = (new CustomFieldValueModel())->setFieldId(231189);
//        $customFields->add($textField);
//    }
//
//    //Установим значение поля
//    $textField->setValues(
//        [
//            [
//                'value' => 'asfasf',
//            ],
//        ]
//    );

    //Установим название
    $contact->setName('Example lead');
}

//Сохраним сделку
try {
    $apiClient->contacts()->update($contacts);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}
