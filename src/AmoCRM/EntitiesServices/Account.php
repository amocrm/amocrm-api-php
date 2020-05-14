<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Models\AccountModel;
use AmoCRM\Models\BaseApiModel;

/**
 * Class Account
 *
 * @package AmoCRM\EntitiesServices
 */
class Account extends BaseEntity
{
    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/account';

    /**
     * @var string
     */
    protected $collectionClass = BaseApiCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = AccountModel::class;

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        return $response;
    }

    /**
     * Обновление одной конкретной сущности
     * @param array $with
     *
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
        $class = static::ITEM_CLASS;
        $entity = new $class();

        $entity = !empty($response) ? $entity->fromArray($response) : null;

        return $entity;
    }

    /**
     * @param int|string $id
     * @param array $with
     *
     * @return BaseApiModel|null
     * @throws NotAvailableForActionException
     */
    public function getOne($id, array $with = []): ?BaseApiModel
    {
        throw new NotAvailableForActionException('Use getCurrent for this entity');
    }

    /**
     * @param null|BaseEntityFilter $filter
     * @param array $with
     *
     * @return BaseApiCollection|null
     * @throws NotAvailableForActionException
     */
    public function get(BaseEntityFilter $filter = null, array $with = []): ?BaseApiCollection
    {
        throw new NotAvailableForActionException('Use getCurrent for this entity');
    }

    /**
     * @param BaseApiCollection $collection
     *
     * @return BaseApiCollection
     * @throws NotAvailableForActionException
     */
    public function add(BaseApiCollection $collection): BaseApiCollection
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiModel $model
     *
     * @return BaseApiModel
     * @throws NotAvailableForActionException
     */
    public function addOne(BaseApiModel $model): BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiCollection $collection
     *
     * @return BaseApiCollection
     * @throws NotAvailableForActionException
     */
    public function update(BaseApiCollection $collection): BaseApiCollection
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiModel $apiModel
     *
     * @return BaseApiModel
     * @throws NotAvailableForActionException
     */
    public function updateOne(BaseApiModel $apiModel): BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiModel $apiModel
     * @param array $with
     *
     * @return BaseApiModel
     * @throws NotAvailableForActionException
     */
    public function syncOne(BaseApiModel $apiModel, $with = []): BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }
}
