<?php

namespace AmoCRM\EntitiesServices\Leads;

use AmoCRM\EntitiesServices\HasDeleteMethodInterface;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
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

/**
 * Class Statuses
 *
 * @package AmoCRM\EntitiesServices\Leads
 *
 * @method null|StatusModel getOne($id, array $with = [])
 * @method null|StatusesCollection get(BaseEntityFilter $filter = null, array $with = [])
 * @method StatusModel addOne(BaseApiModel $model)
 * @method StatusesCollection add(BaseApiCollection $collection)
 * @method StatusModel updateOne(BaseApiModel $apiModel)
 */
class Statuses extends BaseEntityIdEntity implements HasDeleteMethodInterface
{
    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::LEADS . '/' . EntityTypesInterface::LEADS_PIPELINES . '/%s/' . EntityTypesInterface::LEADS_STATUSES;

    /**
     * @var string
     */
    protected $collectionClass = StatusesCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = StatusModel::class;

    /**
     * @param array $response
     *
     * @return array
     */
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
     *
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
     * @param BaseApiModel|StatusModel $apiModel
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
     * @param BaseApiModel|StatusModel $model
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
     * @param BaseApiModel|StatusModel $apiModel
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
