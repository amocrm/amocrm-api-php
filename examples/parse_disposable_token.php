<?php

use AmoCRM\AmoCRM\Exceptions\{
    DisposableTokenExpiredException,
    DisposableTokenVerificationFailedException
};

include_once __DIR__ . '/bootstrap.php';

$accessToken = getToken();

// Как пример, получим заголовки с реквеста
// И получим нужный нам X-Auth-Token
$headers = apache_request_headers();
$token = $headers['X-Auth-Token'];

if ($token === null) {
    echo 'X-Auth-Token not found';
    die;
}

try {
    /**
     * Одноразовый токен для интеграций, для того чтобы его получить используйте
     * метод this.$authorizedAjax() в своей интеграции
     * Подробнее: @link https://www.amocrm.ru/developers/content/web_sdk/mechanics
     *
     * Данный токен должен передаваться в заголовках вместе с запросом на ваш удаленный сервер
     * X-Auth-Token: {disposable_token}
     * Время жизни токена: 30 минут
     *
     * Расшифруем пришедший токен и получим модель с информацией
     * Подробнее: @see DisposableTokenModel
     */
    $disposableTokenModel = $apiClient->getOAuthClient()
        ->parseDisposableToken($token);

    var_dump($disposableTokenModel->toArray());
} catch (DisposableTokenExpiredException $e) {
    // Токен не прошел проверку подписи
    printError($e);
    die;
} catch (DisposableTokenVerificationFailedException $e) {
    // Токен
    printError($e);
    die;
}
