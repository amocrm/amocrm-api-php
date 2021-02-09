<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\CustomFieldGroupsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\CustomFieldGroupModel;

/**
 * Class CustomFieldGroups
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method null|CustomFieldGroupModel getOne($id, array $with = [])
 * @method null|CustomFieldGroupsCollection get(BaseEntityFilter $filter = null, array $with = [])
 * @method CustomFieldGroupModel addOne(BaseApiModel $model)
 * @method CustomFieldGroupsCollection add(BaseApiCollection $collection)
 * @method CustomFieldGroupModel updateOne(BaseApiModel $apiModel)
 */
class CustomFieldGroups extends BaseEntityTypeEntity implements HasDeleteMethodInterface
{
    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/%s/custom_fields/groups';

    /**
     * @var string
     */
    protected $collectionClass = CustomFieldGroupsCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = CustomFieldGroupModel::class;

    /**
     * @param string $entityType
     *
     * @return string
     * @throws InvalidArgumentException
     */
    protected function validateEntityType(string $entityType): string
    {
        $availableEntities = [
            EntityTypesInterface::CONTACTS,
            EntityTypesInterface::LEADS,
            EntityTypesInterface::CUSTOMERS,
            EntityTypesInterface::COMPANIES,
        ];

        if (!in_array($entityType, $availableEntities, true)) {
            throw new InvalidArgumentException('Entity is not supported by this method');
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

        if (
            isset($response[AmoCRMApiRequest::EMBEDDED])
            && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CUSTOM_FIELD_GROUPS])
        ) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CUSTOM_FIELD_GROUPS];
        }

        return $entities;
    }

    /**
     * @param BaseApiCollection $collection
     *
     * @return BaseApiCollection
     * @throws NotAvailableForActionException
     */
    public function update(BaseApiCollection $collection): BaseApiCollection
    {
        throw new NotAvailableForActionException('This entity supports only updateOne method');
    }

    /**
     * @param BaseApiModel $model
     *
     * @param array $response
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
     * @param BaseApiModel|CustomFieldGroupModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        if (isset($entity['id'])) {
            $apiModel->setId($entity['id']);
        }

        if (isset($entity['name'])) {
            $apiModel->setName($entity['name']);
        }
    }


    /**
     * @param BaseApiModel|CustomFieldGroupModel $model
     *
     * @return bool
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function deleteOne(BaseApiModel $model): bool
    {
        $method = $this->getMethod() . '/' . $model->getId();
        $response = $this->request->delete($method);

        return $response['result'];
    }

    /**
     * @param BaseApiCollection $collection
     *
     * @return bool
     * @throws NotAvailableForActionException
     */
    public function delete(BaseApiCollection $collection): bool
    {
        throw new NotAvailableForActionException('This entity supports only deleteOne method');
    }

    /**
     * @param BaseApiModel|CustomFieldGroupModel $apiModel
     * @param array $with
     *
     * @return BaseApiModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function syncOne(BaseApiModel $apiModel, $with = []): BaseApiModel
    {
        $this->setEntityType($apiModel->getEntityType());

        return parent::syncOne($apiModel, $with);
    }
}
