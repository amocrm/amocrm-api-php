<?php

namespace AmoCRM\Client;

use AmoCRM\OAuth\OAuthConfigInterface;
use AmoCRM\OAuth\OAuthServiceInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * Class AmoCRMClientFactory
 *
 * Фабрика для создания и настройки AmoCRMApiClient.
 * Поддерживает OAuth авторизацию и долгоживущие токены.
 *
 * @package AmoCRM\Client
 */
class AmoCRMClientFactory
{
    /**
     * Создать API-клиент с OAuth авторизацией
     *
     * @param string $clientId ID интеграции
     * @param string $clientSecret Секретный ключ интеграции
     * @param string $redirectUri Callback URL для OAuth
     * @param OAuthConfigInterface|null $oAuthConfig Конфигурация OAuth
     * @param OAuthServiceInterface|null $oAuthService Сервис для сохранения токенов
     * @return AmoCRMApiClient
     */
    public static function createWithOAuth(
        string $clientId,
        string $clientSecret,
        string $redirectUri,
        ?OAuthConfigInterface $oAuthConfig = null,
        ?OAuthServiceInterface $oAuthService = null
    ): AmoCRMApiClient {
        $apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);

        if ($oAuthConfig !== null && $oAuthService !== null) {
            $factory = new AmoCRMApiClientFactory($oAuthConfig, $oAuthService);
            $apiClient = $factory->make();
        }

        return $apiClient;
    }

    /**
     * Создать API-клиент с долгоживущим токеном
     *
     * @param string $accessToken Долгоживущий токен доступа
     * @param string $accountDomain Домен аккаунта (например, 'subdomain.amocrm.ru')
     * @return AmoCRMApiClient
     */
    public static function createWithLongLivedToken(
        string $accessToken,
        string $accountDomain
    ): AmoCRMApiClient {
        $apiClient = new AmoCRMApiClient();
        $longLivedToken = new LongLivedAccessToken($accessToken);

        $apiClient
            ->setAccessToken($longLivedToken)
            ->setAccountBaseDomain($accountDomain);

        return $apiClient;
    }

    /**
     * Создать API-клиент с AccessToken объектом
     *
     * @param AccessTokenInterface $accessToken Объект токена
     * @param string|null $accountDomain Домен аккаунта (берётся из токена если не указан)
     * @param callable|null $onTokenRefresh Callback для обновления токена
     * @return AmoCRMApiClient
     */
    public static function createWithAccessToken(
        AccessTokenInterface $accessToken,
        ?string $accountDomain = null,
        ?callable $onTokenRefresh = null
    ): AmoCRMApiClient {
        $apiClient = new AmoCRMApiClient();

        $apiClient->setAccessToken($accessToken);

        // Если домен передан явно или есть в токене
        $domain = $accountDomain ?? $accessToken->getValues()['baseDomain'] ?? null;
        if ($domain !== null) {
            $apiClient->setAccountBaseDomain($domain);
        }

        // Устанавливаем callback для обновления токена
        if ($onTokenRefresh !== null) {
            $apiClient->onAccessTokenRefresh($onTokenRefresh);
        }

        return $apiClient;
    }

    /**
     * Восстановить API-клиент из сохранённого состояния токена
     *
     * @param array $tokenData Массив с данными токена
     *                         ['accessToken', 'refreshToken', 'expires', 'baseDomain']
     * @param callable|null $onTokenRefresh Callback для обновления токена
     * @return AmoCRMApiClient
     */
    public static function restoreFromTokenData(
        array $tokenData,
        ?callable $onTokenRefresh = null
    ): AmoCRMApiClient {
        $accessToken = new AccessToken([
            'access_token' => $tokenData['accessToken'] ?? '',
            'refresh_token' => $tokenData['refreshToken'] ?? '',
            'expires' => $tokenData['expires'] ?? time(),
            'baseDomain' => $tokenData['baseDomain'] ?? '',
        ]);

        return self::createWithAccessToken(
            $accessToken,
            $tokenData['baseDomain'] ?? null,
            $onTokenRefresh
        );
    }

    /**
     * Получить URL для авторизации OAuth
     *
     * @param AmoCRMApiClient $apiClient
     * @param string $state CSRF токен для безопасности
     * @param string $mode Режим авторизации ('post_message' или 'popup')
     * @return string URL авторизации
     */
    public static function getAuthorizationUrl(
        AmoCRMApiClient $apiClient,
        string $state = '',
        string $mode = 'post_message'
    ): string {
        return $apiClient->getOAuthClient()->getAuthorizeUrl([
            'state' => $state,
            'mode' => $mode,
        ]);
    }

    /**
     * Получить AccessToken по коду авторизации
     *
     * @param AmoCRMApiClient $apiClient
     * @param string $code Код авторизации из OAuth callback
     * @return AccessTokenInterface
     */
    public static function getAccessTokenByCode(
        AmoCRMApiClient $apiClient,
        string $code
    ): AccessTokenInterface {
        return $apiClient->getOAuthClient()->getAccessTokenByCode($code);
    }

    /**
     * Создать API-клиент с контекстом конкретного пользователя (для admin-токенов)
     *
     * @param AmoCRMApiClient $apiClient Базовый API-клиент
     * @param int $userId ID пользователя для контекста
     * @return AmoCRMApiClient API-клиент с установленным контекстом
     */
    public static function withUserContext(AmoCRMApiClient $apiClient, int $userId): AmoCRMApiClient
    {
        return $apiClient->withContextUserId($userId);
    }

    /**
     * Создать API-клиент с кастомным User Agent
     *
     * @param AmoCRMApiClient $apiClient Базовый API-клиент
     * @param string $userAgent Название приложения/интеграции
     * @return AmoCRMApiClient API-клиент с установленным User Agent
     */
    public static function withUserAgent(AmoCRMApiClient $apiClient, string $userAgent): AmoCRMApiClient
    {
        return $apiClient->setUserAgnet($userAgent);
    }

    /**
     * Создать API-клиент с кастомным обработчиком HTTP-статусов
     *
     * @param AmoCRMApiClient $apiClient Базовый API-клиент
     * @param callable $callback Функция обратного вызова для обработки ответов
     * @return AmoCRMApiClient
     */
    public static function withHttpStatusCallback(AmoCRMApiClient $apiClient, callable $callback): AmoCRMApiClient
    {
        return $apiClient->setCheckHttpStatusCallback($callback);
    }
}