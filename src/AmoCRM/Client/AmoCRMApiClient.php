<?php

namespace AmoCRM\Client;

use AmoCRM\EntitiesServices\Currencies;
use AmoCRM\EntitiesServices\Account;
use AmoCRM\EntitiesServices\Calls;
use AmoCRM\EntitiesServices\CatalogElements;
use AmoCRM\EntitiesServices\Catalogs;
use AmoCRM\EntitiesServices\Chats\Templates;
use AmoCRM\EntitiesServices\Companies;
use AmoCRM\EntitiesServices\Contacts;
use AmoCRM\EntitiesServices\Customers\BonusPoints;
use AmoCRM\EntitiesServices\Customers\Customers;
use AmoCRM\EntitiesServices\Customers\Statuses as CustomersStatuses;
use AmoCRM\EntitiesServices\Customers\Transactions;
use AmoCRM\EntitiesServices\CustomFieldGroups;
use AmoCRM\EntitiesServices\CustomFields;
use AmoCRM\EntitiesServices\EntityFiles;
use AmoCRM\EntitiesServices\EntityNotes;
use AmoCRM\EntitiesServices\Files;
use AmoCRM\EntitiesServices\Sources;
use AmoCRM\EntitiesServices\EntitySubscriptions;
use AmoCRM\EntitiesServices\EntityTags;
use AmoCRM\EntitiesServices\Events;
use AmoCRM\EntitiesServices\Leads;
use AmoCRM\EntitiesServices\Leads\LossReasons;
use AmoCRM\EntitiesServices\Leads\Pipelines;
use AmoCRM\EntitiesServices\Leads\Statuses;
use AmoCRM\EntitiesServices\Links;
use AmoCRM\EntitiesServices\Products;
use AmoCRM\EntitiesServices\Roles;
use AmoCRM\EntitiesServices\Segments;
use AmoCRM\EntitiesServices\ShortLinks;
use AmoCRM\EntitiesServices\Sources\WebsiteButtons;
use AmoCRM\EntitiesServices\Talks;
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

use function is_callable;

/**
 * Class AmoCRMApiClient
 *
 * @package AmoCRM\Client
 */
class AmoCRMApiClient
{
    public const API_VERSION = 4;
    public const DRIVE_API_VERSION = 'v1.0';

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
     * @var null|int
     */
    private $contextUserId;

    /**
     * @var string|null
     */
    private $userAgent;

    /**
     * @var callable|null
     */
    private $refreshAccessTokenCallback;

    /**
     * @var callable|null
     */
    private $checkHttpStatusCallback;

    /**
     * AmoCRMApiClient constructor.
     *
     * @param string|null $clientId
     * @param string|null $clientSecret
     * @param null|string $redirectUri
     */
    public function __construct(?string $clientId = null, ?string $clientSecret = null, ?string $redirectUri = null)
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

    public function getAccessToken(): ?AccessToken
    {
        return $this->accessToken;
    }

    public function getContextUserId(): ?int
    {
        return $this->contextUserId;
    }

    /**
     * Для админских токеном можно задать пользователя аккаунта, в контексте которого будет сделан запрос
     * Метод возвращает новый объект апи клиента с установленным контекстом
     * @param int|null $contextUserId
     *
     * @return $this
     */
    public function withContextUserId(?int $contextUserId): AmoCRMApiClient
    {
        $apiClient = clone $this;
        $apiClient->contextUserId = $contextUserId;

        return $apiClient;
    }

    public function setUserAgent(?string $userAgent): self
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
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
     * Устанавливаем callback, который будет вызван для обновления AccessToken`a библиотеки
     *
     * @param callable $callable
     * @return $this
     */
    public function setRefreshAccessTokenCallback(callable $callable): self
    {
        $this->refreshAccessTokenCallback = $callable;

        return $this;
    }

    /**
     * Устанавливаем callback, который будет вызван при обработке ответа от сервера.
     * Если нет необходимости в отработке стандартной логики обработки ответа, то callback должен возвращать true
     *
     * @param callable $callable
     * @return $this
     */
    public function setCheckHttpStatusCallback(callable $callable): self
    {
        $this->checkHttpStatusCallback = $callable;

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

        $request = new AmoCRMApiRequest(
            $this->getAccessToken(),
            $oAuthClient,
            $this->getContextUserId(),
            $this->getUserAgent()
        );

        if ($this->refreshAccessTokenCallback !== null) {
            $request->setRefreshAccessTokenCallback($this->refreshAccessTokenCallback);
        }

        if ($this->checkHttpStatusCallback !== null) {
            $request->setCustomCheckStatusCallback($this->checkHttpStatusCallback);
        }

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
     * @return WebsiteButtons
     * @throws AmoCRMMissedTokenException
     */
    public function websiteButtons(): WebsiteButtons
    {
        $request = $this->buildRequest();

        return new WebsiteButtons($request);
    }

    /**
     * Метод вернет объект аккаунта
     *
     * @param string|null $domain
     *
     * @return Files
     * @throws AmoCRMMissedTokenException
     */
    public function files(?string $domain = null): Files
    {
        $request = $this->buildRequest();
        $request->setRequestDomain($domain ?? 'https://drive.amocrm.');

        return new Files($request);
    }

    /**
     * Метод вернет объект для работы со связями файлов с сущностями
     *
     * @param string $entityType
     * @param int $entityId
     *
     * @return EntityFiles
     * @throws InvalidArgumentException|AmoCRMMissedTokenException
     */
    public function entityFiles(string $entityType, int $entityId): EntityFiles
    {
        return (new EntityFiles($this->buildRequest()))
            ->setEntityType($entityType)
            ->setEntityId($entityId);
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
     * Метод вернет объект Источников
     *
     * @return Sources
     * @throws AmoCRMMissedTokenException
     */
    public function sources(): Sources
    {
        $request = $this->buildRequest();

        return new Sources($request);
    }

    /**
     * Метод вернет объект шаблонов чатов
     *
     * @return Templates
     * @throws AmoCRMMissedTokenException
     */
    public function chatTemplates(): Templates
    {
        $request = $this->buildRequest();

        return new Templates($request);
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
     * @deprecated Скорее всего будет удалено в релизе 0.8 (Ориентировачно осень-зима 2021)
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

        return new Products($request);
    }

    public function talks(): Talks
    {
        return new Talks($this->buildRequest());
    }

    public function entitySubscriptions(string $entityType): EntitySubscriptions
    {
        return (new EntitySubscriptions($this->buildRequest()))
            ->setEntityType($entityType);
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

    /**
     * Метод вернет объект валют
     *
     * @since Release Spring 2022
     * @return Currencies
     * @throws AmoCRMMissedTokenException
     */
    public function currencies(): Currencies
    {
        return new Currencies($this->buildRequest());
    }
}
