<?php

namespace AmoCRM\AmoCRM\EntitiesServices\Leads;

use AmoCRM\AmoCRM\EntitiesServices\HasDeleteMethodInterface;
use AmoCRM\AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Leads\Pipelines\Statuses\StatusesCollection;
use AmoCRM\EntitiesServices\BaseEntityIdEntity;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Leads\Pipelines\Statuses\StatusModel;
use Exception;

class Statuses extends BaseEntityIdEntity implements HasDeleteMethodInterface
{
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::LEADS . '/' . EntityTypesInterface::LEADS_PIPELINES . '/%s/' . EntityTypesInterface::LEADS_STATUSES;

    protected $collectionClass = StatusesCollection::class;

    protected $itemClass = StatusModel::class;

    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::LEADS_STATUSES])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::LEADS_STATUSES];
        }

        return $entities;
    }

    /**
     * @param BaseApiCollection $collection
     * @return BaseApiCollection
     * @throws NotAvailableForActionException
     */
    public function update(BaseApiCollection $collection): BaseApiCollection
    {
        throw new NotAvailableForActionException('Method not available for this entity');
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
    protected function processAdd(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        return $this->processAction($collection, $response);
    }

    protected function processAction(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        $entities = $this->getEntitiesFromResponse($response);
        foreach ($entities as $entity) {
            //todo remove this after request_id fix
            if (!empty($entity['name'])) {
                $initialEntity = $collection->getBy('name', $entity['name']);
                if (!empty($initialEntity)) {
                    $this->processModelAction($initialEntity, $entity);
                }
            }
            //todo support request_id
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

        if (array_key_exists('sort', $entity)) {
            $apiModel->setSort($entity['sort']);
        }

        if (array_key_exists('color', $entity)) {
            $apiModel->setColor($entity['color']);
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
     * @throws NotAvailableForActionException
     */
    public function delete(BaseApiCollection $collection): bool
    {
        throw new NotAvailableForActionException('This entity supports only deleteOne method');
    }

    /**
     * @param BaseApiModel $apiModel
     * @param array $with
     *
     * @return BaseApiModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws Exception
     */
    public function syncOne(BaseApiModel $apiModel, $with = []): BaseApiModel
    {
        $this->setEntityId($apiModel->getPipelineId());

        return $this->mergeModels($this->getOne($apiModel->getId(), $with), $apiModel);
    }
}
