<?php

use AmoCRM\Filters\BaseRangeFilter;
use AmoCRM\Filters\Interfaces\HasOrderInterface;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\Factories\UnsortedModelFactory;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\Leads\Unsorted\FormsUnsortedCollection;
use AmoCRM\Collections\Leads\Unsorted\SipUnsortedCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\UnsortedFilter;
use AmoCRM\Filters\UnsortedSummaryFilter;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\Unsorted\BaseUnsortedModel;
use AmoCRM\Models\Unsorted\FormsMetadata;
use AmoCRM\Models\Unsorted\FormUnsortedModel;
use AmoCRM\Models\Unsorted\SipMetadata;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Ramsey\Uuid\Uuid;

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

//Добавим звонок в неразобранное
$sipUnsortedCollection = new SipUnsortedCollection();
$sipUnsorted = UnsortedModelFactory::createForCategory(BaseUnsortedModel::CATEGORY_CODE_SIP);
$sipMetadata = new SipMetadata();
$sipMetadata
    ->setServiceCode('my_best_telephony')
    ->setLink('https://example.com/example.mp3')
    ->setDuration(135)
    ->setCalledAt(mktime(date('h'), date('i'), date('s'), 10, 04, 2019))
    ->setPhone('135')
    ->setFrom('+79123456789')
    ->setUniq(Uuid::uuid4())
    ->setIsCallEventNeeded(true); // нужно ли примечание о звонке

$unsortedLead = new LeadModel();
$unsortedLead->setName('Сделка')
    ->setPrice(500000);

$unsortedContactsCollection = new ContactsCollection();
$unsortedContact = new ContactModel();
$unsortedContact->setName('Контакт');
$unsortedContactsCollection->add($unsortedContact);

$sipUnsorted
    ->setSourceName('Название источника')
    ->setSourceUid('my_unique_uid')
    ->setCreatedAt(time())
    ->setMetadata($sipMetadata)
    ->setLead($unsortedLead)
    ->setPipelineId(3166396)
    ->setContacts($unsortedContactsCollection);

$sipUnsortedCollection->add($sipUnsorted);

$unsortedService = $apiClient->unsorted();
try {
    $sipUnsortedCollection = $unsortedService->add($sipUnsortedCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Добавим в неразобранное форму
$formsUnsortedCollection = new FormsUnsortedCollection();
$formUnsorted = new FormUnsortedModel();
$formMetadata = new FormsMetadata();
$formMetadata
    ->setFormId('my_best_form')
    ->setFormName('Обратная связь')
    ->setFormPage('https://example.com/form')
    ->setFormSentAt(mktime(date('h'), date('i'), date('s'), 10, 04, 2019))
    ->setReferer('https://google.com/search')
    ->setIp('192.168.0.1');

$unsortedLead = new LeadModel();
$unsortedLead->setName('Сделка')
    ->setPrice(500000);

$unsortedContactsCollection = new ContactsCollection();
$unsortedContact = new ContactModel();
$unsortedContact->setName('Контакт');
$contactCustomFields = new CustomFieldsValuesCollection();
$phoneFieldValueModel = new MultitextCustomFieldValuesModel();
$phoneFieldValueModel->setFieldCode('PHONE');
$phoneFieldValueModel->setValues(
    (new MultitextCustomFieldValueCollection())
        ->add((new MultitextCustomFieldValueModel())->setValue('+79123456789'))
);
$unsortedContact->setCustomFieldsValues($contactCustomFields->add($phoneFieldValueModel));
$unsortedContactsCollection->add($unsortedContact);

$formUnsorted
    ->setSourceName('Название источника')
    ->setSourceUid('my_unique_uid')
    ->setCreatedAt(time())
    ->setMetadata($formMetadata)
    ->setLead($unsortedLead)
    ->setPipelineId(3166396)
    ->setContacts($unsortedContactsCollection);

$formsUnsortedCollection->add($formUnsorted);

try {
    $formsUnsortedCollection = $unsortedService->add($formsUnsortedCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
$formUnsorted = $formsUnsortedCollection->first();

try {
    $unsortedFiler = new UnsortedFilter();
    $unsortedFiler
        ->setCategory([BaseUnsortedModel::CATEGORY_CODE_FORMS,  BaseUnsortedModel::CATEGORY_CODE_SIP])
        ->setOrder('created_at', HasOrderInterface::SORT_ASC);
    $unsortedCollection = $unsortedService->get($unsortedFiler);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//первое неразобранное примем
$unsortedModel = $unsortedCollection->first();
try {
    $acceptResult = $unsortedService->accept($unsortedModel);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//получим ID сделки, которая была принята
$leadId = $acceptResult->getLeads()->first()->getId();

//последнее отклоним
$unsortedModel = $unsortedCollection->last();
try {
    $declineResult = $unsortedService->decline($unsortedModel);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Получим статистику по неразобранному
try {
    $unsortedSummaryFilter = new UnsortedSummaryFilter();
    $unsortedSummaryFilter
        ->setCreatedAt((new BaseRangeFilter())
            ->setFrom(time() - 10 * 24 * 60 * 60)
            ->setTo(time()));
    $unsortedSummary = $unsortedService->summary($unsortedSummaryFilter);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Привяжем неразобранное к сделке/контакту/покупателю
//Доступны только неразобранные типа chat/mail
try {
    $unsortedFiler = new UnsortedFilter();
    $unsortedFiler
        ->setCategory(BaseUnsortedModel::CATEGORY_CODE_CHATS);
    $unsortedCollection = $unsortedService->get($unsortedFiler);

    $body = [
        'link' => [
            'entity_type' => EntityTypesInterface::LEADS,
            'entity_id' => 3921175,
        ],
        'user_id' => 0,
    ];
    $result = $unsortedService->link($unsortedCollection->first(), $body);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
