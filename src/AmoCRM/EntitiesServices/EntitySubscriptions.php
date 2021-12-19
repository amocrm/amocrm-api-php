<?php

declare(strict_types=1);

namespace AmoCRM\EntitiesServices;

use AmoCRM\Models\SubscriptionModel;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\SubscriptionsCollection;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\EntitiesServices\Interfaces\HasParentEntity;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\EntitiesServices\Traits\WithParentEntityMethodsTrait;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\BaseApiModel;

/**
 * @method null|SubscriptionsCollection getByParentId(int $parentId, BaseEntityFilter $filter = null, array $with = [])
 */
class EntitySubscriptions extends BaseEntityTypeEntity implements HasPageMethodsInterface, HasParentEntity
{
    use PageMethodsTrait;
    use WithParentEntityMethodsTrait;

    public const ITEM_CLASS = SubscriptionModel::class;

    /** @var string */
    protected $methodWithParent = 'api/v' . AmoCRMApiClient::API_VERSION . '/%s/%d/' . EntityTypesInterface::SUBSCRIPTIONS;
    /** @var class-string */
    protected $collectionClass = SubscriptionsCollection::class;

    /**
     * @param string $entityType
     *
     * @return string
     * @throws InvalidArgumentException
     */
    protected function validateEntityType(string $entityType): string
    {
        $availableTypes = [
            EntityTypesInterface::LEADS,
            EntityTypesInterface::CUSTOMERS,
        ];

        if (!in_array($entityType, $availableTypes, true)) {
            throw new InvalidArgumentException('This method doesn\'t support given entity type');
        }

        return $entityType;
    }

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::SUBSCRIPTIONS])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::SUBSCRIPTIONS];
        }

        return $entities;
    }

    /**
     * @param BaseApiModel $model
     *
     * @return BaseApiModel
     * @throws NotAvailableForActionException
     */
    public function addOne(BaseApiModel $model): BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiCollection $collection
     *
     * @return BaseApiCollection
     * @throws NotAvailableForActionException
     */
    public function add(BaseApiCollection $collection): BaseApiCollection
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiCollection $collection
     *
     * @return BaseApiCollection
     * @throws NotAvailableForActionException
     */
    public function update(BaseApiCollection $collection): BaseApiCollection
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiModel $apiModel
     *
     * @return BaseApiModel
     * @throws NotAvailableForActionException
     */
    public function updateOne(BaseApiModel $apiModel): BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiModel $apiModel
     * @param array $with
     *
     * @return BaseApiModel
     * @throws NotAvailableForActionException
     */
    public function syncOne(BaseApiModel $apiModel, $with = []): BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param array $id
     * @param array $with
     *
     * @return BaseApiModel|null
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function getOne($id, array $with = []): ?BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param null|BaseEntityFilter $filter
     * @param array $with
     *
     * @return BaseApiCollection|null
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function get(BaseEntityFilter $filter = null, array $with = []): ?BaseApiCollection
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }
}
