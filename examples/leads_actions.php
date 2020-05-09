<?php

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\LeadFilter;
use AmoCRM\Models\CustomFieldValueModel;
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


//try {
//    $service = $apiClient->leads();
//    $q = $service->get();
//    $q = $service->nextPage($q);
//} catch (AmoCRMApiException $e) {
//    printError($e);
//    die;
//}
//
//die;
//Создадим сделку
$lead = new LeadModel();
$lead->setName('Example');

$leadsCollection = new LeadsCollection();
$leadsCollection->add($lead);
try {
    $apiClient->leads()->add($leadsCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


//Получим контакт по ID, сделку и првяжем контакт к сделке
try {
    $contact = $apiClient->contacts()->getOne(7143559);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

$links = new LinksCollection();
$links->add($contact);
try {
    $apiClient->leads()->link($lead, $links);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


//Создадим фильтр по id сделки и ответственному пользователю
$filter = new LeadFilter();
$filter->setIds([1, 5170965])
    ->setResponsibleUserIds([504141]);

//Получим сделки по фильтру и с полем with=is_price_modified_by_robot,loss_reason,contacts
try {
    $leads = $apiClient->leads()->get($filter, [LeadModel::IS_PRICE_BY_ROBOT, LeadModel::LOSS_REASON, LeadModel::CONTACTS]);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


//Обновим все найденные сделки
/** @var LeadModel $lead */
foreach ($leads as $lead) {
    //Получим коллекцию значений полей сделки
    $customFields = $lead->getCustomFieldsValues();
    /** @var CustomFieldValueModel $textField */
    //Получем значение поля по его ID
    if (!empty($customFields)) {
        $textField = $customFields->getBy('fieldId', 231189);
    } else {
        $customFields = new CustomFieldsValuesCollection();
    }
    //Если значения нет, то создадим новый объект поля и добавим его в колекцию значенй
    if (empty($textField)) {
        $textField = (new CustomFieldValueModel())->setFieldId(231189);
        $customFields->add($textField);
    }

    //Установим значение поля
    $textField->setValues(
        [
            [
                'value' => 'asfasf',
            ],
        ]
    );

    //Установим название
    $lead->setName('Example lead');
    //Установим бюджет
    $lead->setPrice(12);
    //Установим нового ответственного пользователя
    $lead->setResponsibleUserId(0);
}

//Сохраним сделку
try {
    $apiClient->leads()->update($leads);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


//Получим сделку
try {
    $lead = $apiClient->leads()->getOne(1, [LeadModel::CONTACTS, LeadModel::CATALOG_ELEMENTS]);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Получим основной контакт сделки
/** @var ContactsCollection $leadContacts */
$leadContacts = $lead->getContacts();
if ($leadContacts) {
    $leadMainContact = $leadContacts->getBy('isMain', true);
}

//Получим элемент, прикрепленный к сделке по его ID
$element = $lead->getCatalogElementsLinks()->getBy('id', 425909);
//Так как по-умолчанию в связи хранится минимум информации, то вызовем метод syncOne - чтобы засинхронить модель с amoCRM
$syncedElement = $apiClient->catalogElements()->syncOne($element);

var_dump($syncedElement);
die;
