<?php

namespace AmoCRM\OAuth;

use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\OAuth2\Client\Provider\AmoCRM;
use GuzzleHttp\ClientInterface;
use League\OAuth2\Client\Grant\AuthorizationCode;
use League\OAuth2\Client\Grant\RefreshToken;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * Class AmoCRMOAuth
 * @package AmoCRM\OAuth
 */
class AmoCRMOAuth
{
    protected const REQUEST_TIMEOUT = 15;

    /**
     * @var AmoCRM
     */
    private $oauthProvider;

    /**
     * @var null|callable
     */
    private $accessTokenRefreshCallback = null;

    /**
     * AmoCRMOAuth constructor.
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUri
     */
    public function __construct(string $clientId, string $clientSecret, string $redirectUri)
    {
        $this->oauthProvider = new AmoCRM(
            [
                'clientId' => $clientId,
                'clientSecret' => $clientSecret,
                'redirectUri' => $redirectUri,
                'timeout' => self::REQUEST_TIMEOUT,
            ]
        );
    }

    /**
     * Получение ссылки на окно предоставление доступа
     * @param string|null $state
     * @return string
     */
    public function getAuthorizeUrl(?string $state = null): string
    {
        $params = [];

        if (!is_null($state)) {
            $params['state'] = $state;
        }

        //todo popup type and params
        $authorizationUrl = $this->oauthProvider->getAuthorizationUrl($params);

        return $authorizationUrl;
    }

    /**
     * Получение Access токена по коду авторизации
     * @param string $code
     * @return AccessTokenInterface
     * @throws AmoCRMoAuthApiException
     */
    public function getAccessTokenByCode(string $code): AccessTokenInterface
    {
        try {
            $accessToken = $this->oauthProvider->getAccessToken(new AuthorizationCode(), [
                'code' => $code,
            ]);
        } catch (IdentityProviderException $e) {
            throw new AmoCRMoAuthApiException($e);
        }

        return $accessToken;
    }

    /**
     * Получение нового Access токена по Refresh токену
     * @param AccessTokenInterface $accessToken
     * @return AccessTokenInterface
     * @throws AmoCRMoAuthApiException
     */
    public function getAccessTokenByRefreshToken(AccessTokenInterface $accessToken): AccessTokenInterface
    {
        try {
            $accessToken = $this->oauthProvider->getAccessToken(new RefreshToken(), [
                'refresh_token' => $accessToken->getRefreshToken(),
            ]);
        } catch (IdentityProviderException $e) {
            throw new AmoCRMoAuthApiException($e);
        }

        if (is_callable($this->accessTokenRefreshCallback)) {
            $callback = $this->accessTokenRefreshCallback;
            $baseAccountDomain = $this->oauthProvider->getBaseDomain();
            $callback($accessToken, $baseAccountDomain);
        }

        return $accessToken;
    }

    /**
     * Получить Http клиент, который используется для работы с oAuth
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->oauthProvider->getHttpClient();
    }

    /**
     * Установка базового домена, куда будут отправляться запросы необходимые для работы с токенами
     * @param string $domain
     * @return $this
     */
    public function setBaseDomain(string $domain): self
    {
        $this->oauthProvider->setBaseDomain($domain);

        return $this;
    }

    /**
     * Получение авторизационных заголовков для переданного Access токена
     * @param AccessTokenInterface $accessToken
     * @return array
     */
    public function getAuthorizationHeaders(AccessTokenInterface $accessToken): array
    {
        return $this->oauthProvider->getHeaders($accessToken);
    }

    /**
     * Получаем URL аккаунта
     * @return string
     */
    public function getAccountUrl(): string
    {
        return $this->oauthProvider->urlAccount();
    }

    /**
     * Установка Callback при обновлении Access токена по
     * @param callable $function
     * @return AmoCRMOAuth
     */
    public function setAccessTokenRefreshCallback(callable $function): self
    {
        $this->accessTokenRefreshCallback = $function;

        return $this;
    }

    /**
     * Получение данных о том, кому принадлежит токен
     * @param AccessTokenInterface $accessToken
     * @return ResourceOwnerInterface
     */
    public function getResourceOwner(AccessTokenInterface $accessToken): ResourceOwnerInterface
    {
        return $this->oauthProvider->getResourceOwner($accessToken);
    }

    /**
     * Доступные значения для options:
     * string title
     * bool compact
     * string class_name
     * string color
     * string state
     * string error_callback
     *
     * @param array $options
     * @return string
     */
    public function getOAuthButton(array $options = []): string
    {
        $title = $options['title'] ?? 'Установить интеграцию';
        $compact = isset($options['compact']) && $options['compact'] ? 'true' : 'false';
        $className = $options['class_name'] ?? 'className';
        $color = $options['color'] ?? 'default'; //todo validate colors
        $errorCallback = $options['error_callback'] ?? 'handleOauthError';
        try {
            $state = $options['state'] ?? bin2hex(random_bytes(10));
        } catch (\Exception $exception) {
            $state = rand(1, 100);
        }

        $button =
        '<div>
                <script
                    class="amocrm_oauth"
                    charset="utf-8"
                    data-client-id="' . $this->oauthProvider->getClientId() . '"
                    data-title="' . $title . '"
                    data-compact="' . $compact . '"
                    data-class-name="' . $className . '"
                    data-color="' . $color . '"
                    data-state="' . $state . '"
                    data-error-callback="' . $errorCallback . '"
                    src="https://www.amocrm.ru/auth/button.min.js"
                ></script>
        </div>';

        return $button;
    }
}
