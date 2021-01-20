<?php

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\CompanyModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\TagModel;
use AmoCRM\Models\Unsorted\FormsMetadata;
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

//Представим, что у нас есть данные, полученные из сторонней системы
$externalData = [
    [
        'is_new' => true,
        'price' => 54321,
        'name' => 'Lead name',
        'contact' => [
            'first_name' => 'Ivan',
            'last_name' => 'Zinoviev',
            'phone' => '+79129876543',
        ],
        'company' => [
            'name' => 'Qwerty LLC',
        ],
        'tag' => 'Новый клиент',
        'external_id' => '0752a617-c834-4bde-b4a6-76ff0fe26871',
    ],
    [
        'is_new' => false,
        'price' => 99212,
        'name' => 'Lead 2 name',
        'contact' => [
            'first_name' => 'Masha',
            'last_name' => 'Petrova',
            'phone' => '+79123456789',
        ],
        'company' => [
            'name' => 'Asdfg LLC',
        ],
        'tag' => 'Важный клиент',
        'external_id' => '4268bc22-f568-4689-84ac-7d2df9599c08',
    ],
];

$leadsCollection = new LeadsCollection();

//Создадим модели и заполним ими коллекцию
foreach ($externalData as $externalLead) {
    $lead = (new LeadModel())
        ->setName($externalLead['name'])
        ->setPrice($externalLead['price'])
        ->setTags(
            (new TagsCollection())
                ->add(
                    (new TagModel())
                        ->setName($externalLead['tag'])
                )
        )
        ->setContacts(
            (new ContactsCollection())
                ->add(
                    (new ContactModel())
                        ->setFirstName($externalLead['contact']['first_name'])
                        ->setLastName($externalLead['contact']['last_name'])
                        ->setCustomFieldsValues(
                            (new CustomFieldsValuesCollection())
                                ->add(
                                    (new MultitextCustomFieldValuesModel())
                                        ->setFieldCode('PHONE')
                                        ->setValues(
                                            (new MultitextCustomFieldValueCollection())
                                                ->add(
                                                    (new MultitextCustomFieldValueModel())
                                                        ->setValue($externalLead['contact']['phone'])
                                                )
                                        )
                                )
                        )
                )
        )
        ->setCompany(
            (new CompanyModel())
                ->setName($externalLead['company']['name'])
        )
        ->setRequestId($externalLead['external_id']);

    if ($externalLead['is_new']) {
        $lead->setMetadata(
            (new FormsMetadata())
                ->setFormId('my_best_form')
                ->setFormName('Обратная связь')
                ->setFormPage('https://example.com/form')
                ->setFormSentAt(mktime(date('h'), date('i'), date('s'), date('m'), date('d'), date('Y')))
                ->setReferer('https://google.com/search')
                ->setIp('192.168.0.1')
        );
    }

    $leadsCollection->add($lead);
}

//Создадим сделки
try {
    $addedLeadsCollection = $apiClient->leads()->addComplex($leadsCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

/** @var LeadModel $addedLead */
foreach ($addedLeadsCollection as $addedLead) {
    //Пройдемся по добавленным сделкам и выведем результат
    $leadId = $addedLead->getId();
    $contactId = $addedLead->getContacts()->first()->getId();
    $companyId = $addedLead->getCompany()->getId();

    $externalRequestIds = $addedLead->getComplexRequestIds();
    foreach ($externalRequestIds as $requestId) {
        $action = $addedLead->isMerged() ? 'обновлены' : 'созданы';
        $separator = PHP_SAPI === 'cli' ? PHP_EOL : "<br>";
        echo "Для сущности с ID {$requestId} были {$action}: сделка ({$leadId}), контакт ({$contactId}), компания ({$companyId})" . $separator;
    }
}
