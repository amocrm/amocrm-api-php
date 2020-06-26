<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Collections\ShortLinks\ShortLinksCollection;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\ShortLinks\ShortLinkModel;

/**
 * Class ShortLinks
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method ShortLinksCollection add(ShortLinksCollection $collection) : BaseApiCollection
 * @method ShortLinkModel addOne(ShortLinkModel $model) : BaseApiModel
 */
class ShortLinks extends BaseEntity
{
    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::SHORT_LINKS;

    /**
     * @var string
     */
    protected $collectionClass = ShortLinksCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = ShortLinkModel::class;

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::SHORT_LINKS])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::SHORT_LINKS];
        }

        return $entities;
    }


    /**
     * @param BaseApiCollection|ShortLinksCollection $collection
     * @param array $response
     *
     * @return BaseApiCollection
     */
    protected function processAdd(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        return $this->processAction($collection, $response);
    }

    /**
     * @param BaseApiCollection|ShortLinksCollection $collection
     * @param array $response
     *
     * @return BaseApiCollection
     */
    protected function processAction(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        $entities = $this->getEntitiesFromResponse($response);
        foreach ($entities as $entity) {
            if (array_key_exists('url', $entity)) {
                $initialEntity = $collection->getBy('entity_id', $entity['metadata']['entity_id']);
                if (!empty($initialEntity)) {
                    $this->processModelAction($initialEntity, $entity);
                }
            }
        }

        return $collection;
    }

    /**
     * @param BaseApiModel|ShortLinkModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        if (isset($entity['url'])) {
            $apiModel->setUrl($entity['url']);
        }

        if (isset($entity['metadata']['entity_id'])) {
            $apiModel->setEntityId($entity['metadata']['entity_id']);
        }

        if (isset($entity['metadata']['entity_type'])) {
            $apiModel->setEntityType($entity['metadata']['entity_type']);
        }
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
