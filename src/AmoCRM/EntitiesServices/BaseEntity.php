<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\HasIdInterface;
use ReflectionClass;
use ReflectionException;

use function is_null;

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
    public const ITEM_CLASS = '';

    /**
     * @var AmoCRMApiRequest
     */
    protected $request;

    /**
     * BaseEntity constructor.
     *
     * @param AmoCRMApiRequest $request
     */
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
     *
     * @return array
     */
    abstract protected function getEntitiesFromResponse(array $response): array;

    /**
     * Получение коллекции сущностей
     * @param null|BaseEntityFilter $filter
     * @param array $with
     *
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
        $with = array_intersect($with, (static::ITEM_CLASS)::getAvailableWith());
        if (!empty($with)) {
            $queryParams['with'] = implode(',', $with);
        }

        $response = $this->request->get($this->getMethod(), $queryParams);

        return $this->createCollection($response);
    }

    /**
     * @param array $response
     *
     * @return BaseApiCollection|null
     */
    protected function createCollection(array $response): ?BaseApiCollection
    {
        /** @var BaseApiCollection $collection */
        $collection = new $this->collectionClass();
        $entities = $this->getEntitiesFromResponse($response);

        $collection = !empty($entities) ? $collection->fromArray($entities) : null;

        if (method_exists($collection, 'setNextPageLink') && isset($response['_links']['next']['href'])) {
            $collection->setNextPageLink($response['_links']['next']['href']);
        }

        if (method_exists($collection, 'setPrevPageLink') && isset($response['_links']['prev']['href'])) {
            $collection->setPrevPageLink($response['_links']['prev']['href']);
        }

        return $collection;
    }

    /**
     * Получение одной конкретной сущности
     * @param int|string $id
     * @param array $with
     *
     * @return BaseApiModel|null
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function getOne($id, array $with = []): ?BaseApiModel
    {
        $queryParams = [];
        $with = array_intersect($with, (static::ITEM_CLASS)::getAvailableWith());
        if (!empty($with)) {
            $queryParams['with'] = implode(',', $with);
        }
        $response = $this->request->get($this->getMethod() . '/' . $id, $queryParams);

        $class = static::ITEM_CLASS;
        /** @var BaseApiModel $entity */
        $entity = new $class();

        $entity = !empty($response) ? $entity->fromArray($response) : null;

        return $entity;
    }

    /**
     * @param BaseApiCollection $collection
     * @param array $response
     *
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
     *
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
     *
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
     *
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
     * Добавление сщуности
     * @param BaseApiModel $model
     *
     * @return BaseApiModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function addOne(BaseApiModel $model): BaseApiModel
    {
        /** @var BaseApiCollection $collection */
        $collection = new $this->collectionClass();
        $collection->add($model);
        $collection = $this->add($collection);

        return $collection->first();
    }

    /**
     * Обновление коллекции сущностей
     * @param BaseApiCollection $collection
     *
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
     *
     * @return BaseApiModel
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

        $response = $this->request->patch($this->getMethod() . '/' . $apiModel->getId(), $apiModel->toApi(0));
        $apiModel = $this->processUpdateOne($apiModel, $response);

        return $apiModel;
    }

    /**
     * @param BaseApiModel $apiModel
     * @param array $with
     *
     * @return BaseApiModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function syncOne(BaseApiModel $apiModel, $with = []): BaseApiModel
    {
        return $this->mergeModels($this->getOne($apiModel->getId(), $with), $apiModel);
    }

//    /**
//     * @param BaseApiCollection $collection
//     * @param array $with
//     *
//     * @return BaseApiCollection
//     * @throws AmoCRMApiException
//     * @throws AmoCRMoAuthApiException
//     * @throws InvalidArgumentException
//     */
//    public function sync(BaseApiCollection $collection, $with = []): BaseApiCollection
//    {
//        $ids = $collection->pluck('id');
//        //TODO implement sync() and pluck();
//
//        $freshModel = $this->mergeModels($this->get($collection, $with), $apiModel);
//
//        return $freshModel;
//    }

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
        if (get_class($objectA) !== get_class($objectB)) {
            throw new InvalidArgumentException('Can not merge 2 different objects');
        }
    }

    /**
     * @param BaseApiModel $objectA
     * @param BaseApiModel $objectB
     *
     * @return BaseApiModel
     * @throws InvalidArgumentException
     */
    protected function mergeModels(
        BaseApiModel $objectA,
        BaseApiModel $objectB
    ): BaseApiModel {
        $this->checkModelsClasses($objectA, $objectB);

        //Так как обе модели должны быть одного класса, нам без разницы у какой получить финальный класс
        $finalClass = get_class($objectA);
        $newObject = new $finalClass();

        /**
         * Свойства класса получим через отражение, а значения через магические метод
         * @see \AmoCRM\Models\BaseApiModel::__get
         * @see \AmoCRM\Models\BaseApiModel::__set
         */
        try {
            $reflection = new ReflectionClass($objectA);
        } catch (ReflectionException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        $reflectionProperties = $reflection->getProperties();
        foreach ($reflectionProperties as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            $value = $objectB->$propertyName;
            if (is_null($value)) {
                $value = $objectA->$propertyName;
            }

            if (!is_null($value)) {
                $newObject->$propertyName = $value;
            }
        }

        return $newObject;
    }

    /**
     * @return array
     */
    public function getLastRequestInfo(): array
    {
        return $this->request->getLastRequestInfo();
    }
}
