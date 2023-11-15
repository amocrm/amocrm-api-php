<?php

declare(strict_types=1);

use AmoCRM\Collections\Chats\Templates\Buttons\ButtonsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\Chats\Templates\Buttons\TextButtonModel;
use AmoCRM\Models\Chats\Templates\ReviewModel;
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
    /** @var TemplateModel $chatTemplate */
    $chatTemplate = $chatTemplatesService->addOne($chatTemplate);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
echo 'Добавленный шаблон: ';
var_dump($chatTemplate->toArray());
echo PHP_EOL;


// Обновим шаблон и добавим в него кнопки. Кнопок разного типа быть не может
// Также сменим тип шаблона на WhatsApp
$buttonsCollection = new ButtonsCollection();
$buttonsCollection
    ->add(
        (new TextButtonModel())->setText('Текст кнопки')
    )
    ->add(
        (new TextButtonModel())->setText('Текст кнопки2')
    );
$chatTemplate->setButtons($buttonsCollection);
$chatTemplate->setType(TemplateModel::TYPE_WABA);
$chatTemplate->setWabaCategory(TemplateModel::CATEGORY_UTILITY);
$chatTemplate->setWabaFooter('Футер шаблона');
$chatTemplate->setWabaExamples(['{{lead.name}}' => 'Заявка из WhatsApp']);
$chatTemplate->setWabaLanguage('ru');


try {
    $chatTemplate = $chatTemplatesService->updateOne($chatTemplate);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

echo 'Обновлённый шаблон: ';
var_dump($chatTemplate->toArray());
echo PHP_EOL;

// Отправим шаблон WhatsApp на проверку
try {
    $reviews = $chatTemplatesService->sendOnReview($chatTemplate);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

echo 'Статусы шаблона: ';
var_dump($reviews->toArray());
echo PHP_EOL;

// Получим шаблоны со статусами
try {
    $chatTemplatesCollection = $chatTemplatesService->get(null, TemplateModel::getAvailableWith());
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
var_dump($chatTemplatesCollection->toArray());

$template = $review = null;

// Найдём шаблон со статусом review
/** @var TemplateModel $chatTemplate */
foreach ($chatTemplatesCollection as $chatTemplate) {
    $templateReviews = $chatTemplate->getReviews();

    if (is_null($templateReviews)) {
        continue;
    }

    /** @var ReviewModel $templateReview */
    foreach ($templateReviews as $templateReview) {
        if ($templateReview->getStatus() === ReviewModel::STATUS_REVIEW_NAME) {
            $review = $templateReview;
            break;
        }
    }

    if ($review) {
        $template = $chatTemplate;
        break;
    }
}

// Если нашли - поставим статус отказ
if ($review && $template) {
    try {
        $review = $chatTemplatesService->updateReviewStatus(
            $template,
            $review->setStatus(ReviewModel::STATUS_REJECTED_NAME)->setRejectReason('Does not fit')
        );
    } catch (AmoCRMApiException $e) {
        printError($e);
        die;
    }

    echo 'Шаблон с отказаом: ';
    var_dump($review->toArray());
    echo PHP_EOL;
}

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
