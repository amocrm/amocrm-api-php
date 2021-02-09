<?php

namespace AmoCRM\OAuth;

use AmoCRM\AmoCRM\Exceptions\DisposableTokenExpiredException;
use AmoCRM\AmoCRM\Exceptions\DisposableTokenInvalidDestinationException;
use AmoCRM\AmoCRM\Exceptions\DisposableTokenVerificationFailedException;
use AmoCRM\AmoCRM\Models\AccountDomainModel;
use AmoCRM\AmoCRM\Models\DisposableTokenModel;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Exceptions\AmoCRMApiConnectExceptionException;
use AmoCRM\Exceptions\AmoCRMApiErrorResponseException;
use AmoCRM\Exceptions\AmoCRMApiHttpClientException;
use AmoCRM\Exceptions\AmoCRMApiTooManyRedirectsException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\BadTypeException;
use AmoCRM\OAuth2\Client\Provider\AmoCRM;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Psr7\Uri;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Lcobucci\JWT\Validation\ConstraintViolation;
use League\OAuth2\Client\Grant\AuthorizationCode;
use League\OAuth2\Client\Grant\RefreshToken;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

use function sprintf;

/**
 * Class AmoCRMOAuth
 * @package AmoCRM\OAuth
 */
class AmoCRMOAuth
{
    /**
     * Доступные цвета для кнопки авторизации
     */
    public const BUTTON_COLORS = [
        'default' => '#339DC7',
        'blue' => '#1976D2',
        'violet' => '#6A1B9A',
        'green' => '#388E3C',
        'orange' => '#F57F17',
        'red' => '#D84315',
    ];

    protected const REQUEST_TIMEOUT = 15;

    /**
     * @var AmoCRM
     */
    protected $oauthProvider;

    /**
     * @var null|callable
     */
    private $accessTokenRefreshCallback = null;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $redirectUri;

    /**
     * AmoCRMOAuth constructor.
     * @param string $clientId
     * @param string $clientSecret
     * @param null|string $redirectUri
     */
    public function __construct(string $clientId, string $clientSecret, ?string $redirectUri)
    {
        $this->oauthProvider = new AmoCRM(
            [
                'clientId' => $clientId,
                'clientSecret' => $clientSecret,
                'redirectUri' => $redirectUri,
                'timeout' => self::REQUEST_TIMEOUT,
            ]
        );

        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
    }

    /**
     * Получение ссылки на окно предоставление доступа
     *
     * @param array $options
     *
     * @return string
     */
    public function getAuthorizeUrl(array $options = []): string
    {
        return $this->oauthProvider->getAuthorizationUrl($options);
    }

    /**
     * Получение Access токена по коду авторизации
     * @param string $code
     *
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
     *
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
            throw new AmoCRMoAuthApiException($e->getResponseBody());
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
     *
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->oauthProvider->getHttpClient();
    }

    /**
     * Установка базового домена, куда будут отправляться запросы необходимые для работы с токенами
     * @param string $domain
     *
     * @return $this
     */
    public function setBaseDomain(string $domain): self
    {
        $this->oauthProvider->setBaseDomain($domain);

        return $this;
    }

    /**
     * Установка протокола
     * @param string $protocol
     *
     * @return $this
     */
    public function setProtocol(string $protocol): self
    {
        $this->oauthProvider->setProtocol($protocol);

        return $this;
    }

    /**
     * Установка адреса перенаправления
     *
     * @param string|null $redirectUri
     *
     * @return $this
     */
    public function setRedirectUri(?string $redirectUri): self
    {
        $this->oauthProvider->setRedirectUri($redirectUri);

        return $this;
    }

    /**
     * Получаем oAuth провайдера
     *
     * @return AmoCRM
     */
    public function getOAuthProvider(): AmoCRM
    {
        return $this->oauthProvider;
    }

    /**
     * Получение авторизационных заголовков для переданного Access токена
     * @param AccessTokenInterface $accessToken
     *
     * @return array
     */
    public function getAuthorizationHeaders(AccessTokenInterface $accessToken): array
    {
        return $this->oauthProvider->getHeaders($accessToken);
    }

    /**
     * Получаем URL аккаунта
     *
     * @return string
     */
    public function getAccountUrl(): string
    {
        return $this->oauthProvider->urlAccount();
    }

    /**
     * Установка Callback при обновлении Access токена по
     * @param callable $function
     *
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
     *
     * @return ResourceOwnerInterface
     */
    public function getResourceOwner(AccessTokenInterface $accessToken): ResourceOwnerInterface
    {
        /** @var AccessToken $accessToken */
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
     *
     * @return string
     * @throws BadTypeException
     */
    public function getOAuthButton(array $options = []): string
    {
        if (isset($options['color']) && !array_key_exists($options['color'], self::BUTTON_COLORS)) {
            throw new BadTypeException('Invalid color selected');
        }

        $title = $options['title'] ?? 'Установить интеграцию';
        $compact = isset($options['compact']) && $options['compact'] ? 'true' : 'false';
        $className = $options['class_name'] ?? 'className';
        $color = $options['color'] ?? 'default';
        $errorCallback = $options['error_callback'] ?? 'handleOauthError';
        $mode = isset($options['mode']) && in_array($options['mode'], ['popup', 'post_message'])
            ? $options['mode']
            : 'post_message';
        try {
            $state = $options['state'] ?? bin2hex(random_bytes(10));
        } catch (Exception $exception) {
            $state = rand(1, 100);
        }

        return '<div>
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
                    data-mode="' . $mode . '"
                    src="https://www.amocrm.ru/auth/button.min.js"
                ></script>
        </div>';
    }

    /**
     * @param string $login
     * @param string $apiKey
     *
     * @throws AmoCRMApiConnectExceptionException
     * @throws AmoCRMApiHttpClientException
     * @throws AmoCRMApiTooManyRedirectsException
     * @throws AmoCRMApiErrorResponseException
     */
    public function exchangeApiKey(string $login, string $apiKey)
    {
        $body = [
            'login' => $login,
            'api_key' => $apiKey,
            'client_uuid' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];

        $headers = [];
        $headers['User-Agent'] = AmoCRMApiRequest::USER_AGENT;

        try {
            $response = $this->oauthProvider->getHttpClient()->request(
                AmoCRMApiRequest::POST_REQUEST,
                $this->oauthProvider->urlAccount() . '/oauth2/exchange_api_key',
                [
                    'json' => $body,
                    'connect_timeout' => AmoCRMApiRequest::CONNECT_TIMEOUT,
                    'headers' => $headers,
                    'http_errors' => false,
                    'query' => [],
                    'timeout' => self::REQUEST_TIMEOUT,
                ]
            );
        } catch (ConnectException $e) {
            throw new AmoCRMApiConnectExceptionException($e->getMessage(), $e->getCode());
        } catch (TooManyRedirectsException $e) {
            throw new AmoCRMApiTooManyRedirectsException($e->getMessage(), $e->getCode());
        } catch (GuzzleException $e) {
            throw new AmoCRMApiHttpClientException($e->getMessage(), $e->getCode());
        }

        if ($response->getStatusCode() !== StatusCodeInterface::STATUS_ACCEPTED) {
            throw new AmoCRMApiErrorResponseException(
                'Invalid response code',
                $response->getStatusCode(),
                [],
                (string)$response->getBody()
            );
        }
    }

    /**
     * Получение субдомена аккаунта по токену
     *
     * @param AccessTokenInterface $accessToken
     *
     * @return AccountDomainModel
     * @throws AmoCRMApiErrorResponseException
     * @throws AmoCRMApiConnectExceptionException
     * @throws AmoCRMApiHttpClientException
     */
    public function getAccountDomain(AccessTokenInterface $accessToken): AccountDomainModel
    {
        try {
            $response = $this->oauthProvider->getHttpClient()->request(
                AmoCRMApiRequest::GET_REQUEST,
                sprintf(
                    '%s%s%s',
                    $this->oauthProvider->getProtocol(),
                    $this->oauthProvider->getBaseDomain(),
                    '/oauth2/account/subdomain'
                ),
                [
                    'headers' => $this->oauthProvider->getHeaders($accessToken),
                    'connect_timeout' => AmoCRMApiRequest::CONNECT_TIMEOUT,
                    'http_errors' => false,
                    'timeout' => self::REQUEST_TIMEOUT,
                    'query' => [],
                    'json' => [],
                ]
            );

            $responseBody = (string)$response->getBody();
            if ($response->getStatusCode() !== StatusCodeInterface::STATUS_OK) {
                throw new AmoCRMApiErrorResponseException(
                    'Invalid response',
                    $response->getStatusCode(),
                    [],
                    $responseBody
                );
            }
            $response = json_decode($responseBody, true);
            $accountDomainModel = AccountDomainModel::fromArray($response);
        } catch (ConnectException $e) {
            throw new AmoCRMApiConnectExceptionException($e->getMessage(), $e->getCode());
        } catch (GuzzleException $e) {
            throw new AmoCRMApiHttpClientException($e->getMessage(), $e->getCode());
        }

        return $accountDomainModel;
    }

    /**
     * Расшифровывает полученный одноразовый токен и возвращает модель
     *
     * @param string $token
     *
     * @return DisposableTokenModel
     *
     * @throws DisposableTokenExpiredException
     * @throws DisposableTokenVerificationFailedException
     * @throws DisposableTokenInvalidDestinationException
     *
     * @link https://www.amocrm.ru/developers/content/web_sdk/mechanics
     */
    public function parseDisposableToken(string $token): DisposableTokenModel
    {
        $signer = new Sha256();
        $key = InMemory::plainText($this->clientSecret);

        $clientBaseUri = new Uri($this->redirectUri);
        $clientBaseUri = sprintf('%s://%s', $clientBaseUri->getScheme(), $clientBaseUri->getHost());
        $constraints = [
            // Проверка подписи
            new SignedWith($signer, $key),
            // Проверим наш ли адресат
            new PermittedFor($clientBaseUri),
            // Проверка жизни токена, с 4.2 deprecated use LooseValidAt
            new ValidAt(FrozenClock::fromUTC()),
        ];

        $configuration = Configuration::forSymmetricSigner($signer, $key);
        $jwtToken = $configuration->parser()->parse($token);

        try {
            /** @var Constraint $constraint */
            foreach ($constraints as $constraint) {
                $constraint->assert($jwtToken);
            }
        } catch (ConstraintViolation $e) {
            switch (true) {
                case $constraint instanceof SignedWith:
                    throw DisposableTokenVerificationFailedException::create();
                case $constraint instanceof PermittedFor:
                    throw DisposableTokenInvalidDestinationException::create();
                case $constraint instanceof ValidAt:
                    throw DisposableTokenExpiredException::create();
            }
        }

        return DisposableTokenModel::fromJwtToken($jwtToken);
    }
}
