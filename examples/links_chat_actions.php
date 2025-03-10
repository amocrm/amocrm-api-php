<?php

use AmoCRM\Collections\ChatLinksCollection;
use AmoCRM\Models\ChatLinkModel;
use AmoCRM\Exceptions\AmoCRMApiException;
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

// Массив с ID контактов и uuid идентификаторов чатов
$data = [
    [
        'chat_id' => 'd7a2842c-d926-43bc-a090-21ab410b8acb',
        'contact_id' => 18738957
    ],
    [
        'chat_id' => '61aca544-27dd-4601-9f9e-67e3cbbba2e0',
        'contact_id' => 18742731
    ]
];

$chatLinksCollection = new ChatLinksCollection();

//Создадим модели и заполним ими коллекцию
foreach ($data as $linksChat) {
    $linkChat = (new ChatLinkModel())
            ->setContactId($linksChat['contact_id'])
            ->setChatId($linksChat['chat_id']);

    $chatLinksCollection->add($linkChat);
}

// Привязываем чат к контакту
try {
    $linksChatCollection = $apiClient->contacts()->linkChats($chatLinksCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

/** @var ChatLinkModel $link */
foreach ($linksChatCollection as $linkChat) {
    $contactId = $linkChat->getContactId();
    $chatId = $linkChat->getChatId();

    echo "Для сущности с ID {$contactId} был привязан чат {$chatId}"  . PHP_EOL;
}
