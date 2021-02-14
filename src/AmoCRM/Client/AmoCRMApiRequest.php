<?php

namespace AmoCRM\Client;

use AmoCRM\Exceptions\AmoCRMApiConnectExceptionException;
use AmoCRM\Exceptions\AmoCRMApiErrorResponseException;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMApiHttpClientException;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Exceptions\AmoCRMApiTooManyRedirectsException;
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
    public const GET_REQUEST = 'GET';
    public const PATCH_REQUEST = 'PATCH';
    public const DELETE_REQUEST = 'DELETE';

    public const CONNECT_TIMEOUT = 5;
    public const REQUEST_TIMEOUT = 20;
    //TODO Do not forget to change this on each release
    public const LIBRARY_VERSION = '0.4.6';
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
     * @var array
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

    /**
     * AmoCRMApiRequest constructor.
     * @param AccessTokenInterface $accessToken
     * @param AmoCRMOAuth $oAuthClient
     */
    public function __construct(AccessTokenInterface $accessToken, AmoCRMOAuth $oAuthClient)
    {
        $this->accessToken = $accessToken;
        $this->oAuthClient = $oAuthClient;
        $this->httpClient = $oAuthClient->getHttpClient();
    }

    /**
     * Обновляем в библиотеке oAuth аккаунт токен по рефрешу
     * @throws AmoCRMoAuthApiException
     */
    private function refreshAccessToken()
    {
        $newAccessToken = $this->oAuthClient->getAccessTokenByRefreshToken($this->accessToken);
        $this->accessToken = $newAccessToken;
    }

    /**
     * @param string $method
     * @param array $body
     * @param array $queryParams
     * @param array $headers
     * @param bool $needToRefresh
     *
     * @return array
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiNoContentException
     */
    public function post(
        string $method,
        array $body = [],
        array $queryParams = [],
        array $headers = [],
        bool $needToRefresh = false
    ): array {
        if ($this->accessToken->hasExpired()) {
            $needToRefresh = true;
        }

        if ($needToRefresh) {
            $this->refreshAccessToken();
        }

        $headers = array_merge($headers, $this->getBaseHeaders());

        $this->lastHttpMethod = self::POST_REQUEST;
        $this->lastMethod = $this->oAuthClient->getAccountUrl() . $method;
        $this->lastBody = $body;
        $this->lastQueryParams = $queryParams;

        try {
            $response = $this->httpClient->request(
                self::POST_REQUEST,
                $this->oAuthClient->getAccountUrl() . $method,
                [
                    'json' => $body,
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
            if ($needToRefresh) {
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
        if ($this->accessToken->hasExpired()) {
            $needToRefresh = true;
        }

        if ($needToRefresh) {
            $this->refreshAccessToken();
        }

        $headers = array_merge($headers, $this->getBaseHeaders());

        $this->lastHttpMethod = self::PATCH_REQUEST;
        $this->lastMethod = $this->oAuthClient->getAccountUrl() . $method;
        $this->lastBody = $body;
        $this->lastQueryParams = $queryParams;

        try {
            $response = $this->httpClient->request(
                self::PATCH_REQUEST,
                $this->oAuthClient->getAccountUrl() . $method,
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
            if ($needToRefresh) {
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
     * @throws AmoCRMApiNoContentException
     */
    public function delete(
        string $method,
        array $body = [],
        array $queryParams = [],
        array $headers = [],
        bool $needToRefresh = false
    ): array {
        if ($this->accessToken->hasExpired()) {
            $needToRefresh = true;
        }

        if ($needToRefresh) {
            $this->refreshAccessToken();
        }

        $headers = array_merge($headers, $this->getBaseHeaders());

        $this->lastHttpMethod = self::DELETE_REQUEST;
        $this->lastMethod = $this->oAuthClient->getAccountUrl() . $method;
        $this->lastBody = $body;
        $this->lastQueryParams = $queryParams;

        try {
            $response = $this->httpClient->request(
                self::DELETE_REQUEST,
                $this->oAuthClient->getAccountUrl() . $method,
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
            if ($needToRefresh) {
                throw $e;
            }

            return $this->delete($method, $body, $queryParams, $headers, true);
        } catch (AmoCRMApiNoContentException $e) {
            return $response = ['result' => true];
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
        if ($this->accessToken->hasExpired()) {
            $needToRefresh = true;
        }

        if ($needToRefresh) {
            $this->refreshAccessToken();
        }

        $headers = array_merge($headers, $this->getBaseHeaders());
        $method = $this->oAuthClient->getAccountUrl() .
            str_replace(
                $this->oAuthClient->getAccountUrl(),
                '',
                $method
            );

        $this->lastHttpMethod = self::GET_REQUEST;
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
            if ($needToRefresh) {
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

        $headers['User-Agent'] = self::USER_AGENT;
        $headers['X-Library-Version'] = self::USER_AGENT;

        return $headers;
    }

    /**
     * @return array
     */
    public function getLastRequestInfo(): array
    {
        return [
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

        return 'curl "' . $url . '" -X "' . $requestHttpMethod . '" -d"' . http_build_query($this->lastBody) . '"';
    }
}
