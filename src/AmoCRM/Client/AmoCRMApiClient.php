<?php

namespace AmoCRM\Client;

use AmoCRM\AmoCRM\EntitiesServices\Leads\Pipelines;
use AmoCRM\AmoCRM\EntitiesServices\Leads\Statuses;
use AmoCRM\EntitiesServices\Account;
use AmoCRM\EntitiesServices\CatalogElements;
use AmoCRM\EntitiesServices\Catalogs;
use AmoCRM\EntitiesServices\Companies;
use AmoCRM\EntitiesServices\Contacts;
use AmoCRM\EntitiesServices\CustomFieldGroups;
use AmoCRM\EntitiesServices\CustomFields;
use AmoCRM\EntitiesServices\EntityNotes;
use AmoCRM\EntitiesServices\EntityTags;
use AmoCRM\EntitiesServices\Events;
use AmoCRM\EntitiesServices\Leads;
use AmoCRM\EntitiesServices\Roles;
use AmoCRM\EntitiesServices\Segments;
use AmoCRM\EntitiesServices\Tasks;
use AmoCRM\EntitiesServices\Unsorted;
use AmoCRM\EntitiesServices\Webhooks;
use AmoCRM\EntitiesServices\Widgets;
use AmoCRM\OAuth\AmoCRMOAuth;
use Exception;
use League\OAuth2\Client\Token\AccessToken;

class AmoCRMApiClient
{
    public const API_VERSION = 4;

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
     * Метод вернет объект примечаний сущности
     * @param string $entityType
     * @return EntityNotes
     * @throws Exception
     */
    public function notes(string $entityType)
    {
        $request = $this->buildRequest();

        $service = new EntityNotes($request);

        if (!is_null($entityType)) {
            $service->setEntityType($entityType);
        }

        return $service;
    }

    /**
     * Метод вернет объект тегов
     * @param string $entityType
     * @return EntityTags
     * @throws Exception
     */
    public function tags(string $entityType)
    {
        $request = $this->buildRequest();

        $service = new EntityTags($request);

        if (!is_null($entityType)) {
            $service->setEntityType($entityType);
        }

        return $service;
    }

    /**
     * Метод вернет объект задач
     * @return Tasks
     */
    public function tasks()
    {
        $request = $this->buildRequest();

        $service = new Tasks($request);

        return $service;
    }

    /**
     * Метод вернет объект сделок
     * @return Leads
     */
    public function leads(): Leads
    {
        $request = $this->buildRequest();

        return new Leads($request);
    }

    /**
     * Метод вернет объект контактов
     * @return Contacts
     */
    public function contacts(): Contacts
    {
        $request = $this->buildRequest();

        return new Contacts($request);
    }

    /**
     * Метод вернет объект компаний
     * @return Companies
     */
    public function companies()
    {
        $request = $this->buildRequest();

        return new Companies($request);
    }

    /**
     * Метод вернет объект каталогов
     * @return Catalogs
     */
    public function catalogs()
    {
        $request = $this->buildRequest();

        return new Catalogs($request);
    }

    /**
     * Метод вернет объект элементов каталогов
     *
     * @param int|null $catalogId
     *
     * @return CatalogElements
     */
    public function catalogElements(int $catalogId = null)
    {
        $request = $this->buildRequest();
        $service = new CatalogElements($request);
        if (!is_null($catalogId)) {
            $service->setEntityId($catalogId);
        }

        return $service;
    }

    /**
     * Метод вернет объект кастом полей
     * @param string $entityType
     * @return CustomFields
     * @throws Exception
     */
    public function customFields(string $entityType)
    {
        $request = $this->buildRequest();

        $service = new CustomFields($request);

        if (!is_null($entityType)) {
            $service->setEntityType($entityType);
        }

        return $service;
    }

    /**
     * Метод вернет объект групп кастом полей (табы в карточке)
     * @param string|null $entityType
     * @return CustomFieldGroups
     * @throws Exception
     */
    public function customFieldGroups(string $entityType = null)
    {
        $request = $this->buildRequest();

        $service = new CustomFieldGroups($request);

        if (!is_null($entityType)) {
            $service->setEntityType($entityType);
        }

        return $service;
    }

    /**
     * Метод вернет объект аккаунта
     * @return Account
     */
    public function account()
    {
        $request = $this->buildRequest();

        return new Account($request);
    }

    /**
     * Метод вернет объект ролей пользователей
     * @return Roles
     */
    public function roles(): Roles
    {
        $request = $this->buildRequest();

        return new Roles($request);
    }


    /**
     * Метод вернет объект сегментов покупателей
     * @return Segments
     */
    public function customersSegments(): Segments
    {
        $request = $this->buildRequest();

        return new Segments($request);
    }

    /**
     * Метод вернет объект событий
     * @return Events
     */
    public function events(): Events
    {
        $request = $this->buildRequest();

        return new Events($request);
    }

    /**
     * Метод вернет объект хуков
     * @return Webhooks
     */
    public function webhooks(): Webhooks
    {
        $request = $this->buildRequest();

        return new Webhooks($request);
    }

    /**
     * Метод вернет объект неразобранного
     * @return Unsorted
     */
    public function unsorted(): Unsorted
    {
        $request = $this->buildRequest();

        return new Unsorted($request);
    }

    /**
     * Метод вернет объект воронок
     * @return Pipelines
     */
    public function pipelines(): Pipelines
    {
        $request = $this->buildRequest();

        return new Pipelines($request);
    }


    /**
     * Метод вернет объект статусов
     *
     * @param int|null $pipelineId
     *
     * @return Statuses
     */
    public function statuses(int $pipelineId = null): Statuses
    {
        $request = $this->buildRequest();
        $service = new Statuses($request);
        if (!is_null($pipelineId)) {
            $service->setEntityId($pipelineId);
        }
        return $service;
    }

    /**
     * Метод вернет объект виджетов
     *
     * @return Widgets
     */
    public function widgets(): Widgets
    {
        $request = $this->buildRequest();

        return new Widgets($request);
    }


    /**
     * Метод вернет объект запроса для любых запросов в amoCRM с текущим Access Token
     * @return AmoCRMApiRequest
     */
    public function getRequest(): AmoCRMApiRequest
    {
        return $this->buildRequest();
    }
}
