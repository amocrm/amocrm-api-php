<?php

use AmoCRM\Collections\FileLinksCollection;
use AmoCRM\Models\FileLinkModel;
use AmoCRM\Models\Files\FileModel;
use AmoCRM\Models\Files\FileUploadModel;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Enum\Chats\Templates\Attachment\TypesEnum;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\Chats\Templates\AttachmentModel;
use AmoCRM\Models\CustomFieldsValues\FileCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\FileCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\FileCustomFieldValueModel;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\NoteType\AttachmentNote;
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


//Получим список файлов
try {
    $files = $apiClient->files()->get();
    var_dump($files->toArray());
} catch (AmoCRMApiException $e) {
    printError($e);
}


//Получим модель файла
try {
    $file = $apiClient->files()->getOne('83bd6974-d54e-4fed-9fec-a3e5a31220db');
    var_dump($file->toArray());
} catch (AmoCRMApiException $e) {
    printError($e);
}


//Освежим модель файла
try {
    $file = (new FileModel())->setUuid('6639ccbf-4bc6-4a08-8537-65adb868967d');
    $file = $apiClient->files()->syncOne($file);
    var_dump($file->toArray());
} catch (AmoCRMApiException $e) {
    printError($e);
}


// Изменим имя у найденного файла
try {
    $file->setName('Новое название файла');
    $file = $apiClient->files()->updateOne($file);
    var_dump($file->toArray());
} catch (AmoCRMApiException $e) {
    printError($e);
}


//Загрузим файл
$uploadModel = new FileUploadModel();
$uploadModel->setName('Название файла123.txt')
    ->setLocalPath('/tmp/123');

try {
    $file = $apiClient->files()->uploadOne($uploadModel);
} catch (AmoCRMApiException $e) {
    printError($e);
}


// Установим файл в поле сделки загруженный файл
$leadsService = $apiClient->leads();

$lead = new LeadModel();
$lead->setName('Название сделки')
    ->setPrice(54321)
    ->setCustomFieldsValues(
        (new CustomFieldsValuesCollection())
            ->add(
                (new FileCustomFieldValuesModel())
                    ->setFieldId(1154281)
                    ->setValues(
                        (new FileCustomFieldValueCollection())
                            ->add(
                                (new FileCustomFieldValueModel())
                                    ->setFileUuid($file->getUuid())
                                    ->setVersionUuid($file->getVersionUuid()) // Можно не передавать, тогда будет использована последняя версия
                                    ->setFileName($file->getName()) // Можно не передавать, тогда название в интерфейсе отобразиться с небольшой задержкой
                                    ->setFileSize($file->getSize()) // Можно не передавать, тогда размер в интерфейсе отобразиться с небольшой задержкой
                            ) // Для того, чтобы заменить файл, обновлять поле не нужно, нужно загрузить файл с передачей file_uuid
                    )
            )
    );

try {
    $lead = $leadsService->addOne($lead);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


// Создадим шаблон сообщений с файлом
$chatTemplatesService = $apiClient->chatTemplates();

$chatTemplate = new \AmoCRM\Models\Chats\Templates\TemplateModel();
$chatTemplate
    ->setName('Название шаблона1')
    ->setContent('Название сделки - {{lead.name}}')
    ->setExternalId('qwedsgfsdg-dsgsdg') //Идентификатор шаблона на стороне интеграции
    ->setIsEditable(true)
    ->setAttachment(
        (new AttachmentModel())
            ->setName($file->getName())
            ->setId($file->getUuid())
            ->setType(TypesEnum::TYPE_PICTURE)
    );

try {
    $chatTemplate = $chatTemplatesService->addOne($chatTemplate);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


//Создадим примечание с файлом
$noteModel = new AttachmentNote();
$noteModel->setEntityId(20285255)
    ->setFileName($file->getNameWithExtension()) // название файла, которое будет отображаться в примечании
    ->setVersionUuid($file->getVersionUuid())
    ->setFileUuid($file->getUuid());

try {
    $leadNotesService = $apiClient->notes(EntityTypesInterface::LEADS);
    $noteModel = $leadNotesService->addOne($noteModel);
    var_dump($noteModel->toArray());
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}


//Удалим файл
//try {
//    $result = $apiClient->files()->deleteOne($file);
//    var_dump($result);
//} catch (AmoCRMApiException $e) {
//    printError($e);
//}


//Привяжем файл к сделке с ID 21825653, чтобы он отображался во вкладке файлы
try {
    $result = $apiClient->entityFiles(EntityTypesInterface::LEADS, 21825653)->add(
        (new FileLinksCollection())
            ->add(
                (new FileLinkModel())
                    ->setFileUuid($file->getUuid())
            )
    );
    var_dump($result);
} catch (AmoCRMApiException $e) {
    printError($e);
}

// Получим все файлы, связанные со сделкой 21825653
try {
    $result = $apiClient->entityFiles(EntityTypesInterface::LEADS, 21825653)->get();
    var_dump($result);
} catch (AmoCRMApiException $e) {
    printError($e);
}

//Отвяжем файл от сделки с ID 21825653
try {
    $result = $apiClient->entityFiles(EntityTypesInterface::LEADS, 21825653)->delete(
        (new FileLinksCollection())
            ->add(
                (new FileLinkModel())
                    ->setFileUuid($file->getUuid())
            )
    );
    var_dump($result);
} catch (AmoCRMApiException $e) {
    printError($e);
}
