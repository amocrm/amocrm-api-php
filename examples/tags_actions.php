<?php

use AmoCRM\AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Collections\LeadsCollection;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\Tag;
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

//Создадим тег
$tagsCollection = new TagsCollection();
$tag = new Tag();
$tag->setName('новый тег');
$tagsCollection->add($tag);
$tagsService = $apiClient->tags()->setEntityType(EntityTypesInterface::LEADS);

try {
    $tagsService->add($tagsCollection);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}
$tagsCollection->first()->setId(69079); //убрать, когда будет request_id в тегах

//Найдем тег
$tagsFilter = new \AmoCRM\Filters\TagFilter();
$tagsFilter->setSearch('новый тег');
try {
    /** @var TagsCollection $tagsCollection */
    $tagsCollection = $apiClient->tags()->setEntityType(EntityTypesInterface::LEADS)->get($tagsFilter);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
    die;
}

//Создадим сделку с найденным тегом
$lead = new LeadModel();
$lead->setName('Example');
$lead->setTags($tagsCollection);

$leadsCollection = new LeadsCollection();
$leadsCollection->add($lead);
$apiClient->leads()->add($leadsCollection);
