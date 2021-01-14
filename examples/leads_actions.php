<?php

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Collections\NullTagsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\LeadsFilter;
use AmoCRM\Models\CompanyModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NullCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
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


$leadsService = $apiClient->leads();

//Получим сделки и следующую страницу сделок
try {
    $leadsCollection = $leadsService->get();
    $leadsCollection = $leadsService->nextPage($leadsCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Создадим сделку с заполненым бюджетом и привязанными контактами и компанией
$lead = new LeadModel();
$lead->setName('Название сделки')
    ->setPrice(54321)
    ->setContacts(
        (new ContactsCollection())
            ->add(
                (new ContactModel())
                    ->setId(19346889)
            )
            ->add(
                (new ContactModel())
                    ->setId(19324717)
                    ->setIsMain(true)
            )
    )
    ->setCompany(
        (new CompanyModel())
            ->setId(19187743)
    );

$leadsCollection = new LeadsCollection();
$leadsCollection->add($lead);

try {
    $leadsCollection = $leadsService->add($leadsCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Создадим сделку с заполненым полем типа текст
$lead = new LeadModel();
$leadCustomFieldsValues = new CustomFieldsValuesCollection();
$textCustomFieldValueModel = new TextCustomFieldValuesModel();
$textCustomFieldValueModel->setFieldId(269303);
$textCustomFieldValueModel->setValues(
    (new TextCustomFieldValueCollection())
        ->add((new TextCustomFieldValueModel())->setValue('Текст'))
);
$leadCustomFieldsValues->add($textCustomFieldValueModel);
$lead->setCustomFieldsValues($leadCustomFieldsValues);
$lead->setName('Example');

try {
    $lead = $leadsService->addOne($lead);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Получим контакт по ID, сделку и привяжем контакт к сделке
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
$filter = new LeadsFilter();
$filter->setIds([1, 5170965])
    ->setResponsibleUserId([504141]);

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

    //Получим значение поля по его ID
    if (!empty($customFields)) {
        $textField = $customFields->getBy('fieldId', 269303);
        $textFieldValueCollection = $textField->getValues();
    } else {
        //Если полей нет
        $customFields = new CustomFieldsValuesCollection();
        $textField = (new TextCustomFieldValuesModel())->setFieldId(269303);
        $textFieldValueCollection = (new TextCustomFieldValueCollection());
        $customFields->add($textField);
    }

    $textField->setValues(
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

    $lead->setCustomFieldsValues($customFields);

    //Установим название
    $lead->setName('Example lead');
    //Установим бюджет
    $lead->setPrice(12);
    //Установим нового ответственного пользователя
    $lead->setResponsibleUserId(0);
    //Удалим теги
    $lead->setTags((new NullTagsCollection()));
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
if ($lead->getCatalogElementsLinks()) {
    $element = $lead->getCatalogElementsLinks()->getBy('id', 425909);
//Так как по-умолчанию в связи хранится минимум информации, то вызовем метод syncOne - чтобы засинхронить модель с amoCRM
    $syncedElement = $apiClient->catalogElements()->syncOne($element);

    var_dump($syncedElement);
}
