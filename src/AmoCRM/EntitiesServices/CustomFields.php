<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Exceptions\BadTypeException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\CustomFields\CustomFieldsCollection;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\CustomFields\CustomFieldModel;
use AmoCRM\Models\CustomFields\Factories\CustomFieldModelFactory;
use AmoCRM\Models\Interfaces\HasIdInterface;

/**
 * Class CustomFields
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method null|CustomFieldsCollection get(BaseEntityFilter $filter = null, array $with = [])
 */
class CustomFields extends BaseEntityTypeEntity implements HasDeleteMethodInterface, HasPageMethodsInterface
{
    use PageMethodsTrait;

    /**
     * @var string
     */
    protected $cleanEntityType;

    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/%s/custom_fields';

    /**
     * @var CustomFieldsCollection
     */
    protected $collectionClass = CustomFieldsCollection::class;

    /**
     * @var CustomFieldModel
     */
    public const ITEM_CLASS = CustomFieldModel::class;

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

        $this->cleanEntityType = $entityType;

        if ($entityType === EntityTypesInterface::CUSTOMERS_SEGMENTS) {
            $entityType = EntityTypesInterface::CUSTOMERS . '/' . EntityTypesInterface::CUSTOMERS_SEGMENTS;
        }

        if (!in_array($entityType, $availableEntities, true)) {
            preg_match("/" . EntityTypesInterface::CATALOGS . ":(\d+)/", $entityType, $matches);
            if (isset($matches[1]) && (int)$matches[1] > EntityTypesInterface::MIN_CATALOG_ID) {
                $this->cleanEntityType = EntityTypesInterface::CATALOGS;
                $entityType = EntityTypesInterface::CATALOGS . '/' . (int)$matches[1];
            } else {
                $this->cleanEntityType = '';
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
     * @throws BadTypeException
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
     * @throws BadTypeException
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
     * @throws BadTypeException
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
     * @throws BadTypeException
     */
    protected function processAction(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        $entities = $this->getEntitiesFromResponse($response);
        foreach ($entities as $entity) {
            if (array_key_exists('request_id', $entity)) {
                $initialEntity = $collection->getBy('requestId', $entity['request_id']);
                if (!empty($initialEntity)) {
                    $this->processModelAction($initialEntity, $entity);
                    $collection->replaceBy('requestId', $entity['request_id'], $initialEntity);
                }
            }
        }

        return $collection;
    }

    /**
     * @param BaseApiModel|CustomFieldModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel &$apiModel, array $entity): void
    {
        $apiModel = CustomFieldModelFactory::createModel($entity);
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
     * Добавление коллекции сущностей
     * @param BaseApiCollection|CustomFieldsCollection $collection
     *
     * @return BaseApiCollection|CustomFieldsCollection
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function add(BaseApiCollection $collection): BaseApiCollection
    {
        foreach ($collection as $model) {
            $model->setEntityType($this->cleanEntityType);
        }
        $response = $this->request->post($this->getMethod(), $collection->toApi());
        $collection = $this->processAdd($collection, $response);

        return $collection;
    }

    /**
     * Добавление сщуности
     * @param BaseApiModel|CustomFieldModel $model
     *
     * @return BaseApiModel|CustomFieldModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function addOne(BaseApiModel $model): BaseApiModel
    {
        $model->setEntityType($this->cleanEntityType);
        /** @var BaseApiCollection $collection */
        $collection = new $this->collectionClass();
        $collection->add($model);
        $collection = $this->add($collection);

        return $collection->first();
    }

    /**
     * Обновление одной конкретной сущности
     * @param BaseApiModel|CustomFieldModel $apiModel
     *
     * @return BaseApiModel|CustomFieldModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function updateOne(BaseApiModel $apiModel): BaseApiModel
    {
        if (!$apiModel instanceof HasIdInterface) {
            throw new InvalidArgumentException('Entity should have getId method');
        }

        $id = $apiModel->getId();

        if (is_null($id)) {
            throw new AmoCRMApiException('Empty id in model ' . json_encode($apiModel->toApi(0)));
        }

        $apiModel->setEntityType($this->cleanEntityType);
        $response = $this->request->patch($this->getMethod() . '/' . $apiModel->getId(), $apiModel->toApi(0));
        $apiModel = $this->processUpdateOne($apiModel, $response);

        return $apiModel;
    }

    /**
     * Получение одной конкретной сущности
     * @param int|string $id
     * @param array $with
     *
     * @return BaseApiModel|null|CustomFieldModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function getOne($id, array $with = []): ?BaseApiModel
    {
        $queryParams = [];
        $itemClass = static::ITEM_CLASS;
        $with = array_intersect($with, $itemClass::getAvailableWith());
        if (!empty($with)) {
            $queryParams['with'] = implode(',', $with);
        }
        $response = $this->request->get($this->getMethod() . '/' . $id, $queryParams);

        $collection = !empty($response) ? $this->collectionClass::fromArray([$response]) : null;

        return !empty($response) ? $collection->first() : null;
    }

    /**
     * @param BaseApiModel|CustomFieldModel $apiModel
     * @param array $with
     *
     * @return BaseApiModel|CustomFieldModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function syncOne(BaseApiModel $apiModel, $with = []): BaseApiModel
    {
        $apiModel->setEntityType($this->cleanEntityType);
        return $this->mergeModels($this->getOne($apiModel->getId(), $with), $apiModel);
    }

    /**
     * @param BaseApiModel $objectA
     * @param BaseApiModel $objectB
     *
     * @throws InvalidArgumentException
     */
    protected function checkModelsClasses(
        BaseApiModel $objectA,
        BaseApiModel $objectB
    ) {
        if (!($objectA instanceof $objectB || $objectB instanceof $objectA)) {
            throw new InvalidArgumentException('Can not merge 2 different objects');
        }
    }
}
