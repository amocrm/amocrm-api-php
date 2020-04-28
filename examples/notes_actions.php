<?php

use AmoCRM\AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Collections\NotesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\NoteType\CallInNote;
use AmoCRM\Models\NoteType\CallNote;
use AmoCRM\Models\NoteType\ServiceMessageNote;
use AmoCRM\Models\NoteType\SmsOutNote;
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

//Создадим примечания
$notesCollection = new NotesCollection();
$serviceMessageNote = new ServiceMessageNote();
$serviceMessageNote->setEntityId(1)
    ->setText('Текст прмечания')
    ->setService('Api Library')
    ->setCreatedBy(0);

$callInNote = new CallInNote();
$callInNote->setEntityId(1)
    ->setPhone('+7912312321')
    ->setCallStatus(CallNote::CALL_STATUS_SUCCESS_CONVERSATION)
    ->setCallResult('Разговор состоялся')
    ->setDuration(148)
    ->setUniq(\Ramsey\Uuid\Uuid::uuid4())
    ->setSource('integration name')
    ->setLink('https://example.test/test.mp3');

$smsNote = new SmsOutNote();
$smsNote->setEntityId(1)
    ->setText('Исходящее SMS')
    ->setPhone('+7912312321')
    ->setCreatedBy(0);

$notesCollection->add($serviceMessageNote);
$notesCollection->add($smsNote);
$notesCollection->add($callInNote);

try {
    $notesCollection = $apiClient->notes(EntityTypesInterface::LEADS)->add($notesCollection);
} catch (AmoCRMApiException | AmoCRMoAuthApiException | ConnectException $e) {
    echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode() . $e->getTitle();
    die;
}
var_dump($notesCollection->toArray()); die;