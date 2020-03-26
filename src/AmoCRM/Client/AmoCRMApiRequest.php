<?php

namespace AmoCRM\Client;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\OAuth\AmoCRMOAuth;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\ResponseInterface;

class AmoCRMApiRequest
{
    const POST_REQUEST = 'POST';
    const GET_REQUEST = 'GET';
    const PATCH_REQUEST = 'PATCH';
    const DELETE_REQUEST = 'DELETE';

    const CONNECT_TIMEOUT = 3;
    const REQUEST_TIMEOUT = 20;
    const USER_AGENT = 'amoCRM-API-Library/1.0';

    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const NO_CONTENT = 204;

    const UNAUTHORIZED = 401;

    const SUCCESS_STATUSES = [
        self::OK,
        self::CREATED,
        self::ACCEPTED,
        self::NO_CONTENT,
    ];

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
     * @param array $headers
     * @param array $queryParams
     * @return ResponseInterface
     * @throws AmoCRMoAuthApiException
     */
    public function post(
        string $method,
        array $body = [],
        array $queryParams = [],
        array $headers = []
    ): ResponseInterface {
        $headers = array_merge($headers, $this->getBaseHeaders());

        try {
            $response = $this->httpClient->request(
                self::POST_REQUEST,
                $this->oAuthClient->getAccountUrl() . $method,
                [
                    'body' => $body,
                    'connect_timeout' => self::CONNECT_TIMEOUT,
                    'headers' => $headers,
                    'http_errors' => false,
                    'query' => $queryParams,
                    'timeout' => self::REQUEST_TIMEOUT,
                ]
            );
        } catch (GuzzleException $e) {
            throw new AmoCRMoAuthApiException("Request problem: {$e->getMessage()}");
        }

        $this->parseResponse($response);
        //too validate response and exception


        return $response;
    }

    /**
     * @param string $method
     * @param array $queryParams
     * @param array $headers
     * @param bool $needToRefresh
     * @return ResponseInterface
     * @throws AmoCRMoAuthApiException
     */
    public function get(
        string $method,
        array $queryParams = [],
        array $headers = [],
        bool $needToRefresh = false
    ): ResponseInterface {
        if ($this->accessToken->hasExpired()) {
            $needToRefresh = true;
        }

        if ($needToRefresh) {
            $this->refreshAccessToken();
        }

        $headers = array_merge($headers, $this->getBaseHeaders());

        try {
            $response = $this->httpClient->request(
                self::GET_REQUEST,
                $this->oAuthClient->getAccountUrl() . $method,
                [
                    'connect_timeout' => self::CONNECT_TIMEOUT,
                    'headers' => $headers,
                    'http_errors' => false,
                    'query' => $queryParams,
                    'timeout' => self::REQUEST_TIMEOUT,
                ]
            );
        } catch (GuzzleException $e) {
            throw new AmoCRMoAuthApiException("Request problem: {$e->getMessage()}");
        }

        /**
         * В случае получения ошибки авторизации, пробуем обновить токен 1 раз,
         * если не получилось, то тогда уже выкидываем Exception
         */
        try {
            $this->parseResponse($response);
        } catch (AmoCRMoAuthApiException $e) {
            if ($needToRefresh) {
                throw $e;
            }

            return $this->get($method, $queryParams, $headers, true);
        }
        //too validate response and exception

        return $response;
    }

    /**
     * @param ResponseInterface $response
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiException
     */
    protected function checkHttpStatus(ResponseInterface $response): void
    {
        if ((int)$response->getStatusCode() === self::UNAUTHORIZED) {
            throw new AmoCRMoAuthApiException("Unauthorized");
        }

        if (!in_array((int)$response->getStatusCode(), self::SUCCESS_STATUSES, true)) {
            $exception = new AmoCRMApiException("Invalid http status: {$response->getStatusCode()}");
//            $exception->setErrors();
            throw $exception;
        }
    }

    /**
     * @param ResponseInterface $response
     * @return string
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiException
     */
    private function parseResponse(ResponseInterface $response): string
    {
        $this->checkHttpStatus($response);

        $bodyContents = $response->getBody()->getContents();

        if (!($decodedBody = json_decode($bodyContents, true))) {
            $exception = new AmoCRMoAuthApiException("Body is not a json: {$bodyContents}");
            //todo set detail and title
            return $exception;
        }

        return $decodedBody;
    }

    private function getBaseHeaders()
    {
        $headers = $this->oAuthClient->getAuthorizationHeaders($this->accessToken);

        $headers['User-Agent'] = self::USER_AGENT;

        return $headers;
    }
}
