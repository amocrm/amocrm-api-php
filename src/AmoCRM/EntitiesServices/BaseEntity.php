<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Models\BaseApiModel;
use Exception;

abstract class BaseEntity
{
    /**
     * @var string
     * override in child
     */
    protected $method = '';

    /**
     * @var string
     * override in child
     */
    protected $collectionClass = '';

    /**
     * @var BaseApiModel
     * override in child
     */
    protected $itemClass = '';

    /**
     * @var AmoCRMApiRequest
     */
    protected $request;

    public function __construct(AmoCRMApiRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    protected function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Метод для получения сущностей из ответа сервера
     * @param array $response
     * @return array
     */
    abstract protected function getEntitiesFromResponse(array $response): array;

    /**
     * Получение коллекции сущностей
     * @param null|BaseEntityFilter $filter
     * @param array $with
     * @return BaseApiCollection|null
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function get(BaseEntityFilter $filter = null, array $with = []): ?BaseApiCollection
    {
        $queryParams = [];
        if ($filter instanceof BaseEntityFilter) {
            $queryParams = $filter->buildFilter();
        }
        $with = array_intersect($with, $this->itemClass::getAvailableWith());
        if (!empty($with)) {
            $queryParams['with'] = implode(',', $with);
        }

        $response = $this->request->get($this->getMethod(), $queryParams);

        /** @var BaseApiCollection $collection */
        $collection = new $this->collectionClass();
        $entities = $this->getEntitiesFromResponse($response);

        $collection = !empty($entities) ? $collection->fromArray($entities) : null;

        return $collection;
    }

    /**
     * Обновление одной конкретной сущности
     * @param int|string $id
     * @param array $with
     * @return BaseApiCollection|null
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function getOne($id, array $with = []): ?BaseApiModel
    {
        $queryParams = [];
        $with = array_intersect($with, $this->itemClass::getAvailableWith());
        if (!empty($with)) {
            $queryParams['with'] = implode(',', $with);
        }
        $response = $this->request->get($this->getMethod() . '/' . $id, $queryParams);

        /** @var BaseApiModel $entity */
        $entity = new $this->itemClass();

        $entity = !empty($response) ? $entity->fromArray($response) : null;

        return $entity;
    }

    /**
     * @param BaseApiCollection $collection
     * @param array $response
     * @return BaseApiCollection
     */
    protected function processUpdate(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        //override in child
        return $collection;
    }

    /**
     * @param BaseApiModel $model
     * @param array $response
     * @return BaseApiModel
     */
    protected function processUpdateOne(BaseApiModel $model, array $response): BaseApiModel
    {
        //override in child
        return $model;
    }

    /**
     * @param BaseApiCollection $collection
     * @param array $response
     * @return BaseApiCollection
     */
    protected function processAdd(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        //override in child
        return $collection;
    }

    /**
     * Добавление коллекции сущностей
     * @param BaseApiCollection $collection
     * @return BaseApiCollection
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function add(BaseApiCollection $collection): BaseApiCollection
    {
        $response = $this->request->post($this->getMethod(), $collection->toApi());
        $collection = $this->processAdd($collection, $response);

        return $collection;
    }

    /**
     * Обновление коллекции сущностей
     * @param BaseApiCollection $collection
     * @return BaseApiCollection
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function update(BaseApiCollection $collection): BaseApiCollection
    {
        $response = $this->request->patch($this->getMethod(), $collection->toApi());
        $collection = $this->processUpdate($collection, $response);

        return $collection;
    }

    /**
     * Обновление одной конкретной сущности
     * @param BaseApiModel $apiModel
     * @return BaseApiCollection|BaseApiModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function updateOne(BaseApiModel $apiModel): BaseApiModel
    {
        $id = method_exists($apiModel, 'getId') ? $apiModel->getId() : null;
        if (is_null($id)) {
            throw new AmoCRMApiException('Empty id in model ' . json_encode($apiModel->toApi(0)));
        }

        $response = $this->request->patch($this->getMethod() . '/' . $apiModel->getId(), $apiModel->toApi(0));
        $apiModel = $this->processUpdateOne($apiModel, $response);

        return $apiModel;
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
        $freshModel = $this->mergeModels($this->getOne($apiModel->getId(), $with), $apiModel);

        return $freshModel;
    }

    /**
     * @param BaseApiModel $objectA
     * @param BaseApiModel $objectB
     * @return BaseApiModel
     * @throws Exception
     */
    protected function mergeModels(
        BaseApiModel $objectA,
        BaseApiModel $objectB
    ): BaseApiModel {
        if (get_class($objectA) !== get_class($objectB)) {
            throw new Exception('Can not merge 2 different objects');
        }

        //Так как обе модели должны быть одного класса, нам без разницы у какой получить финальный класс
        $finalClass = get_class($objectA);
        $newObject = new $finalClass();

        /**
         * Свойства класса получим через отражение, а значения через магические метод
         * @see \AmoCRM\Models\BaseApiModel::__get
         * @see \AmoCRM\Models\BaseApiModel::__set
         */
        $reflection = new \ReflectionClass($objectA);
        $reflectionProperties = $reflection->getProperties();
        foreach ($reflectionProperties as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            $value = $objectB->$propertyName;
            if (is_null($value)) {
                $value = $objectA->$propertyName;
            }

            $newObject->$propertyName = $value;
        }

        return $newObject;
    }
}
