<?php

use AmoCRM\Enum\Tags\TagColorsEnum;
use AmoCRM\Filters\TagsFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\TagModel;
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

//Создадим тег с цветом (цвета можно задавать только у тегов сделок)
$tagsCollection = new TagsCollection();
$tag = new TagModel();
$tag->setName('новый тег');
$tag->setColor(TagColorsEnum::LAPIS_LAZULI);
$tagsCollection->add($tag);
$tagsService = $apiClient->tags(EntityTypesInterface::LEADS);

try {
    $tagsService->add($tagsCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
$tagsCollection->first()->setId(69079); //убрать, когда будет request_id в тегах

//Найдем тег
$tagsFilter = new TagsFilter();
$tagsFilter->setQuery('новый тег');
try {
    $tagsCollection = $apiClient->tags(EntityTypesInterface::LEADS)->get($tagsFilter);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


//Создадим сделку с найденным тегом
$lead = new LeadModel();
$lead->setName('Example');
$lead->setTags($tagsCollection);

$leadsCollection = new LeadsCollection();
$leadsCollection->add($lead);
$apiClient->leads()->add($leadsCollection);
