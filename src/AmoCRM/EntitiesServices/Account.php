<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Models\AccountModel;
use AmoCRM\Models\BaseApiModel;

class Account extends BaseEntity
{
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/account';

    protected $collectionClass = BaseApiCollection::class;

    protected $itemClass = AccountModel::class;

    protected function getEntitiesFromResponse(array $response): array
    {
        return $response;
    }

    /**
     * Обновление одной конкретной сущности
     * @param array $with
     * @return BaseApiCollection|null
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function getCurrent(array $with = []): ?AccountModel
    {
        $queryParams = [];
        $with = array_intersect($with, AccountModel::getAvailableWith());
        if (!empty($with)) {
            $queryParams['with'] = implode(',', $with);
        }

        $response = $this->request->get($this->method, $queryParams);

        /** @var AccountModel $entity */
        $entity = new $this->itemClass();

        $entity = !empty($response) ? $entity->fromArray($response) : null;

        return $entity;
    }

    public function getOne($id, array $with = []): ?BaseApiModel
    {
        throw new \Exception('Use getCurrent for this entity');
    }

    /**
     * @param BaseEntityFilter $filter
     * @param array $with
     * @return BaseApiCollection|null
     * @throws \Exception
     */
    public function get(BaseEntityFilter $filter, array $with = []): ?BaseApiCollection
    {
        throw new \Exception('Use getCurrent for this entity');
    }

    /**
     * @param BaseApiCollection $collection
     * @throws \Exception
     */
    public function add(BaseApiCollection $collection)
    {
        throw new \Exception('Method not available for this entity');
    }

    /**
     * @param BaseApiCollection $collection
     * @throws \Exception
     */
    public function update(BaseApiCollection $collection)
    {
        throw new \Exception('Method not available for this entity');
    }

    /**
     * @param BaseApiModel $apiModel
     * @throws \Exception
     */
    public function updateOne(BaseApiModel $apiModel)
    {
        throw new \Exception('Method not available for this entity');
    }
}
