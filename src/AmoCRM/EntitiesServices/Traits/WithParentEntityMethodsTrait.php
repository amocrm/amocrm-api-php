<?php

namespace AmoCRM\EntitiesServices\Traits;

use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\EntitiesServices\Interfaces\HasParentEntity;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\BaseApiModel;

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
        $id = method_exists($apiModel, 'getId') ? $apiModel->getId() : null;
        if (is_null($id)) {
            throw new AmoCRMApiException('Empty id in model ' . json_encode($apiModel->toArray()));
        }

        $parentId = method_exists($apiModel, 'getEntityId') ? $apiModel->getEntityId() : null;
        if (is_null($parentId)) {
            throw new AmoCRMApiException('Parent id in model ' . json_encode($apiModel->toArray()));
        }

        //todo add HasIdInterface
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
        $with = array_intersect($with, $this->itemClass::getAvailableWith());
        if (!empty($with)) {
            $queryParams['with'] = implode(',', $with);
        }

        $parentId = $id[HasParentEntity::PARENT_ID_KEY] ?? null;
        $id = $id[HasParentEntity::ID_KEY] ?? null;

        $response = $this->request->get($this->getMethodWithParent($parentId, $id), $queryParams);

        /** @var BaseApiModel $entity */
        $entity = new $this->itemClass();

        $entity = !empty($response) ? $entity->fromArray($response) : null;

        return $entity;
    }

    /**
     * @param string|int $parentId
     * @param string|int $id
     *
     * @return string
     */
    protected function getMethodWithParent($parentId, $id): string
    {
        return sprintf($this->methodWithParent, $this->getEntityType(), $parentId, $id);
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
}
