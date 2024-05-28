<?php

namespace AmoCRM\Client;

use AmoCRM\Exceptions\AmoCRMApiConnectExceptionException;
use AmoCRM\Exceptions\AmoCRMApiErrorResponseException;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMApiHttpClientException;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Exceptions\AmoCRMApiTooManyRedirectsException;
use AmoCRM\Exceptions\AmoCRMApiTooManyRequestsException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\OAuth\AmoCRMOAuth;
use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\RequestOptions;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AmoCRMApiRequest
 *
 * @package AmoCRM\Client
 */
class AmoCRMApiRequest
{
    public const POST_REQUEST = 'POST';
    public const PUT_REQUEST = 'PUT';
    public const GET_REQUEST = 'GET';
    public const PATCH_REQUEST = 'PATCH';
    public const DELETE_REQUEST = 'DELETE';

    public const CONNECT_TIMEOUT = 5;
    public const REQUEST_TIMEOUT = 20;
    //TODO Do not forget to change this on each release
    public const LIBRARY_VERSION = '1.9.0';
    public const USER_AGENT = 'amoCRM-API-Library/' . self::LIBRARY_VERSION;

    public const SUCCESS_STATUSES = [
        StatusCodeInterface::STATUS_OK,
        StatusCodeInterface::STATUS_CREATED,
        StatusCodeInterface::STATUS_ACCEPTED,
        StatusCodeInterface::STATUS_NO_CONTENT,
    ];

    public const EMBEDDED = '_embedded';

    /**
     * @var AccessTokenInterface
     */
    private $accessToken;

    /**
     * @var AmoCRMOAuth
     */
    private $oAuthClient;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var string
     */
    private $lastMethod;

    /**
     * @var string
     */
    private $lastHttpMethod;

    /**
     * @var array|mixed
     */
    private $lastBody = [];

    /**
     * @var string|null
     */
    private $lastResponse;

    /**
     * @var int
     */
    private $lastResponseCode;

    /**
     * @var array|null
     */
    private $lastQueryParams;

    /**
     * @var string|null
     */
    private $lastRequestId;

    /** @var string|null */
    private $requestDomain = null;

    /** @var int|null */
    private $contextUserId = null;

    /** @var string|null */
    private $userAgent = null;

    /**
     * @var callable
     */
    private $refreshAccessTokenCallback;

    /**
     * @var callable|null
     */
    private $customCheckHttpStatusCallback;

    /**
     * AmoCRMApiRequest constructor.
     *
     * @param AccessTokenInterface $accessToken
     * @param AmoCRMOAuth $oAuthClient
     * @param int|null $contextUserId
     * @param string|null $userAgent
     */
    public function __construct(
        AccessTokenInterface $accessToken,
        AmoCRMOAuth $oAuthClient,
        ?int $contextUserId,
        ?string $userAgent
    ) {
        $this->accessToken = $accessToken;
        $this->oAuthClient = $oAuthClient;
        $this->httpClient = $oAuthClient->getHttpClient();
        $this->contextUserId = $contextUserId;
        $this->userAgent = $userAgent;
        $this->refreshAccessTokenCallback = function () {
            return $this->oAuthClient->getAccessTokenByRefreshToken($this->accessToken);
        };
    }

    public function setRefreshAccessTokenCallback(callable $callback): void
    {
        $this->refreshAccessTokenCallback = $callback;
    }

    public function setCustomCheckStatusCallback(callable $callback): void
    {
        $this->customCheckHttpStatusCallback = $callback;
    }


    /**
     * Обновляем в библиотеке oAuth access токен по refresh
     * @throws AmoCRMoAuthApiException
     */
    private function refreshAccessToken(): void
    {
        if ($this->accessToken instanceof LongLivedAccessToken) {
            throw new AmoCRMoAuthApiException('Can not update LongLivedAccessToken');
        }

        $newAccessToken = ($this->refreshAccessTokenCallback)();
        $this->accessToken = $newAccessToken;
    }

    /**
     * @param string $method
     * @param array|mixed $body
     * @param array $queryParams
     * @param array $headers
     * @param bool $needToRefresh
     * @param bool $isFullPath
     *
     * @return array
     * @throws AmoCRMApiConnectExceptionException
     * @throws AmoCRMApiException
     * @throws AmoCRMApiHttpClientException
     * @throws AmoCRMApiNoContentException
     * @throws AmoCRMApiTooManyRedirectsException
     * @throws AmoCRMoAuthApiException
     */
    public function post(
        string $method,
        $body = [],
        array $queryParams = [],
        array $headers = [],
        bool $needToRefresh = false,
        bool $isFullPath = false
    ): array {
        if ($this->isAccessTokenNeedToBeRefreshed()) {
            $needToRefresh = true;
        }

        if ($needToRefresh) {
            $this->refreshAccessToken();
        }

        $headers = array_merge($headers, $this->getBaseHeaders());

        $this->lastHttpMethod = self::POST_REQUEST;
        $this->lastMethod = $isFullPath ? $method : $this->getUrl() . $method;
        $this->lastBody = $body;
        $this->lastQueryParams = $queryParams;

        $requestOptions = [
            'connect_timeout' => self::CONNECT_TIMEOUT,
            'headers' => $headers,
            'http_errors' => false,
            'query' => $queryParams,
            'timeout' => self::REQUEST_TIMEOUT,
        ];

        if (is_array($body)) {
            $requestOptions['json'] = $body;
        } else {
            $requestOptions['body'] = $body;
        }

        try {
            $response = $this->httpClient->request(
                self::POST_REQUEST,
                $isFullPath ? $method : $this->getUrl() . $method,
                $requestOptions
            );
        } catch (ConnectException $e) {
            throw new AmoCRMApiConnectExceptionException($e->getMessage(), $e->getCode(), $this->getLastRequestInfo());
        } catch (TooManyRedirectsException $e) {
            throw new AmoCRMApiTooManyRedirectsException($e->getMessage(), $e->getCode(), $this->getLastRequestInfo());
        } catch (GuzzleException $e) {
            throw new AmoCRMApiHttpClientException($e->getMessage(), $e->getCode(), $this->getLastRequestInfo());
        }

        if (!empty($response->getHeader('X-Request-Id'))) {
            $this->lastRequestId = $response->getHeader('X-Request-Id')[0];
        }

        /**
         * В случае получения ошибки авторизации, пробуем обновить токен 1 раз,
         * если не получилось, то тогда уже выкидываем Exception
         */
        try {
            $response = $this->parseResponse($response);
        } catch (AmoCRMoAuthApiException $e) {
            if ($needToRefresh || $this->accessToken instanceof LongLivedAccessToken) {
                throw $e;
            }

            return $this->post($method, $body, $queryParams, $headers, true);
        }

        return $response;
    }

    /**
     * @param string $method
     * @param array|mixed $body
     * @param array $queryParams
     * @param array $headers
     * @param bool $needToRefresh
     * @param bool $isFullPath
     *
     * @return array
     * @throws AmoCRMApiConnectExceptionException
     * @throws AmoCRMApiException
     * @throws AmoCRMApiHttpClientException
     * @throws AmoCRMApiNoContentException
     * @throws AmoCRMApiTooManyRedirectsException
     * @throws AmoCRMoAuthApiException
     */
    public function put(
        string $method,
        $body = [],
        array $queryParams = [],
        array $headers = [],
        bool $needToRefresh = false,
        bool $isFullPath = false
    ): array {
        if ($this->isAccessTokenNeedToBeRefreshed()) {
            $needToRefresh = true;
        }

        if ($needToRefresh) {
            $this->refreshAccessToken();
        }

        $headers = array_merge($headers, $this->getBaseHeaders());

        $this->lastHttpMethod = self::PUT_REQUEST;
        $this->lastMethod = $isFullPath ? $method : $this->getUrl() . $method;
        $this->lastBody = $body;
        $this->lastQueryParams = $queryParams;

        $requestOptions = [
            'connect_timeout' => self::CONNECT_TIMEOUT,
            'headers' => $headers,
            'http_errors' => false,
            'query' => $queryParams,
            'timeout' => self::REQUEST_TIMEOUT,
        ];

        if (is_array($body)) {
            $requestOptions['json'] = $body;
        } else {
            $requestOptions['body'] = $body;
        }

        try {
            $response = $this->httpClient->request(
                self::PUT_REQUEST,
                $isFullPath ? $method : $this->getUrl() . $method,
                $requestOptions
            );
        } catch (ConnectException $e) {
            throw new AmoCRMApiConnectExceptionException($e->getMessage(), $e->getCode(), $this->getLastRequestInfo());
        } catch (TooManyRedirectsException $e) {
            throw new AmoCRMApiTooManyRedirectsException($e->getMessage(), $e->getCode(), $this->getLastRequestInfo());
        } catch (GuzzleException $e) {
            throw new AmoCRMApiHttpClientException($e->getMessage(), $e->getCode(), $this->getLastRequestInfo());
        }

        if (!empty($response->getHeader('X-Request-Id'))) {
            $this->lastRequestId = $response->getHeader('X-Request-Id')[0];
        }

        /**
         * В случае получения ошибки авторизации, пробуем обновить токен 1 раз,
         * если не получилось, то тогда уже выкидываем Exception
         */
        try {
            $response = $this->parseResponse($response);
        } catch (AmoCRMoAuthApiException $e) {
            if ($needToRefresh || $this->accessToken instanceof LongLivedAccessToken) {
                throw $e;
            }

            return $this->post($method, $body, $queryParams, $headers, true);
        }

        return $response;
    }

    /**
     * @param string $method
     * @param array $body
     * @param array $queryParams
     * @param array $headers
     * @param bool $needToRefresh
     * @return array
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiNoContentException
     */
    public function patch(
        string $method,
        array $body = [],
        array $queryParams = [],
        array $headers = [],
        bool $needToRefresh = false
    ): array {
        if ($this->isAccessTokenNeedToBeRefreshed()) {
            $needToRefresh = true;
        }

        if ($needToRefresh) {
            $this->refreshAccessToken();
        }

        $headers = array_merge($headers, $this->getBaseHeaders());

        $this->lastHttpMethod = self::PATCH_REQUEST;
        $this->lastMethod = $this->getUrl() . $method;
        $this->lastBody = $body;
        $this->lastQueryParams = $queryParams;

        try {
            $response = $this->httpClient->request(
                self::PATCH_REQUEST,
                $this->getUrl() . $method,
                [
                    RequestOptions::JSON => $body,
                    RequestOptions::CONNECT_TIMEOUT => self::CONNECT_TIMEOUT,
                    RequestOptions::HEADERS => $headers,
                    RequestOptions::HTTP_ERRORS => false,
                    RequestOptions::QUERY => $queryParams,
                    RequestOptions::TIMEOUT => self::REQUEST_TIMEOUT,
                ]
            );
        } catch (ConnectException $e) {
            throw new AmoCRMApiConnectExceptionException($e->getMessage(), $e->getCode(), $this->getLastRequestInfo());
        } catch (TooManyRedirectsException $e) {
            throw new AmoCRMApiTooManyRedirectsException($e->getMessage(), $e->getCode(), $this->getLastRequestInfo());
        } catch (GuzzleException $e) {
            throw new AmoCRMApiHttpClientException($e->getMessage(), $e->getCode(), $this->getLastRequestInfo());
        }

        if (!empty($response->getHeader('X-Request-Id'))) {
            $this->lastRequestId = $response->getHeader('X-Request-Id')[0];
        }

        /**
         * В случае получения ошибки авторизации, пробуем обновить токен 1 раз,
         * если не получилось, то тогда уже выкидываем Exception
         */
        try {
            $response = $this->parseResponse($response);
        } catch (AmoCRMoAuthApiException $e) {
            if ($needToRefresh || $this->accessToken instanceof LongLivedAccessToken) {
                throw $e;
            }

            return $this->patch($method, $body, $queryParams, $headers, true);
        }

        return $response;
    }

    /**
     * @param string $method
     * @param array $body
     * @param array $queryParams
     * @param array $headers
     * @param bool $needToRefresh
     * @return array
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function delete(
        string $method,
        array $body = [],
        array $queryParams = [],
        array $headers = [],
        bool $needToRefresh = false
    ): array {
        if ($this->isAccessTokenNeedToBeRefreshed()) {
            $needToRefresh = true;
        }

        if ($needToRefresh) {
            $this->refreshAccessToken();
        }

        $headers = array_merge($headers, $this->getBaseHeaders());

        $this->lastHttpMethod = self::DELETE_REQUEST;
        $this->lastMethod = $this->getUrl() . $method;
        $this->lastBody = $body;
        $this->lastQueryParams = $queryParams;

        try {
            $response = $this->httpClient->request(
                self::DELETE_REQUEST,
                $this->getUrl() . $method,
                [
                    RequestOptions::JSON => $body,
                    RequestOptions::CONNECT_TIMEOUT => self::CONNECT_TIMEOUT,
                    RequestOptions::HEADERS => $headers,
                    RequestOptions::HTTP_ERRORS => false,
                    RequestOptions::QUERY => $queryParams,
                    RequestOptions::TIMEOUT => self::REQUEST_TIMEOUT,
                ]
            );
        } catch (ConnectException $e) {
            throw new AmoCRMApiConnectExceptionException($e->getMessage(), $e->getCode(), $this->getLastRequestInfo());
        } catch (TooManyRedirectsException $e) {
            throw new AmoCRMApiTooManyRedirectsException($e->getMessage(), $e->getCode(), $this->getLastRequestInfo());
        } catch (GuzzleException $e) {
            throw new AmoCRMApiHttpClientException($e->getMessage(), $e->getCode(), $this->getLastRequestInfo());
        }

        if (!empty($response->getHeader('X-Request-Id'))) {
            $this->lastRequestId = $response->getHeader('X-Request-Id')[0];
        }

        /**
         * В случае получения ошибки авторизации, пробуем обновить токен 1 раз,
         * если не получилось, то тогда уже выкидываем Exception
         */
        try {
            $response = $this->parseResponse($response);
        } catch (AmoCRMoAuthApiException $e) {
            if ($needToRefresh || $this->accessToken instanceof LongLivedAccessToken) {
                throw $e;
            }

            return $this->delete($method, $body, $queryParams, $headers, true);
        } catch (AmoCRMApiNoContentException $e) {
            return ['result' => true];
        }

        return $response;
    }

    /**
     * @param string $method
     * @param array $queryParams
     * @param array $headers
     * @param bool $needToRefresh
     * @return array
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiException
     */
    public function get(
        string $method,
        array $queryParams = [],
        array $headers = [],
        bool $needToRefresh = false
    ): array {
        if ($this->isAccessTokenNeedToBeRefreshed()) {
            $needToRefresh = true;
        }

        if ($needToRefresh) {
            $this->refreshAccessToken();
        }

        $headers = array_merge($headers, $this->getBaseHeaders());

        $this->lastHttpMethod = self::GET_REQUEST;
        $method = $this->getUrl() .
            str_replace(
                $this->getUrl(),
                '',
                $method
            );
        $this->lastMethod = $method;
        $this->lastBody = [];
        $this->lastQueryParams = $queryParams;

        try {
            $response = $this->httpClient->request(
                self::GET_REQUEST,
                $method,
                [
                    'connect_timeout' => self::CONNECT_TIMEOUT,
                    'headers' => $headers,
                    'http_errors' => false,
                    'query' => $queryParams,
                    'timeout' => self::REQUEST_TIMEOUT,
                ]
            );
        } catch (ConnectException $e) {
            throw new AmoCRMApiConnectExceptionException($e->getMessage(), $e->getCode(), $this->getLastRequestInfo());
        } catch (TooManyRedirectsException $e) {
            throw new AmoCRMApiTooManyRedirectsException($e->getMessage(), $e->getCode(), $this->getLastRequestInfo());
        } catch (GuzzleException $e) {
            throw new AmoCRMApiHttpClientException($e->getMessage(), $e->getCode(), $this->getLastRequestInfo());
        }

        if (!empty($response->getHeader('X-Request-Id'))) {
            $this->lastRequestId = $response->getHeader('X-Request-Id')[0];
        }

        /**
         * В случае получения ошибки авторизации, пробуем обновить токен 1 раз,
         * если не получилось, то тогда уже выкидываем Exception
         */
        try {
            $response = $this->parseResponse($response);
        } catch (AmoCRMoAuthApiException $e) {
            if ($needToRefresh || $this->accessToken instanceof LongLivedAccessToken) {
                throw $e;
            }

            return $this->get($method, $queryParams, $headers, true);
        }

        return $response;
    }

    /**
     * @param ResponseInterface $response
     * @param array $decodedBody
     * @throws AmoCRMApiErrorResponseException
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    protected function checkHttpStatus(ResponseInterface $response, $decodedBody = []): void
    {
        $this->lastResponseCode = $response->getStatusCode();

        if (
            $this->customCheckHttpStatusCallback !== null
            && is_callable($this->customCheckHttpStatusCallback)
            && ($this->customCheckHttpStatusCallback)($response, $decodedBody) === true
        ) {
            return;
        }

        if ((int)$response->getStatusCode() === StatusCodeInterface::STATUS_UNAUTHORIZED) {
            throw new AmoCRMoAuthApiException(
                "Unauthorized",
                $response->getStatusCode(),
                $this->getLastRequestInfo(),
                isset($decodedBody['detail']) ? $decodedBody['detail'] : ''
            );
        }

        if ((int)$response->getStatusCode() === StatusCodeInterface::STATUS_NO_CONTENT) {
            throw new AmoCRMApiNoContentException(
                "No content",
                $response->getStatusCode(),
                $this->getLastRequestInfo()
            );
        }

        if (!in_array((int)$response->getStatusCode(), self::SUCCESS_STATUSES, true)) {
            $exception = new AmoCRMApiException(
                "Invalid http status",
                $response->getStatusCode(),
                $this->getLastRequestInfo(),
                isset($decodedBody['detail']) ? $decodedBody['detail'] : ''
            );

            if (
                $response->getStatusCode() === StatusCodeInterface::STATUS_BAD_REQUEST
                && !empty($decodedBody['validation-errors'])
            ) {
                $exception = new AmoCRMApiErrorResponseException(
                    "Response has validation errors",
                    $response->getStatusCode(),
                    $this->getLastRequestInfo(),
                    isset($decodedBody['detail']) ? $decodedBody['detail'] : ''
                );
                $exception->setValidationErrors($decodedBody['validation-errors']);
            }

            throw $exception;
        }
    }

    /**
     * Http status check and response check
     *
     * @param ResponseInterface $response
     * @return array
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiException
     */
    private function parseResponse(ResponseInterface $response): array
    {
        $bodyContents = (string)$response->getBody();
        $this->lastResponse = $bodyContents;

        $decodedBody = json_decode($bodyContents, true);

        if (
            $response->getStatusCode() !== StatusCodeInterface::STATUS_ACCEPTED
            && !$decodedBody
            && !empty($bodyContents)
        ) {
            if ($response->getStatusCode() === StatusCodeInterface::STATUS_TOO_MANY_REQUESTS) {
                throw new AmoCRMApiTooManyRequestsException(
                    "Too many requests",
                    $response->getStatusCode(),
                    $this->getLastRequestInfo()
                );
            }

            throw new AmoCRMApiException(
                "Response body is not json",
                $response->getStatusCode(),
                $this->getLastRequestInfo()
            );
        }

        $this->checkHttpStatus($response, $decodedBody);

        return $decodedBody ?? [];
    }

    /**
     * @return array
     */
    private function getBaseHeaders()
    {
        $headers = $this->oAuthClient->getAuthorizationHeaders($this->accessToken);

        $headers['User-Agent'] = $this->getUserAgent();
        $headers['X-Library-Version'] = self::LIBRARY_VERSION;
        $headers['X-Client-UUID'] = $this->oAuthClient->getOAuthProvider()->getClientId();

        if (!is_null($this->contextUserId)) {
            $headers['X-Context-User-ID'] = $this->contextUserId;
        }

        return $headers;
    }

    /**
     * @return array
     */
    public function getLastRequestInfo(): array
    {
        return [
            'context_user_id' => $this->contextUserId,
            'last_http_method' => $this->lastHttpMethod,
            'last_method' => $this->lastMethod,
            'last_body' => $this->lastBody,
            'last_query_params' => $this->lastQueryParams,
            'last_response' => $this->lastResponse,
            'last_response_code' => $this->lastResponseCode,
            'last_request_id' => $this->lastRequestId,
            'jquery_call' => $this->buildJqueryCall(),
            'curl_call' => $this->buildCurlCall(),
            'timestamp' => time(),
        ];
    }

    /**
     * @return string
     */
    private function buildJqueryCall(): string
    {
        $url = $this->lastMethod;

        if (!empty($this->lastQueryParams)) {
            $url .= '?' . http_build_query($this->lastQueryParams);
        }

        $data = json_encode($this->lastBody, JSON_UNESCAPED_UNICODE);
        $requestHttpMethod = $this->lastHttpMethod;

        return '
$.ajax({
    url: "' . $url . '",
    data: JSON.stringify(' . $data . '),
    type: "' . $requestHttpMethod . '",
    contentType: "application/json"
});';
    }

    /**
     * @return string
     */
    private function buildCurlCall(): string
    {
        $url = $this->lastMethod;

        if (!empty($this->lastQueryParams)) {
            $url .= '?' . http_build_query($this->lastQueryParams);
        }

        $requestHttpMethod = $this->lastHttpMethod;

        $data = is_array($this->lastBody) ? http_build_query($this->lastBody) : $this->lastBody;

        return 'curl "' . $url . '" -X "' . $requestHttpMethod . '" -d"' . $data . '"';
    }

    private function getRequestDomain(): ?string
    {
        return $this->requestDomain;
    }

    public function setRequestDomain(?string $requestDomain): void
    {
        if ($requestDomain !== null && $requestDomain[strlen($requestDomain) - 1] === '.') {
            $hostExploded = explode(".", parse_url($this->oAuthClient->getAccountUrl(), PHP_URL_HOST));
            $tld = end($hostExploded);
            $requestDomain .= $tld;
        }

        $this->requestDomain = $requestDomain;
    }

    private function getUrl(): string
    {
        return !is_null($this->getRequestDomain()) ? $this->getRequestDomain() . '/' : $this->oAuthClient->getAccountUrl();
    }

    public function getUserAgent(): string
    {
        return !empty($this->userAgent)
            ? sprintf('%s (%s)', $this->userAgent, self::USER_AGENT)
            : self::USER_AGENT;
    }

    private function isAccessTokenNeedToBeRefreshed(): bool
    {
        return !$this->accessToken instanceof LongLivedAccessToken && $this->accessToken->hasExpired();
    }
}
