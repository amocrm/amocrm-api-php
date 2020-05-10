<?php

namespace AmoCRM\EntitiesServices\Customers;

use AmoCRM\EntitiesServices\HasLinkMethodInterface;
use AmoCRM\EntitiesServices\Traits\LinkMethodsTrait;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Customers\CustomersCollection;
use AmoCRM\EntitiesServices\BaseEntity;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Customers\CustomerModel;

/**
 * Class Customers
 *
 * @package AmoCRM\EntitiesServices\Customers
 *
 * @method CustomerModel getOne($id, array $with = []) : ?CustomerModel
 * @method CustomersCollection get(BaseEntityFilter $filter = null, array $with = []) : ?CustomersCollection
 * @method CustomerModel addOne(BaseApiModel $model) : CustomerModel
 * @method CustomersCollection add(BaseApiCollection $collection) : CustomersCollection
 * @method CustomerModel updateOne(BaseApiModel $apiModel) : CustomerModel
 * @method CustomersCollection update(BaseApiCollection $collection) : CustomersCollection
 * @method CustomerModel syncOne(BaseApiModel $apiModel, $with = []) : CustomerModel
 */
class Customers extends BaseEntity implements HasLinkMethodInterface, HasPageMethodsInterface
{
    use PageMethodsTrait;
    use LinkMethodsTrait;

    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::CUSTOMERS;

    /**
     * @var string
     */
    protected $collectionClass = CustomersCollection::class;

    /**
     * @var string
     */
    protected $itemClass = CustomerModel::class;

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CUSTOMERS])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CUSTOMERS];
        }

        return $entities;
    }

    /**
     * @param BaseApiModel $model
     * @param array $response
     *
     * @return BaseApiModel
     */
    protected function processUpdateOne(BaseApiModel $model, array $response): BaseApiModel
    {
        $this->processModelAction($model, $response);

        return $model;
    }

    /**
     * @param BaseApiCollection $collection
     * @param array $response
     *
     * @return BaseApiCollection
     */
    protected function processUpdate(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        return $this->processAction($collection, $response);
    }

    /**
     * @param BaseApiCollection $collection
     * @param array $response
     *
     * @return BaseApiCollection
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
     */
    protected function processAction(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        $entities = $this->getEntitiesFromResponse($response);
        foreach ($entities as $entity) {
            if (!empty($entity['request_id'])) {
                $initialEntity = $collection->getBy('requestId', $entity['request_id']);
                if (!empty($initialEntity)) {
                    $this->processModelAction($initialEntity, $entity);
                }
            }
        }

        return $collection;
    }

    /**
     * @param BaseApiModel|CustomerModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        if (isset($entity['id'])) {
            $apiModel->setId($entity['id']);
        }

        if (isset($entity['updated_at'])) {
            $apiModel->setUpdatedAt($entity['updated_at']);
        }
    }

    /**
     * @return array
     */
    protected function getAvailableLinkTypes(): array
    {
        return [
            EntityTypesInterface::CONTACTS,
            EntityTypesInterface::CATALOGS . EntityTypesInterface::CATALOG_ELEMENTS,
            //todo company
        ];
    }
}
