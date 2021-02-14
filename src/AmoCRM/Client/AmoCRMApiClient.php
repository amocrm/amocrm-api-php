<?php

namespace AmoCRM\Client;

use AmoCRM\AmoCRM\EntitiesServices\Customers\BonusPoints;
use AmoCRM\AmoCRM\EntitiesServices\Links;
use AmoCRM\AmoCRM\EntitiesServices\Products;
use AmoCRM\EntitiesServices\Calls;
use AmoCRM\EntitiesServices\Customers\Transactions;
use AmoCRM\EntitiesServices\Leads\LossReasons;
use AmoCRM\EntitiesServices\Leads\Pipelines;
use AmoCRM\EntitiesServices\Leads\Statuses;
use AmoCRM\EntitiesServices\Account;
use AmoCRM\EntitiesServices\CatalogElements;
use AmoCRM\EntitiesServices\Catalogs;
use AmoCRM\EntitiesServices\Companies;
use AmoCRM\EntitiesServices\Contacts;
use AmoCRM\EntitiesServices\Customers\Customers;
use AmoCRM\EntitiesServices\CustomFieldGroups;
use AmoCRM\EntitiesServices\CustomFields;
use AmoCRM\EntitiesServices\EntityNotes;
use AmoCRM\EntitiesServices\EntityTags;
use AmoCRM\EntitiesServices\Events;
use AmoCRM\EntitiesServices\Leads;
use AmoCRM\EntitiesServices\Roles;
use AmoCRM\EntitiesServices\Segments;
use AmoCRM\EntitiesServices\ShortLinks;
use AmoCRM\EntitiesServices\Tasks;
use AmoCRM\EntitiesServices\Unsorted;
use AmoCRM\EntitiesServices\Users;
use AmoCRM\EntitiesServices\Webhooks;
use AmoCRM\EntitiesServices\Widgets;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\OAuth\AmoCRMOAuth;
use League\OAuth2\Client\Token\AccessToken;
use AmoCRM\EntitiesServices\Customers\Statuses as CustomersStatuses;

use function is_callable;

/**
 * Class AmoCRMApiClient
 *
 * @package AmoCRM\Client
 */
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
     * @param null|string $redirectUri
     */
    public function __construct(string $clientId, string $clientSecret, ?string $redirectUri)
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
     *
     * @return AmoCRMApiRequest
     * @throws AmoCRMMissedTokenException
     */
    private function buildRequest(): AmoCRMApiRequest
    {
        if (!$this->isAccessTokenSet()) {
            throw new AmoCRMMissedTokenException();
        }

        $oAuthClient = $this->getOAuthClient();

        $oAuthClient->setAccessTokenRefreshCallback(
            function (AccessToken $accessToken, string $baseAccountDomain) use ($oAuthClient) {
                $this->setAccessToken($accessToken);

                if (is_callable($this->accessTokenRefreshCallback)) {
                    $callback = $this->accessTokenRefreshCallback;
                    $callback($accessToken, $baseAccountDomain);
                }
            }
        );

        return new AmoCRMApiRequest($this->accessToken, $oAuthClient);
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
     *
     * @param string $entityType
     *
     * @return EntityNotes
     * @throws InvalidArgumentException|AmoCRMMissedTokenException
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
     *
     * @param string $entityType
     *
     * @return EntityTags
     * @throws InvalidArgumentException|AmoCRMMissedTokenException
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
     *
     * @return Tasks
     * @throws AmoCRMMissedTokenException
     */
    public function tasks()
    {
        $request = $this->buildRequest();

        return new Tasks($request);
    }

    /**
     * Метод вернет объект сделок
     *
     * @return Leads
     * @throws AmoCRMMissedTokenException
     */
    public function leads(): Leads
    {
        $request = $this->buildRequest();

        return new Leads($request);
    }

    /**
     * Метод вернет объект контактов
     *
     * @return Contacts
     * @throws AmoCRMMissedTokenException
     */
    public function contacts(): Contacts
    {
        $request = $this->buildRequest();

        return new Contacts($request);
    }

    /**
     * Метод вернет объект компаний
     *
     * @return Companies
     * @throws AmoCRMMissedTokenException
     */
    public function companies()
    {
        $request = $this->buildRequest();

        return new Companies($request);
    }

    /**
     * Метод вернет объект каталогов
     *
     * @return Catalogs
     * @throws AmoCRMMissedTokenException
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
     * @throws InvalidArgumentException|AmoCRMMissedTokenException
     */
    public function catalogElements(int $catalogId = null)
    {
        $request = $this->buildRequest();
        $service = new CatalogElements($request);
        if (!is_null($catalogId)) {
            $service->setEntityId($catalogId);
        } else {
            if ($catalogId < EntityTypesInterface::MIN_CATALOG_ID) {
                throw new InvalidArgumentException('Catalog id is invalid');
            }
        }

        return $service;
    }

    /**
     * Метод вернет объект кастом полей
     *
     * @param string $entityType
     *
     * @return CustomFields
     * @throws InvalidArgumentException|AmoCRMMissedTokenException
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
     * Метод вернет объект связи сущностей
     *
     * @param string $entityType
     *
     * @return Links
     * @throws InvalidArgumentException|AmoCRMMissedTokenException
     */
    public function links(string $entityType): Links
    {
        return (new Links($this->buildRequest()))
            ->setEntityType($entityType);
    }

    /**
     * Метод вернет объект групп кастом полей (табы в карточке)
     *
     * @param string|null $entityType
     *
     * @return CustomFieldGroups
     * @throws InvalidArgumentException|AmoCRMMissedTokenException
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
     *
     * @return Account
     * @throws AmoCRMMissedTokenException
     */
    public function account(): Account
    {
        $request = $this->buildRequest();

        return new Account($request);
    }

    /**
     * Метод вернет объект ролей пользователей
     *
     * @return Roles
     * @throws AmoCRMMissedTokenException
     */
    public function roles(): Roles
    {
        $request = $this->buildRequest();

        return new Roles($request);
    }

    /**
     * Метод вернет объект пользователей
     *
     * @return Users
     * @throws AmoCRMMissedTokenException
     */
    public function users(): Users
    {
        $request = $this->buildRequest();

        return new Users($request);
    }


    /**
     * Метод вернет объект сегментов покупателей
     *
     * @return Segments
     * @throws AmoCRMMissedTokenException
     */
    public function customersSegments(): Segments
    {
        $request = $this->buildRequest();

        return new Segments($request);
    }

    /**
     * Метод вернет объект событий
     *
     * @return Events
     * @throws AmoCRMMissedTokenException
     */
    public function events(): Events
    {
        $request = $this->buildRequest();

        return new Events($request);
    }

    /**
     * Метод вернет объект хуков
     *
     * @return Webhooks
     * @throws AmoCRMMissedTokenException
     */
    public function webhooks(): Webhooks
    {
        $request = $this->buildRequest();

        return new Webhooks($request);
    }

    /**
     * Метод вернет объект неразобранного
     *
     * @return Unsorted
     * @throws AmoCRMMissedTokenException
     */
    public function unsorted(): Unsorted
    {
        $request = $this->buildRequest();

        return new Unsorted($request);
    }

    /**
     * Метод вернет объект воронок
     *
     * @return Pipelines
     * @throws AmoCRMMissedTokenException
     */
    public function pipelines(): Pipelines
    {
        $request = $this->buildRequest();

        return new Pipelines($request);
    }


    /**
     * Метод вернет объект статусов
     *
     * @param int $pipelineId
     *
     * @return Statuses
     * @throws AmoCRMMissedTokenException
     */
    public function statuses(int $pipelineId): Statuses
    {
        $request = $this->buildRequest();
        $service = new Statuses($request);
        $service->setEntityId($pipelineId);

        return $service;
    }

    /**
     * Метод вернет объект виджетов
     *
     * @return Widgets
     * @throws AmoCRMMissedTokenException
     */
    public function widgets(): Widgets
    {
        $request = $this->buildRequest();

        return new Widgets($request);
    }

    /**
     * Метод вернет объект коротких ссылок
     *
     * @return ShortLinks
     * @throws AmoCRMMissedTokenException
     */
    public function shortLinks(): ShortLinks
    {
        $request = $this->buildRequest();

        return new ShortLinks($request);
    }

    /**
     * Метод вернет объект причин отказа
     *
     * @return LossReasons
     * @throws AmoCRMMissedTokenException
     */
    public function lossReasons(): LossReasons
    {
        $request = $this->buildRequest();

        return new LossReasons($request);
    }

    /**
     * Метод вернет объект транзакций
     *
     * @return Transactions
     * @throws AmoCRMMissedTokenException
     */
    public function transactions(): Transactions
    {
        $request = $this->buildRequest();

        return new Transactions($request);
    }

    /**
     * Метод вернет объект покупателей
     *
     * @return Customers
     * @throws AmoCRMMissedTokenException
     */
    public function customers(): Customers
    {
        $request = $this->buildRequest();

        return new Customers($request);
    }

    /**
     * Метод вернет объект статусов покупателей
     *
     * @return CustomersStatuses
     * @throws AmoCRMMissedTokenException
     */
    public function customersStatuses(): CustomersStatuses
    {
        $request = $this->buildRequest();

        return new CustomersStatuses($request);
    }

    /**
     * Метод вернет объект для списания/начисления бонусных баллов покупателю
     *
     * @return BonusPoints
     * @throws AmoCRMMissedTokenException
     */
    public function customersBonusPoints(): BonusPoints
    {
        $request = $this->buildRequest();

        return new BonusPoints($request);
    }

    /**
     * Метод вернет объект звонков
     *
     * @return Calls
     * @throws AmoCRMMissedTokenException
     */
    public function calls(): Calls
    {
        $request = $this->buildRequest();

        return new Calls($request);
    }

    /**
     * Метод вернет объект Продуктов
     *
     * @return Products
     * @throws AmoCRMMissedTokenException
     */
    public function products(): Products
    {
        $request = $this->buildRequest();

        return  new Products($request);
    }

    /**
     * Метод вернет объект запроса для любых запросов в amoCRM с текущим Access Token
     *
     * @return AmoCRMApiRequest
     * @throws AmoCRMMissedTokenException
     */
    public function getRequest(): AmoCRMApiRequest
    {
        return $this->buildRequest();
    }

    /**
     * Проверка, установлен ли токен
     * @return bool
     */
    public function isAccessTokenSet(): bool
    {
        return $this->accessToken !== null;
    }
}
