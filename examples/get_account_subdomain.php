<?php

use AmoCRM\Exceptions\AmoCRMApiException;

include_once __DIR__ . '/bootstrap.php';

$accessToken = getToken();

try {
    /**
     * В случае потери/смены субдомена аккаунта, мы можем его получить с помощью refresh_token
     * api_domain - Claim из access_token
     * Сделав запрос на {api_domain}.amocrm.ru/oauth2/account/current/subdomain
     *
     * Получим модель с информацией о домене аккаунта по refresh_token
     * Подробнее: @see AccountDomainModel
     *
     * Запрос уходит на {api_domain}.amocrm.ru/oauth2/account/current/subdomain
     * С X-Refresh-Token: {refresh_token}
     *
     * @example curl 'https://api-a.amocrm.ru/oauth2/account/current/subdomain' -H 'X-Refresh-Token: {refresh_token}'
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
