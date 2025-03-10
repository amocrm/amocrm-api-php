<?php

declare(strict_types=1);

namespace AmoCRM\EntitiesServices;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\EventTypesCollections;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\EventTypeModel;

/**
 * Class EventTypes
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method null|EventTypesCollections get(?BaseEntityFilter $filter = null, array $with = [])
 */
class EventTypes extends BaseEntity
{
    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::EVENTS . '/types';

    /**
     * @var string
     */
    protected $collectionClass = EventTypesCollections::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = EventTypeModel::class;

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        return $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::EVENTS_TYPES] ?? [];
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
    public function add(BaseApiCollection $collection): BaseApiCollection
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
