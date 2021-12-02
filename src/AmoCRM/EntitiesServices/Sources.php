<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\SourcesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Filters\SourcesFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\SourceModel;

/**
 * Class EntitySources
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method null|SourcesCollection get(SourcesFilter $filter = null, array $with = [])
 * @method SourceModel addOne(SourceModel $model)
 * @method SourcesCollection add(SourcesCollection $collection)
 * @method SourceModel updateOne(SourceModel $model)
 * @method SourcesCollection update(SourcesCollection $collection)
 */
class Sources extends BaseEntity implements HasDeleteMethodInterface
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

    public function deleteOne(BaseApiModel $model): bool
    {
        if (!$model instanceof HasIdInterface) {
            throw new InvalidArgumentException('Entity should have getId method');
        }

        $id = $model->getId();

        if (is_null($id)) {
            throw new AmoCRMApiException('Empty id in model ' . json_encode($model->toApi(0)));
        }

        $result = $this->request->delete($this->getMethod() . '/' . $model->getId());

        return $result['result'] ?? false;
    }

    public function delete(BaseApiCollection $collection): bool
    {
        $result = $this->request->delete($this->getMethod(), $collection->toApi());
        return $result['result'] ?? false;
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
            && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::SOURCES])
        ) {
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
