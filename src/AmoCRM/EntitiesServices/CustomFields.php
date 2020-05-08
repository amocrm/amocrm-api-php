<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\AmoCRM\EntitiesServices\HasDeleteMethodInterface;
use AmoCRM\AmoCRM\Helpers\EntityTypesInterface;
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
use Exception;

class CustomFields extends BaseEntityTypeEntity implements HasDeleteMethodInterface, HasPageMethodsInterface
{
    use PageMethodsTrait;

    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/%s/custom_fields';

    protected $collectionClass = CustomFieldsCollection::class;

    protected $itemClass = CustomFieldModel::class;

    protected function validateEntityType(string $entityType): void
    {
        //todo segments
        //todo catalog elements with catalog_id
        $availableEntities = [
            EntityTypesInterface::CONTACTS,
            EntityTypesInterface::LEADS,
            EntityTypesInterface::CUSTOMERS,
            EntityTypesInterface::COMPANIES,
        ];

        if (!in_array($entityType, $availableEntities)) {
            throw new Exception('Entity is not supported by this method');
        }
    }

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
     * @return BaseApiCollection
     */
    protected function processUpdate(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        return $this->processAction($collection, $response);
    }

    /**
     * @param BaseApiCollection $collection
     * @param array $response
     * @return BaseApiCollection
     */
    protected function processAdd(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        return $this->processAction($collection, $response);
    }

    protected function processAction(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        $entities = $this->getEntitiesFromResponse($response);
        foreach ($entities as $entity) {
            if (!empty($entity['name'])) {
                $initialEntity = $collection->getBy('name', $entity['name']);
                if (!empty($initialEntity)) {
                    $this->processModelAction($initialEntity, $entity);
                }
            }
            //todo add request_id
//            if (!empty($entity['request_id'])) {
//                $initialEntity = $collection->getBy('requestId', $entity['request_id']);
//                if (!empty($initialEntity)) {
//                    $this->processModelAction($initialEntity, $entity);
//                }
//            }
        }

        return $collection;
    }

    /**
     * @param BaseApiModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        //todo
        if (isset($entity['id'])) {
            $apiModel->setId($entity['id']);
        }

        if (isset($entity['name'])) {
            $apiModel->setName($entity['name']);
        }
    }

    /**
     * @param BaseApiModel $model
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
     * @return bool
     * @throws Exception
     */
    public function delete(BaseApiCollection $collection): bool
    {
        throw new Exception('This entity supports only deleteOne method');
    }

    /**
     * @param BaseApiCollection $collection
     * @return BaseApiCollection
     * @throws Exception
     */
    public function update(BaseApiCollection $collection): BaseApiCollection
    {
        throw new Exception('This entity supports only updateOne method');
    }

    /**
     * @param BaseApiModel $apiModel
     * @param array $with
     * @return BaseApiModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws Exception
     */
    public function syncOne(BaseApiModel $apiModel, $with = []): BaseApiModel
    {
        $this->setEntityType($apiModel->getCatalogId());

        $freshModel = $this->mergeModels($this->getOne($apiModel->getId(), $with), $apiModel);

        return $freshModel;
    }
}
