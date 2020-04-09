<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\CatalogElementModel;
use Exception;

class CatalogElements extends BaseEntityTypeEntity
{
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/catalogs/%s/elements';

    protected $collectionClass = CatalogElementsCollection::class;

    protected $itemClass = CatalogElementModel::class;

    protected function validateEntityType(string $entityType): void
    {
        if ((int)$entityType < EntityTypesInterface::MIN_CATALOG_ID) {
            throw new Exception('Doesn\'t looks like catalog exists');
        }
    }

    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (
            isset($response[AmoCRMApiRequest::EMBEDDED])
            && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CATALOG_ELEMENTS])
        ) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CATALOG_ELEMENTS];
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
     * @param BaseApiModel $apiModel
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
