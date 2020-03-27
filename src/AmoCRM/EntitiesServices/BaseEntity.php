<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Models\BaseApiModel;

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
    private $request;

    public function __construct(AmoCRMApiRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Метод для получения сущностей из ответа сервера
     * @param array $response
     * @return array
     */
    abstract protected function getEntitiesFromResponse(array $response): array;

    /**
     * Получение коллекции сущностей
     * @param BaseEntityFilter $filter
     * @param array $with
     * @return BaseApiCollection|null
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function get(BaseEntityFilter $filter, array $with = []): ?BaseApiCollection
    {
        $queryParams = $filter->buildFilter();
        $with = array_intersect($with, $this->itemClass::getAvailableWith());
        if (!empty($with)) {
            $queryParams['with'] = implode(',', $with);
        }

        $response = $this->request->get($this->method, $queryParams);

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
        $response = $this->request->get($this->method . '/' . $id, $queryParams);

        /** @var BaseApiModel $entity */
        $entity = new $this->itemClass();

        $entity = !empty($response) ? $entity->fromArray($response) : null;

        return $entity;
    }

    /**
     * Добавление коллекции сущностей
     * @param BaseApiCollection $collection
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function add(BaseApiCollection $collection)
    {
        $response = $this->request->post($this->method, $collection->toArray());
        //todo parse response and fill collection object with id by request_id
    }

    /**
     * Обновление коллекции сущностей
     * @param BaseApiCollection $collection
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function update(BaseApiCollection $collection)
    {
        $response = $this->request->patch($this->method, $collection->toArray());
        //todo parse response and fill collection with request_id
    }

    /**
     * Обновление одной конкретной сущности
     * @param BaseApiModel $apiModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function updateOne(BaseApiModel $apiModel)
    {
        $id = method_exists($apiModel, 'getId') ? $apiModel->getId() : null;

        if (is_null($id)) {
            throw new AmoCRMApiException('Empty id in model ' . json_encode($apiModel->toArray()));
        }

        $response = $this->request->patch($this->method . $apiModel->getId(), $apiModel->toArray());
        //todo parse response and fill object with id by request_id
    }
}
