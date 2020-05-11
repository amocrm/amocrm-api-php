<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\CustomFieldsCollection;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\CustomFieldModel;

/**
 * Class CustomFields
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method CustomFieldModel getOne($id, array $with = []) : ?CustomFieldModel
 * @method CustomFieldsCollection get(BaseEntityFilter $filter = null, array $with = []) : ?CustomFieldsCollection
 * @method CustomFieldModel addOne(BaseApiModel $model) : CustomFieldModel
 * @method CustomFieldsCollection add(BaseApiCollection $collection) : CustomFieldsCollection
 * @method CustomFieldModel updateOne(BaseApiModel $apiModel) : CustomFieldModel
 */
class CustomFields extends BaseEntityTypeEntity implements HasDeleteMethodInterface, HasPageMethodsInterface
{
    use PageMethodsTrait;

    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/%s/custom_fields';

    /**
     * @var string
     */
    protected $collectionClass = CustomFieldsCollection::class;

    /**
     * @var string
     */
    protected $itemClass = CustomFieldModel::class;

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
            EntityTypesInterface::CUSTOMERS . '/' . EntityTypesInterface::CUSTOMERS_SEGMENTS,
        ];

        if ($entityType === EntityTypesInterface::CUSTOMERS_SEGMENTS) {
            $entityType = EntityTypesInterface::CUSTOMERS . '/' . EntityTypesInterface::CUSTOMERS_SEGMENTS;
        }
        if (!in_array($entityType, $availableEntities, true)) {
            preg_match("/" . EntityTypesInterface::CATALOGS . ":(\d+)/", $entityType, $matches);
            if (isset($matches[1]) && (int)$matches[1] > EntityTypesInterface::MIN_CATALOG_ID) {
                $entityType = EntityTypesInterface::CATALOGS . '/' . (int)$matches[1];
            } else {
                throw new InvalidArgumentException('Entity is not supported by this method');
            }
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
            && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CUSTOM_FIELDS])
        ) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CUSTOM_FIELDS];
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
     * @param BaseApiModel|CustomFieldModel $apiModel
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
     * @param BaseApiModel|CustomFieldModel $model
     *
     * @return bool
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function deleteOne(BaseApiModel $model): bool
    {
        $result = $this->request->delete($this->getMethod() . '/' . $model->getId());

        return $result['result'];
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
     * @param BaseApiModel|CustomFieldModel $apiModel
     * @param array $with
     *
     * @return BaseApiModel
     * @throws AmoCRMApiException
     */
    public function syncOne(BaseApiModel $apiModel, $with = []): BaseApiModel
    {
        //todo add support
        throw new NotAvailableForActionException('This entity does not support this method');
    }
}
