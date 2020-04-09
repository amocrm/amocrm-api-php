<?php

use AmoCRM\Collections\CompaniesCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\CompanyFilter;
use AmoCRM\Models\CompanyModel;
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
$company = new CompanyModel();
$company->setName('Example');

$companiesCollection = new CompaniesCollection();
$companiesCollection->add($company);
try {
    $apiClient->companies()->add($companiesCollection);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}

//Получим сделку по ID, сделку и првяжем компанию к сделке
try {
    $lead = $apiClient->leads()->getOne(3916883);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}

$links = new LinksCollection();
$links->add($lead);
try {
    $apiClient->companies()->link($company, $links);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}

//Создадим фильтр по id компании
$filter = new CompanyFilter();
$filter->setIds([1]);

//Получим компании по фильтру
try {
    $companies = $apiClient->companies()->get($filter);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}

var_dump($companies->toArray());
////Обновим все найденные сделки
///** @var LeadModel $lead */
//foreach ($leads as $lead) {
//    //Получим коллекцию значений полей сделки
//    $customFields = $lead->getCustomFieldsValues();
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
//
//    //Установим название
//    $lead->setName('Example lead');
//    //Установим бюджет
//    $lead->setPrice(12);
//    //Установим нового ответственного пользователя
//    $lead->setResponsibleUserId(0);
//}
//
////Сохраним сделку
//try {
//    $apiClient->leads()->update($leads);
//} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
//    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
//    die;
//}
