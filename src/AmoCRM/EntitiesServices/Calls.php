<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Collections\CallsCollection;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\CallModel;
use AmoCRM\Models\Factories\EntityFactory;

/**
 * Class Calls
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method CallModel addOne(BaseApiModel $model)
 * @method CallsCollection add(BaseApiCollection $collection)
 */
class Calls extends BaseEntity
{
    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::CALLS;

    /**
     * @var string
     */
    protected $collectionClass = CallsCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = CallModel::class;

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CALLS])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CALLS];
        }

        return $entities;
    }

    /**
     * @param BaseApiModel $model
     * @param array $response
     *
     * @return BaseApiModel
     * @throws NotAvailableForActionException
     */
    protected function processUpdateOne(BaseApiModel $model, array $response): BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiCollection $collection
     * @param array $response
     *
     * @return BaseApiCollection
     * @throws NotAvailableForActionException
     */
    protected function processUpdate(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiCollection $collection
     * @param array $response
     *
     * @return BaseApiCollection
     * @throws InvalidArgumentException
     */
    protected function processAdd(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        return $this->processAction($collection, $response);
    }

    /**
     * @param BaseApiCollection $collection
     * @param array $response
     *
     * @return BaseApiCollection
     * @throws InvalidArgumentException
     */
    protected function processAction(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        $entities = $this->getEntitiesFromResponse($response);
        foreach ($entities as $entity) {
            if (array_key_exists('request_id', $entity)) {
                $initialEntity = $collection->getBy('requestId', $entity['request_id']);
                if (!empty($initialEntity)) {
                    $this->processModelAction($initialEntity, $entity);
                }
            }
        }

        return $collection;
    }

    /**
     * @param BaseApiModel|CallModel $apiModel
     * @param array $entity
     *
     * @throws InvalidArgumentException
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        /** @var CallModel $apiModel */
        if (isset($entity['id'])) {
            $apiModel->setId($entity['id']);
        }

        $entityModel = null;
        if (isset($entity[AmoCRMApiRequest::EMBEDDED]['entity'])) {
            $entityModel = EntityFactory::createForType($entity['entity_type'], $entity[AmoCRMApiRequest::EMBEDDED]['entity']);
        }

        $apiModel->setEntity($entityModel);
    }

    /**
     * @param int|string $id
     * @param array $with
     *
     * @return BaseApiModel|null
     * @throws NotAvailableForActionException
     */
    public function getOne($id, array $with = []): ?BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseEntityFilter|null $filter
     * @param array $with
     *
     * @return BaseApiCollection|null
     * @throws NotAvailableForActionException
     */
    public function get(BaseEntityFilter $filter = null, array $with = []): ?BaseApiCollection
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
}
