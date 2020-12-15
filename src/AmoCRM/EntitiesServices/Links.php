<?php

declare(strict_types=1);

namespace AmoCRM\AmoCRM\EntitiesServices;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\EntitiesServices\BaseEntityTypeEntity;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\LinkModel;

use function in_array;

class Links extends BaseEntityTypeEntity
{
    /** @var string */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/%s';

    /** @var string */
    protected $collectionClass = LinksCollection::class;

    /** @var string */
    public const ITEM_CLASS = LinkModel::class;

    /**
     * @param BaseEntityFilter|null $filter
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

        $response = $this->request->get($this->getMethod() . '/' . EntityTypesInterface::LINKS, $queryParams);

        return $this->createCollection($response);
    }

    public function add(BaseApiCollection $collection): BaseApiCollection
    {
        $this->request->post($this->getMethod() . '/link', $collection->toApi());

        return $collection;
    }

    public function delete(BaseApiCollection $collection): BaseApiCollection
    {
        $this->request->post($this->getMethod() . '/unlink', $collection->toApi());

        return $collection;
    }

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        return $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::LINKS] ?? [];
    }

    public function getOne($id, array $with = []): ?BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    public function addOne(BaseApiModel $model): BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    public function update(BaseApiCollection $collection): BaseApiCollection
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    public function updateOne(BaseApiModel $apiModel): BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    public function syncOne(BaseApiModel $apiModel, $with = []): BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    protected function validateEntityType(string $entityType): string
    {
        $availableEntities = [
            EntityTypesInterface::CONTACTS,
            EntityTypesInterface::LEADS,
            EntityTypesInterface::CUSTOMERS,
            EntityTypesInterface::COMPANIES,
        ];

        if (!in_array($entityType, $availableEntities, true)) {
            throw new InvalidArgumentException('Entity is not supported by this method');
        }

        return $entityType;
    }
}
