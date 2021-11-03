<?php

namespace AmoCRM\EntitiesServices\Leads;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Leads\SourcesCollection;
use AmoCRM\EntitiesServices\BaseEntity;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Leads\SourceModel;

/**
 * Class EntitySources
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method null|SourcesCollection get(BaseEntityFilter $filter = null, array $with = [])
 * @method SourceModel addOne(BaseApiModel $model)
 * @method SourcesCollection add(BaseApiCollection $collection)
 */
class EntitySources extends BaseEntity
{

    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::SOURCES;

    /**
     * @var string
     */
    protected $collectionClass = SourcesCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = SourceModel::class;

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED])
            && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::SOURCES])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::SOURCES];
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
     * @param BaseApiModel|SourceModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        /** @var SourceModel|BaseApiModel $apiModel */
        if (isset($entity['id'])) {
            $apiModel->setId($entity['id']);
        }

        if (isset($entity['name'])) {
            $apiModel->setName($entity['name']);
        }

        if (isset($entity['pipeline_id'])) {
            $apiModel->setPipelineId((int)$entity['pipeline_id']);
        }

        if (isset($entity['external_id'])) {
            $apiModel->setExternalId($entity['external_id']);
        }
    }
}
