<?php

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\ContactsFilter;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
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

//Создадим контакт
$contact = new ContactModel();
$contact->setName('Example');

try {
    $contactModel = $apiClient->contacts()->addOne($contact);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

$contactsCollection = new ContactsCollection();
//Создадим несколько контактов
foreach (['Example 1', 'Example 2'] as $name) {
    //Создадим контакт
    $contact = new ContactModel();
    $contact->setName($name);

    $contactsCollection->add($contact);
}
try {
    $contactsCollection = $apiClient->contacts()->add($contactsCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


//Получим сделку по ID, сделку и привяжем контакт к сделке
try {
    $lead = $apiClient->leads()->getOne(3916883);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

$links = new LinksCollection();
$links->add($lead);
try {
    $apiClient->contacts()->link($contactModel, $links);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Получим один контакт и добавим ему значение поля телефон
try {
    $contact = $apiClient->contacts()->getOne(3);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Получим коллекцию значений полей контакта
$customFields = $contact->getCustomFieldsValues();
//Получим значение поля по его коду
$phoneField = $customFields->getBy('fieldCode', 'PHONE');

//Если значения нет, то создадим новый объект поля и добавим его в коллекцию значений
if (empty($phoneField)) {
    $phoneField = (new MultitextCustomFieldValuesModel())->setFieldCode('PHONE');
    $customFields->add($phoneField);
}

//Установим значение поля
$phoneField->setValues(
    (new MultitextCustomFieldValueCollection())
        ->add(
            (new MultitextCustomFieldValueModel())
                ->setEnum('WORKDD')
                ->setValue('+79123')
        )
);

//Установим название
$contact->setName('Example contact');

//Сохраним контакт
try {
    $apiClient->contacts()->updateOne($contact);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


//Создадим фильтр с id контакта (работает только в аккаунтах, подключенных к функционалу фильтрации)
$filter = new ContactsFilter();
$filter->setIds([3]);

//Получим сделки по фильтру
try {
    $contacts = $apiClient->contacts()->get($filter);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Обновим все найденные контакты
/** @var ContactModel $contact */
foreach ($contacts as $contact) {
    //Получим коллекцию значений полей контакта
    $customFields = $contact->getCustomFieldsValues();
    //Получим значение поля по его ID
    $emailField = $customFields->getBy('fieldCode', 'EMAIL');
    //Если значения нет, то создадим новый объект поля и добавим его в коллекцию значений
    if (empty($emailField)) {
        $emailField = (new MultitextCustomFieldValuesModel())->setFieldCode('EMAIL');
        $customFields->add($emailField);
    }

    //Установим значение поля
    $emailField->setValues(
        (new MultitextCustomFieldValueCollection())
            ->add(
                (new MultitextCustomFieldValueModel())
                    ->setEnum('WORK')
                    ->setValue('example@test.com')
            )
    );

    //Установим название
    $contact->setName('Example contact');
}

//Сохраним сделку
try {
    $apiClient->contacts()->update($contacts);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
