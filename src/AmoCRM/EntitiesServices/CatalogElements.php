<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\CatalogElementModel;

/**
 * Class CatalogElements
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method null|CatalogElementModel getOne($id, array $with = [])
 * @method null|CatalogElementsCollection get(BaseEntityFilter $filter = null, array $with = [])
 * @method CatalogElementModel addOne(BaseApiModel $model)
 * @method CatalogElementsCollection add(BaseApiCollection $collection)
 * @method CatalogElementModel updateOne(BaseApiModel $apiModel)
 * @method CatalogElementsCollection update(BaseApiCollection $collection)
 */
class CatalogElements extends BaseEntityIdEntity implements HasPageMethodsInterface
{
    use PageMethodsTrait;

    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/catalogs/%s/elements';

    /**
     * @var string
     */
    protected $collectionClass = CatalogElementsCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = CatalogElementModel::class;

    /**
     * @param int $entityId
     *
     * @throws NotAvailableForActionException
     */
    protected function validateEntityId(int $entityId): void
    {
        if ($entityId < EntityTypesInterface::MIN_CATALOG_ID) {
            throw new NotAvailableForActionException('Doesn\'t looks like catalog exists');
        }
    }

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (
            isset($response[AmoCRMApiRequest::EMBEDDED])
            && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CATALOG_ELEMENTS])
        ) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CATALOG_ELEMENTS];
        }

        return $entities;
    }

    /**
     * @param BaseApiModel $model
     * @param array $response
     *
     * @return BaseApiModel
     */
    protected function processUpdateOne(BaseApiModel $model, array $response): BaseApiModel
    {
        $this->processModelAction($model, $response);

        return $model;
    }

    /**
     * @param BaseApiCollection $collection
     * @param array $response
     *
     * @return BaseApiCollection
     */
    protected function processUpdate(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        return $this->processAction($collection, $response);
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
            if (array_key_exists('request_id', $entity)) {
                $initialEntity = $collection->getBy('requestId', $entity['request_id']);
                if (!empty($initialEntity)) {
                    $this->processModelAction($initialEntity, $entity);
                }
            }
        }

        return $collection;
    }

    /**
     * @param BaseApiModel|CatalogElementModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        if (isset($entity['id'])) {
            $apiModel->setId($entity['id']);
        }

        if (isset($entity['name'])) {
            $apiModel->setName($entity['name']);
        }
    }


    /**
     * @param BaseApiModel|CatalogElementModel $apiModel
     * @param array $with
     *
     * @return BaseApiModel|CatalogElementModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function syncOne(BaseApiModel $apiModel, $with = []): BaseApiModel
    {
        $this->setEntityId($apiModel->getCatalogId());

        return parent::syncOne($apiModel, $with);
    }
}
