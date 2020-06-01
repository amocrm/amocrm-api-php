<?php

namespace AmoCRM\EntitiesServices\Traits;

use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\EntitiesServices\Interfaces\HasParentEntity;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\HasIdInterface;

use function is_null;

trait WithParentEntityMethodsTrait
{
    /**
     * @var AmoCRMApiRequest
     */
    protected $request;

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
            throw new AmoCRMApiException('Empty id in model ' . json_encode($apiModel->toArray()));
        }

        $parentId = method_exists($apiModel, 'getEntityId') ? $apiModel->getEntityId() : null;
        if (is_null($parentId)) {
            throw new AmoCRMApiException('Parent id in model ' . json_encode($apiModel->toArray()));
        }

        $response = $this->request->patch($this->getMethodWithParent($parentId, $id), $apiModel->toApi());
        $apiModel = $this->processUpdateOne($apiModel, $response);

        return $apiModel;
    }

    /**
     * Получение одной конкретной сущности
     *
     * @param array $id
     * @param array $with
     *
     * @return BaseApiModel|null
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function getOne($id, array $with = []): ?BaseApiModel
    {
        $queryParams = [];
        $class = static::ITEM_CLASS;
        $with = array_intersect($with, $class::getAvailableWith());
        if (!empty($with)) {
            $queryParams['with'] = implode(',', $with);
        }

        $parentId = $id[HasParentEntity::PARENT_ID_KEY] ?? null;
        $id = $id[HasParentEntity::ID_KEY] ?? null;

        $response = $this->request->get($this->getMethodWithParent($parentId, $id), $queryParams);

        $class = static::ITEM_CLASS;
        /** @var BaseApiModel $entity */
        $entity = new $class();

        $entity = !empty($response) ? $entity->fromArray($response) : null;

        return $entity;
    }

    /**
     * @param string|int $parentId
     * @param string|int $id
     *
     * @return string
     */
    protected function getMethodWithParent($parentId, $id = null): string
    {
        $method = sprintf($this->methodWithParent, $this->getEntityType(), $parentId);

        if (!is_null($id)) {
            $method .= '/' . $id;
        }

        return $method;
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
        $id = method_exists($apiModel, 'getId') ? $apiModel->getId() : null;
        if (is_null($id)) {
            throw new AmoCRMApiException('Empty id in model ' . json_encode($apiModel->toArray()));
        }

        $parentId = method_exists($apiModel, 'getEntityId') ? $apiModel->getEntityId() : null;
        if (is_null($parentId)) {
            throw new AmoCRMApiException('Parent id in model ' . json_encode($apiModel->toArray()));
        }

        return $this->mergeModels($this->getOne([HasParentEntity::ID_KEY => $id, HasParentEntity::PARENT_ID_KEY => $parentId], $with), $apiModel);
    }

    /**
     * Получение коллекции сущностей по ID родительской сущности
     *
     * @param int $parentId
     * @param null|BaseEntityFilter $filter
     * @param array $with
     *
     * @return BaseApiCollection|null
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function getByParentId(int $parentId, BaseEntityFilter $filter = null, array $with = []): ?BaseApiCollection
    {
        $queryParams = [];
        if ($filter instanceof BaseEntityFilter) {
            $queryParams = $filter->buildFilter();
        }
        $with = array_intersect($with, (static::ITEM_CLASS)::getAvailableWith());
        if (!empty($with)) {
            $queryParams['with'] = implode(',', $with);
        }

        $response = $this->request->get($this->getMethodWithParent($parentId), $queryParams);

        return $this->createCollection($response);
    }
}
