<?php

use AmoCRM\Collections\Leads\SourcesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\Leads\SourceModel;
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
$r = uniqid();
//Создадим источник
$sourcesCollection = new SourcesCollection();
$source = new SourceModel();
$source->setName('New Source');
$source->setExternalId('+7 (912) 123 12 12'.$r);

$sourcesCollection->add($source);
$sourcesService = $apiClient->sources();

try {
    $sourcesService->add($sourcesCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
echo "Added source: ";
var_dump($sourcesCollection->toArray());
echo PHP_EOL;

$sourcesCollection->first()->setName('Source: +7 (912) 123 12 12');

try {
    $sourcesService->update($sourcesCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

try {
    $sourcesCollection = $apiClient->sources()->get();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

var_dump($sourcesCollection->toArray());
