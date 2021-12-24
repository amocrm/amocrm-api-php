<?php

use AmoCRM\Enum\Sources\SourceServiceTypeEnum;
use AmoCRM\Collections\Sources\SourceServicesCollection;
use AmoCRM\Collections\Sources\SourceServicesPagesCollection;
use AmoCRM\Collections\SourcesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\SourceModel;
use AmoCRM\Models\Sources\SourceServiceModel;
use AmoCRM\Models\Sources\SourceServicePageModel;
use League\OAuth2\Client\Token\AccessTokenInterface;

include_once __DIR__ . '/bootstrap.php';

$accessToken = getToken();

$apiClient->setAccessToken($accessToken)
    ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
    ->onAccessTokenRefresh(
        function (AccessTokenInterface $accessToken, string $baseDomain) {
            saveToken(
                [
                    'accessToken'  => $accessToken->getToken(),
                    'refreshToken' => $accessToken->getRefreshToken(),
                    'expires'      => $accessToken->getExpires(),
                    'baseDomain'   => $baseDomain,
                ]
            );
        }
    );
$r = uniqid();
$phoneNumber = '+7 (912) 123 12 12' . $r;
//Создадим источник
$sourcesCollection = new SourcesCollection();
$source = new SourceModel();
$source->setName('New Source');
// Внешний код не обязательно должен быть телефоном,
// просто уникально идентифицируемая строке (ограничения описаны в документации)
$source->setExternalId($phoneNumber);

// если нужно отображать интеграцию в кнопке whatsapp в crm_plugin добавим сервис
$page = new SourceServicePageModel();
$page->setLink('+7912123122');
$page->setName($source->getName());
$page->setId($page->getLink());

$whatsappSourceService = new SourceServiceModel();
$whatsappSourceService->setType(SourceServiceTypeEnum::TYPE_WHATSAPP);
$whatsappSourceService->setPages(SourceServicesPagesCollection::make([$page]));
$source->setServices(SourceServicesCollection::make([$whatsappSourceService]));

$sourcesCollection->add($source);
$sourcesService = $apiClient->sources();

try {
    $sourcesService->add($sourcesCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
echo 'Added source: ';
var_dump($sourcesCollection->toArray());
echo PHP_EOL;

$sourcesCollection->first()->setName('Source: +7 (912) 123 12 12');

try {
    $sourcesService->update($sourcesCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Найдем источник
$sourcesFilter = new \AmoCRM\Filters\SourcesFilter();
$sourcesFilter->setExternalIds((array)$phoneNumber);
try {
    $sourcesCollection = $apiClient->sources()->get($sourcesFilter);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

$source = $sourcesCollection->first();
printf("Updated source: %s, services: %s\n", $source->getName(), json_encode($source->getServices()->toArray()));


//Найдем источник по id
try {
    /** @var SourceModel|null $source */
    $source = $apiClient->sources()->getOne($source->getId());
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

echo 'Source found by Id: ';
var_dump($source->toArray());

$source->setName('Updated-' . $source->getName());
try {
    $source = $apiClient->sources()->updateOne($source);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

echo 'Source found by Id: ';
var_dump($sourcesCollection->toArray());


$isDeleted = false;
try {
    $isDeleted = $apiClient->sources()->deleteOne($source);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

printf("Source %d  is %s\n", $source->getId(), $isDeleted ? 'deleted' : 'not deleted');

try {
    $source = $apiClient->sources()->getOne($source->getId());
} catch (AmoCRMApiException $e) {
    if ($e->getErrorCode() === 204) {
        echo "Really deleted\n";
    } else {
        printError($e);
        die;
    }
}

$sourcesCollection = new SourcesCollection();
$sourceA = new SourceModel();
$sourceA->setName('New SourceA');
$sourceA->setDefault(true);
$sourceA->setExternalId('first-' . uniqid());
$sourcesCollection->add($sourceA);

$sourceB = new SourceModel();
$sourceB->setName('New SourceB ');
$sourceB->setExternalId('second-' . uniqid());
$sourcesCollection->add($sourceB);

try {
    $sourcesService->add($sourcesCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
printf("Sources added: %d, %d\n", $sourceA->getId(), $sourceB->getId());

try {
    $existingSourcesCollection = $apiClient->sources()->get();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
$sourceA = $existingSourcesCollection->getBy('id', $sourceA->getId());
$sourceB = $existingSourcesCollection->getBy('id', $sourceB->getId());

printf("Source A is  %s\n", $sourceA->isDefault() ? 'default' : 'not default');
printf("Source B is  %s\n", $sourceB->isDefault() ? 'default' : 'not default');

$isDeleted = false;
try {
    $isDeleted = $apiClient->sources()->delete($sourcesCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
printf("Sources A & B are %s\n", $isDeleted ? 'deleted' : 'not deleted');


try {
    $existingSourcesCollection = $apiClient->sources()->get();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

$stillExists = array_intersect([$sourceA->getId(), $sourceB->getId()], $existingSourcesCollection->pluck('id'));
printf("Sources A & B are %s\n", empty($stillExists) ? 'really deleted' : 'still exists');
