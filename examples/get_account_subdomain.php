<?php

use AmoCRM\Exceptions\AmoCRMApiException;

include_once __DIR__ . '/bootstrap.php';

$accessToken = getToken();

try {
    /**
     * В случае потери/смены субдомена аккаунта, мы можем его получить с помощью активного refresh_token.
     *
     * Получить субдомен можно сделав запрос на {api_domain}.amocrm.ru/oauth2/account/current/subdomain.
     * Получить api_domain можно распарсив JWT access_token и взять его из claim api_domain
     *
     * В данном примере получим модель с информацией о домене аккаунта по refresh_token
     * Подробнее: @see AccountDomainModel
     *
     * Запрос уходит на {api_domain}.amocrm.ru/oauth2/account/current/subdomain
     * С заголовком X-Refresh-Token: {refresh_token}
     *
     * @example curl 'https://api-a.amocrm.ru/oauth2/account/current/subdomain' -H 'X-Refresh-Token: XXX'
     */
    $accountDomainModel = $apiClient->getOAuthClient()
        ->getAccountDomainByRefreshToken($accessToken);

    var_dump($accountDomainModel->toArray());

    // Возьмём из полученной модели текущий subdomain аккаунта и засетим наш субдомен аккаунта в апи клиенте
    $apiClient->setAccountBaseDomain($accountDomainModel->getDomain());

    var_dump($apiClient->getAccountBaseDomain());
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
