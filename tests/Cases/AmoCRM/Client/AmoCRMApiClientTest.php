<?php

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\EntitiesServices\Account;
use AmoCRM\EntitiesServices\CatalogElements;
use AmoCRM\EntitiesServices\Catalogs;
use AmoCRM\EntitiesServices\Companies;
use AmoCRM\EntitiesServices\Contacts;
use AmoCRM\EntitiesServices\Customers\Customers;
use AmoCRM\EntitiesServices\Customers\Statuses;
use AmoCRM\EntitiesServices\Customers\Transactions;
use AmoCRM\EntitiesServices\CustomFieldGroups;
use AmoCRM\EntitiesServices\CustomFields;
use AmoCRM\EntitiesServices\EntityNotes;
use AmoCRM\EntitiesServices\EntityTags;
use AmoCRM\EntitiesServices\Events;
use AmoCRM\EntitiesServices\Leads;
use AmoCRM\EntitiesServices\Leads\LossReasons;
use AmoCRM\EntitiesServices\Leads\Pipelines;
use AmoCRM\EntitiesServices\Roles;
use AmoCRM\EntitiesServices\Segments;
use AmoCRM\EntitiesServices\Tasks;
use AmoCRM\EntitiesServices\Unsorted;
use AmoCRM\EntitiesServices\Users;
use AmoCRM\EntitiesServices\Webhooks;
use AmoCRM\EntitiesServices\Widgets;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\OAuth\AmoCRMOAuth;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\TestCase;

class AmoCRMApiClientTest extends TestCase
{
    /**
     * @var AmoCRMApiClient
     */
    private $apiClient;

    public function setUp(): void
    {
        $this->apiClient = new AmoCRMApiClient('xxx', 'xxx', 'xxx');
        $this->apiClient->setAccessToken(new AccessToken([
            'access_token' => 'xxx',
            'refresh_token' => 'xxx',
            'expires' => 1893456000,
            'baseDomain' => 'example.amocrm.ru',
        ]));
    }

    public function testCompanies()
    {
        $this->assertInstanceOf(Companies::class, $this->apiClient->companies());
    }

    public function testCustomFields()
    {
        $this->assertInstanceOf(
            CustomFields::class,
            $this->apiClient->customFields(EntityTypesInterface::LEADS)
        );
        $this->assertInstanceOf(
            CustomFields::class,
            $this->apiClient->customFields(EntityTypesInterface::CONTACTS)
        );
        $this->assertInstanceOf(
            CustomFields::class,
            $this->apiClient->customFields(EntityTypesInterface::COMPANIES)
        );
        $this->assertInstanceOf(
            CustomFields::class,
            $this->apiClient->customFields(EntityTypesInterface::CATALOGS . ':2000')
        );
        $this->assertInstanceOf(
            CustomFields::class,
            $this->apiClient->customFields(EntityTypesInterface::CUSTOMERS)
        );
        $this->assertInstanceOf(
            CustomFields::class,
            $this->apiClient->customFields(EntityTypesInterface::CUSTOMERS_SEGMENTS)
        );

        $this->expectException(InvalidArgumentException::class);
        $this->assertInstanceOf(
            CustomFields::class,
            $this->apiClient->customFields(EntityTypesInterface::TASKS)
        );

        $this->expectException(InvalidArgumentException::class);
        $this->assertInstanceOf(
            CustomFields::class,
            $this->apiClient->customFields(EntityTypesInterface::CATALOGS . ':' . 1000)
        );
    }

    public function testCatalogs()
    {
        $this->assertInstanceOf(Catalogs::class, $this->apiClient->catalogs());
    }

    public function testEvents()
    {
        $this->assertInstanceOf(Events::class, $this->apiClient->events());
    }

    public function testContacts()
    {
        $this->assertInstanceOf(Contacts::class, $this->apiClient->contacts());
    }

    public function testCatalogElements()
    {
        $catalogElementsService = $this->apiClient->catalogElements(1200);
        $this->assertInstanceOf(CatalogElements::class, $catalogElementsService);
        $this->assertEquals(1200, $catalogElementsService->getEntityId());

        $this->expectException(InvalidArgumentException::class);
        $this->assertInstanceOf(CatalogElements::class, $this->apiClient->catalogElements());

        $this->expectException(InvalidArgumentException::class);
        $this->assertInstanceOf(CatalogElements::class, $this->apiClient->catalogElements(800));
    }

    public function testUnsorted()
    {
        $this->assertInstanceOf(Unsorted::class, $this->apiClient->unsorted());
    }

    public function testOnAccessTokenRefresh()
    {
        $this->assertInstanceOf(
            AmoCRMApiClient::class,
            $this->apiClient->onAccessTokenRefresh(function () {})
        );
        $callback = $this->_getInnerPropertyValueByReflection('accessTokenRefreshCallback');
        $this->assertIsCallable($callback);
    }

    public function testSetAccountBaseDomain()
    {
        $this->assertInstanceOf(
            AmoCRMApiClient::class,
            $this->apiClient->setAccountBaseDomain('example.amocrm.ru')
        );
        $this->assertIsString($this->apiClient->getAccountBaseDomain());
        $this->assertSame($this->apiClient->getAccountBaseDomain(), 'example.amocrm.ru');
    }

    public function testTags()
    {
        $this->assertInstanceOf(
            EntityTags::class,
            $this->apiClient->tags(EntityTypesInterface::LEADS)
        );
        $this->assertInstanceOf(
            EntityTags::class,
            $this->apiClient->tags(EntityTypesInterface::CONTACTS)
        );
        $this->assertInstanceOf(
            EntityTags::class,
            $this->apiClient->tags(EntityTypesInterface::COMPANIES)
        );
        $this->assertInstanceOf(
            EntityTags::class,
            $this->apiClient->tags(EntityTypesInterface::CUSTOMERS)
        );

        $this->expectException(InvalidArgumentException::class);
        $this->assertInstanceOf(
            EntityTags::class,
            $this->apiClient->tags(EntityTypesInterface::TASKS)
        );
    }

    public function testWebhooks()
    {
        $this->assertInstanceOf(Webhooks::class, $this->apiClient->webhooks());
    }

    public function testWidgets()
    {
        $this->assertInstanceOf(Widgets::class, $this->apiClient->widgets());
    }

    public function testRoles()
    {
        $this->assertInstanceOf(Roles::class, $this->apiClient->roles());
    }

    public function testPipelines()
    {
        $this->assertInstanceOf(Pipelines::class, $this->apiClient->pipelines());
    }

    public function testCustomersSegments()
    {
        $this->assertInstanceOf(Segments::class, $this->apiClient->customersSegments());
    }

    public function testGetRequest()
    {
        $this->assertInstanceOf(AmoCRMApiRequest::class, $this->apiClient->getRequest());
    }

    public function testGetOAuthClient()
    {
        $this->assertInstanceOf(AmoCRMOAuth::class, $this->apiClient->getOAuthClient());
    }

    public function testLossReasons()
    {
        $this->assertInstanceOf(LossReasons::class, $this->apiClient->lossReasons());
    }

    public function testAccount()
    {
        $this->assertInstanceOf(Account::class, $this->apiClient->account());
    }

    public function testCustomersStatuses()
    {
        $this->assertInstanceOf(Statuses::class, $this->apiClient->customersStatuses());
    }

    public function testStatuses()
    {
        $this->assertInstanceOf(Leads\Statuses::class, $this->apiClient->statuses(1));

        $this->assertInstanceOf(
            Leads\Statuses::class,
            $this->apiClient->statuses(100)
        );

        $this->expectException(TypeError::class);
        $this->assertInstanceOf(
            Leads\Statuses::class,
            $this->apiClient->statuses(EntityTypesInterface::TASKS)
        );
    }

    public function testCustomers()
    {
        $this->assertInstanceOf(Customers::class, $this->apiClient->customers());
    }

    public function testNotes()
    {
        $this->assertInstanceOf(
            EntityNotes::class,
            $this->apiClient->notes(EntityTypesInterface::LEADS)
        );
        $this->assertInstanceOf(
            EntityNotes::class,
            $this->apiClient->notes(EntityTypesInterface::CONTACTS)
        );
        $this->assertInstanceOf(
            EntityNotes::class,
            $this->apiClient->notes(EntityTypesInterface::COMPANIES)
        );
        $this->assertInstanceOf(
            EntityNotes::class,
            $this->apiClient->notes(EntityTypesInterface::CUSTOMERS)
        );

        $this->expectException(InvalidArgumentException::class);
        $this->assertInstanceOf(
            EntityNotes::class,
            $this->apiClient->notes(EntityTypesInterface::TASKS)
        );
    }

    public function testSetAccessToken()
    {
        $accessToken = new AccessToken([
            'access_token' => 'xxx',
            'refresh_token' => 'xxx',
            'expires' => 1893456000,
            'baseDomain' => 'example.amocrm.ru',
        ]);
        $this->assertInstanceOf(AmoCRMApiClient::class, $this->apiClient->setAccessToken($accessToken));
        $accessToken = $this->_getInnerPropertyValueByReflection('accessToken');
        $this->assertInstanceOf(AccessToken::class, $accessToken);
    }

    public function testTasks()
    {
        $this->assertInstanceOf(Tasks::class, $this->apiClient->tasks());
    }

    public function testUsers()
    {
        $this->assertInstanceOf(Users::class, $this->apiClient->users());
    }

    public function testCustomFieldGroups()
    {
        $this->assertInstanceOf(
            CustomFieldGroups::class,
            $this->apiClient->customFieldGroups(EntityTypesInterface::LEADS)
        );
        $this->assertInstanceOf(
            CustomFieldGroups::class,
            $this->apiClient->customFieldGroups(EntityTypesInterface::CONTACTS)
        );
        $this->assertInstanceOf(
            CustomFieldGroups::class,
            $this->apiClient->customFieldGroups(EntityTypesInterface::COMPANIES)
        );
        $this->assertInstanceOf(
            CustomFieldGroups::class,
            $this->apiClient->customFieldGroups(EntityTypesInterface::CUSTOMERS)
        );

        $this->assertInstanceOf(CustomFieldGroups::class, $this->apiClient->customFieldGroups());

        $this->expectException(InvalidArgumentException::class);
        $this->assertInstanceOf(
            CustomFieldGroups::class,
            $this->apiClient->customFieldGroups(EntityTypesInterface::TASKS)
        );
    }

    public function testTransactions()
    {
        $this->assertInstanceOf(Transactions::class, $this->apiClient->transactions());
    }

    public function testGetAccountBaseDomain()
    {
        $this->expectException(TypeError::class);
        $this->apiClient->getAccountBaseDomain();

        $this->apiClient->setAccountBaseDomain('example.amocrm.com');
        $this->assertIsString($this->apiClient->getAccountBaseDomain());
        $this->assertSame('example.amocrm.com', $this->apiClient->getAccountBaseDomain());
    }

    public function testLeads()
    {
        $this->assertInstanceOf(Leads::class, $this->apiClient->leads());
    }

    /**
     * Return value of a private property using ReflectionClass
     *
     * @param string $property
     *
     * @return mixed
     * @throws ReflectionException
     */
    private function _getInnerPropertyValueByReflection(string $property)
    {
        $reflector = new ReflectionClass($this->apiClient);
        $reflectorProperty = $reflector->getProperty($property);
        $reflectorProperty->setAccessible(true);

        return $reflectorProperty->getValue($this->apiClient);
    }
}
