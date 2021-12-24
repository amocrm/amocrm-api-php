<?php

use AmoCRM\EntitiesServices\Interfaces\HasParentEntity;
use AmoCRM\Filters\NotesFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Collections\NotesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Models\Interfaces\CallInterface;
use AmoCRM\Models\NoteType\CallInNote;
use AmoCRM\Models\NoteType\ServiceMessageNote;
use AmoCRM\Models\NoteType\SmsOutNote;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Ramsey\Uuid\Uuid;

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

//Создадим примечания
$notesCollection = new NotesCollection();
$serviceMessageNote = new ServiceMessageNote();
$serviceMessageNote->setEntityId(1)
    ->setText('Текст примечания')
    ->setService('Api Library')
    ->setCreatedBy(0);

$callInNote = new CallInNote();
$callInNote->setEntityId(1)
    ->setPhone('+7912312321')
    ->setCallStatus(CallInterface::CALL_STATUS_SUCCESS_CONVERSATION)
    ->setCallResult('Разговор состоялся')
    ->setDuration(148)
    ->setUniq(Uuid::uuid4())
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
    $leadNotesService = $apiClient->notes(EntityTypesInterface::LEADS);
    $notesCollection = $leadNotesService->add($notesCollection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Получение примечаний конкретной сущности с фильтром по типу примечания
try {
    $notesCollection = $leadNotesService->getByParentId(1, (new NotesFilter())->setNoteTypes([NoteFactory::NOTE_TYPE_CODE_CALL_IN]));
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Получение примечаний
try {
    $notesCollection = $leadNotesService->get();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

//Получение одного примечания, в метод getOne необходимо передать массив,
//так как для получения конкретного примечания нужно знать и id сущности и id примечания
try {
    $noteModel = $leadNotesService->getOne(
        [
            HasParentEntity::ID_KEY => 9,
            HasParentEntity::PARENT_ID_KEY => 1,
        ]
    );
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

var_dump($noteModel->toArray());
