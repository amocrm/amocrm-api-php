<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Customers\Segments\SegmentsCollection;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Customers\Segments\SegmentModel;

/**
 * Class Segments
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method null|SegmentModel getOne($id, array $with = [])
 * @method null|SegmentsCollection get(BaseEntityFilter $filter = null, array $with = [])
 * @method SegmentModel updateOne(BaseApiModel $apiModel)
 * @method SegmentModel syncOne(BaseApiModel $apiModel, $with = [])
 */
class Segments extends BaseEntity implements HasPageMethodsInterface
{
    use PageMethodsTrait;

    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::CUSTOMERS . '/' . EntityTypesInterface::CUSTOMERS_SEGMENTS;

    /**
     * @var string
     */
    protected $collectionClass = SegmentsCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = SegmentModel::class;

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CUSTOMERS_SEGMENTS])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CUSTOMERS_SEGMENTS];
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
     * Добавление коллекции сущностей
     * @param BaseApiCollection|SegmentsCollection $collection
     *
     * @return BaseApiCollection|SegmentsCollection
     * @throws AmoCRMApiException
     */
    public function add(BaseApiCollection $collection): BaseApiCollection
    {
        throw new NotAvailableForActionException('This entity supports only addOne method');
    }

    /**
     * Добавление сщуности
     * @param BaseApiModel $model
     *
     * @return BaseApiModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function addOne(BaseApiModel $model): BaseApiModel
    {
        $response = $this->request->post($this->getMethod(), $model->toApi());
        $this->processModelAction($model, $response);

        return $model;
    }

    /**
     * @param BaseApiCollection|SegmentsCollection $collection
     *
     * @return BaseApiCollection|SegmentsCollection
     * @throws NotAvailableForActionException
     */
    public function update(BaseApiCollection $collection): BaseApiCollection
    {
        throw new NotAvailableForActionException('This entity supports only updateOne method');
    }

    /**
     * @param BaseApiModel|SegmentModel $apiModel
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

        if (isset($entity['color'])) {
            $apiModel->setColor($entity['color']);
        }

        $customFieldsValues = new CustomFieldsValuesCollection();
        if (isset($entity['custom_fields_values'])) {
            $customFieldsValues = CustomFieldsValuesCollection::fromArray($entity['custom_fields_values']);
        }
        $apiModel->setCustomFieldsValues($customFieldsValues);
    }
}
