<?php

namespace AmoCRM\EntitiesServices\Leads;

use AmoCRM\Collections\Leads\Pipelines\Statuses\StatusesCollection;
use AmoCRM\EntitiesServices\HasDeleteMethodInterface;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Leads\Pipelines\PipelinesCollection;
use AmoCRM\EntitiesServices\BaseEntity;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Leads\Pipelines\PipelineModel;

/**
 * Class Pipelines
 *
 * @package AmoCRM\EntitiesServices\Leads
 *
 * @method null|PipelineModel getOne($id, array $with = [])
 * @method null|PipelinesCollection get(BaseEntityFilter $filter = null, array $with = [])
 * @method PipelineModel addOne(BaseApiModel $model)
 * @method PipelinesCollection add(BaseApiCollection $collection)
 * @method PipelineModel updateOne(BaseApiModel $apiModel)
 * @method PipelineModel syncOne(BaseApiModel $apiModel, $with = [])
 */
class Pipelines extends BaseEntity implements HasDeleteMethodInterface
{
    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::LEADS . '/' . EntityTypesInterface::LEADS_PIPELINES;

    /**
     * @var string
     */
    protected $collectionClass = PipelinesCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = PipelineModel::class;

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::LEADS_PIPELINES])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::LEADS_PIPELINES];
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
     * @param BaseApiModel|PipelineModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        if (isset($entity['id'])) {
            $apiModel->setId($entity['id']);
        }

        if (array_key_exists('name', $entity)) {
            $apiModel->setName($entity['name']);
        }

        if (array_key_exists('sort', $entity)) {
            $apiModel->setSort($entity['sort']);
        }

        if (array_key_exists('is_main', $entity)) {
            $apiModel->setIsMain($entity['is_main']);
        }

        if (array_key_exists('is_unsorted_on', $entity)) {
            $apiModel->setIsUnsortedOn($entity['is_unsorted_on']);
        }

        if (array_key_exists('is_archive', $entity)) {
            $apiModel->setIsArchive($entity['is_archive']);
        }

        if (array_key_exists('account_id', $entity)) {
            $apiModel->setAccountId($entity['account_id']);
        }

        if (!empty($entity[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::LEADS_STATUSES])) {
            $apiModel->setStatuses(
                StatusesCollection::fromArray(
                    $entity[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::LEADS_STATUSES]
                )
            );
        }
    }

    /**
     * @param BaseApiModel|PipelineModel $model
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
}
