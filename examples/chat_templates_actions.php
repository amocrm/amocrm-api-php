<?php

declare(strict_types=1);

use AmoCRM\Collections\Chats\Templates\Buttons\ButtonsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\Chats\Templates\Buttons\TextButtonModel;
use AmoCRM\Models\Chats\Templates\TemplateModel;
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


$chatTemplatesService = $apiClient->chatTemplates();

// Создадим редактируемый шаблон
$chatTemplate = new TemplateModel();
$chatTemplate
    ->setName('Название шаблона')
    ->setContent('Название сделки - {{lead.name}}')
    ->setExternalId('qwedsgfsdg-dsgsdg') //Идентификатор шаблона на стороне интеграции
    ->setIsEditable(true);

try {
    $chatTemplate = $chatTemplatesService->addOne($chatTemplate);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
echo 'Добавленный шаблон: ';
var_dump($chatTemplate->toArray());
echo PHP_EOL;


// Обновим шаблон и добавим в него кнопки. Кнопок разного типа быть не может
$buttonsCollection = new ButtonsCollection();
$buttonsCollection
    ->add(
        (new TextButtonModel())->setText('Текст кнопки')
    )
    ->add(
        (new TextButtonModel())->setText('Текст кнопки2')
    );
$chatTemplate->setButtons($buttonsCollection);


try {
    $chatTemplatesService->updateOne($chatTemplate);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


// Получим шаблоны
try {
    $chatTemplatesCollection = $chatTemplatesService->get();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
var_dump($chatTemplatesCollection->toArray());


// Получим шаблоны по ExternalId
$templatesFilter = new \AmoCRM\Filters\Chats\TemplatesFilter();
$templatesFilter->setExternalIds(['qwedsgfsdg-dsgsdg']);
try {
    $chatTemplate = $chatTemplatesService->get($templatesFilter)->first();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
var_dump($chatTemplate->toArray());

// Удалим первый шаблон
try {
    $chatTemplatesService->deleteOne($chatTemplate);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
