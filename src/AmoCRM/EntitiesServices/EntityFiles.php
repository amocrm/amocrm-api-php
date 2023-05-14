<?php

declare(strict_types=1);

namespace AmoCRM\EntitiesServices;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\FileLinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\FileLinkModel;

use function in_array;

class EntityFiles extends BaseEntityTypeEntityIdEntity implements HasDeleteMethodInterface
{
    /** @var string */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/%s/%d/' . EntityTypesInterface::FILES;

    /** @var string */
    protected $collectionClass = FileLinksCollection::class;

    /** @var string */
    public const ITEM_CLASS = FileLinkModel::class;

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

        $response = $this->request->get($this->getMethod(), $queryParams);

        return $this->createCollection($response);
    }

    public function add(BaseApiCollection $collection): BaseApiCollection
    {
        $response = $this->request->put($this->getMethod(), $collection->toApi());

        return $this->processAdd($collection, $response);
    }

    public function delete(BaseApiCollection $collection): bool
    {
        $this->request->delete($this->getMethod(), $collection->toApi());

        return true;
    }

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        return $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::FILES] ?? [];
    }

    public function getOne($id, array $with = []): ?BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    public function addOne(BaseApiModel $model): BaseApiModel
    {
        /** @var BaseApiCollection $collection */
        $collection = new $this->collectionClass();
        $collection->add($model);
        $collection = $this->add($collection);

        return $collection->first();
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

    /**
     * @param BaseApiModel|FileLinkModel $model
     *
     * @return bool
     */
    public function deleteOne(BaseApiModel $model): bool
    {
        /** @var BaseApiCollection $collection */
        $collection = new $this->collectionClass();
        $collection->add($model);

        return $this->delete($collection);
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
            preg_match("/" . EntityTypesInterface::CATALOGS . ":(\d+)/", $entityType, $matches);
            if (isset($matches[1]) && (int)$matches[1] > EntityTypesInterface::MIN_CATALOG_ID) {
                $entityType = EntityTypesInterface::CATALOGS . '/' . (int)$matches[1];
            } else {
                throw new InvalidArgumentException('Entity is not supported by this method');
            }
        }

        return $entityType;
    }

    /**
     * @param BaseApiCollection $collection
     * @param array $response
     *
     * @return BaseApiCollection
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
     */
    protected function processAction(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        $entities = $this->getEntitiesFromResponse($response);
        foreach ($entities as $entity) {
            if (array_key_exists('file_uuid', $entity)) {
                $initialEntity = $collection->getBy('file_uuid', $entity['file_uuid']);
                if ($initialEntity !== null) {
                    $this->processModelAction($initialEntity, $entity);
                }
            }
        }

        return $collection;
    }

    /**
     * @param BaseApiModel|FileLinkModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        if (isset($entity['file_uuid'])) {
            $apiModel->setFileUuid($entity['file_uuid']);
        }
    }
}
