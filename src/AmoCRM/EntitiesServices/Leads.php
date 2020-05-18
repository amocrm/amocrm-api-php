<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\EntitiesServices\Traits\LinkMethodsTrait;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\LeadModel;

/**
 * Class Leads
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method LeadModel getOne($id, array $with = []) : ?LeadModel
 * @method LeadsCollection get(BaseEntityFilter $filter = null, array $with = []) : ?LeadsCollection
 * @method LeadModel addOne(BaseApiModel $model) : LeadModel
 * @method LeadsCollection add(BaseApiCollection $collection) : LeadsCollection
 * @method LeadModel updateOne(BaseApiModel $apiModel) : LeadModel
 * @method LeadsCollection update(BaseApiCollection $collection) : LeadsCollection
 * @method LeadModel syncOne(BaseApiModel $apiModel, $with = []) : LeadModel
 * @method link(BaseApiModel $mainEntity, $linkedEntities) : LinksCollection
 * @method unlink(BaseApiModel $mainEntity, $linkedEntities) : bool
 */
class Leads extends BaseEntity implements HasLinkMethodInterface, HasPageMethodsInterface
{
    use PageMethodsTrait;
    use LinkMethodsTrait;

    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/leads';

    /**
     * @var string
     */
    protected $collectionClass = LeadsCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = LeadModel::class;

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::LEADS])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::LEADS];
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
     * @param BaseApiModel|LeadModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        if (isset($entity['id'])) {
            $apiModel->setId($entity['id']);
        }

        if (isset($entity['updated_at'])) {
            $apiModel->setUpdatedAt($entity['updated_at']);
        }
    }

    /**
     * @return array
     */
    protected function getAvailableLinkTypes(): array
    {
        return [
            EntityTypesInterface::CONTACTS,
            EntityTypesInterface::CATALOG_ELEMENTS_FULL,
            EntityTypesInterface::COMPANIES,
        ];
    }
}
