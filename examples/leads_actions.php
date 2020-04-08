<?php

use AmoCRM\Collections\LeadsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\LeadFilter;
use AmoCRM\Models\CustomFieldValueModel;
use AmoCRM\Models\LeadModel;
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
        });

//Создадим сделку
$lead = new LeadModel();
$lead->setName('Example');

$leadsCollection = new LeadsCollection();
$leadsCollection->add($lead);
try {
    $apiClient->leads()->add($leadsCollection);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}

//Получим контакт по ID, сделку и првяжем контакт к сделке
try {
    $contact = $apiClient->contacts()->getOne(7143559);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}

$links = new LinksCollection();
$links->add($contact);
try {
    $apiClient->leads()->link($lead, $links);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}

//Создадим фильтр по id сделки и ответственному пользователю
$filter = new LeadFilter();
$filter->setIds([1])
    ->setResponsibleUserIds([504141]);

//Получим сделки по фильтру и с полем with=is_price_modified_by_robot
try {
    $leads = $apiClient->leads()->get($filter, [LeadModel::IS_PRICE_BY_ROBOT]);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}

//Обновим все найденные сделки
/** @var LeadModel $lead */
foreach ($leads as $lead) {
    //Получим коллекцию значений полей сделки
    $customFields = $lead->getCustomFieldsValues();
    /** @var CustomFieldValueModel $textField */
    //Получем значение поля по его ID
    $textField = $customFields->getBy('fieldId', 231189);
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
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}

