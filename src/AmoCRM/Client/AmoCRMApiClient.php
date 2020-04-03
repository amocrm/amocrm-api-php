<?php

namespace AmoCRM\Client;

use AmoCRM\EntitiesServices\Account;
use AmoCRM\EntitiesServices\Leads;
use AmoCRM\OAuth\AmoCRMOAuth;
use League\OAuth2\Client\Token\AccessToken;

class AmoCRMApiClient
{
    public const API_VERSION = 4;

    protected const HOST_PATTERN = '%s.amocrm.%s';

    /**
     * @var AmoCRMOAuth
     */
    protected $oAuthClient;
    /**
     * @var string
     */
    protected $accountBaseDomain;

    /**
     * @var AccessToken
     */
    private $accessToken;

    /**
     * @var callable
     */
    private $accessTokenRefreshCallback;

    /**
     * AmoCRMApiClient constructor.
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUri
     */
    public function __construct(string $clientId, string $clientSecret, string $redirectUri)
    {
        $this->oAuthClient = new AmoCRMOAuth($clientId, $clientSecret, $redirectUri);
    }

    /**
     * Устанавливаем Access Token, который будет использован при запросах
     * @param AccessToken $accessToken
     * @return $this
     */
    public function setAccessToken(AccessToken $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Устанавливаем базовый домен аккаунта в amoCRM, который будет использован при запросах
     * @param string $domain
     * @return $this
     */
    public function setAccountBaseDomain(string $domain): self
    {
        $this->oAuthClient->setBaseDomain($domain);
        $this->accountBaseDomain = $domain;

        return $this;
    }

    /**
     * Получить базовый домен аккаунта в amoCRM, который будет использован при запросах
     * @return $this
     */
    public function getAccountBaseDomain(): string
    {
        return $this->accountBaseDomain;
    }

    /**
     * Устанавливаем callback, который будет вызван при обновлении AccessToken'а библиотеки
     * @param callable $callback
     * @return $this
     */
    public function onAccessTokenRefresh(callable $callback): self
    {
        $this->accessTokenRefreshCallback = $callback;

        return $this;
    }

    /**
     * Метод строит объект для совершения запросов для сервисов сущностей
     * @return AmoCRMApiRequest
     */
    private function buildRequest(): AmoCRMApiRequest
    {
        $oAuthClient = $this->getOAuthClient();
        $oAuthClient->setAccessTokenRefreshCallback($this->accessTokenRefreshCallback);

        $request = new AmoCRMApiRequest($this->accessToken, $oAuthClient);

        return $request;
    }

    /**
     * Метод вернет oAuthClient
     * @return AmoCRMOAuth
     */
    public function getOAuthClient(): AmoCRMOAuth
    {
        return $this->oAuthClient;
    }

    /**
     * Метод вернет объект сделок
     * @TODO make static
     * @return Leads
     */
    public function leads()
    {
        $request = $this->buildRequest();

        return new Leads($request);
    }

    /**
     * Метод вернет объект сделок
     * @TODO make static
     * @return Account
     */
    public function account()
    {
        $request = $this->buildRequest();

        return new Account($request);
    }
}
