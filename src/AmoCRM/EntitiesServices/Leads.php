<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\AmoCRM\EntitiesServices\HasLinkMethodInterface;
use AmoCRM\AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\AmoCRM\Models\TypeAwareInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\LeadsCollection;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\CatalogElementModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\LeadModel;

class Leads extends BaseEntity implements HasLinkMethodInterface
{
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/leads';

    protected $collectionClass = LeadsCollection::class;

    protected $itemClass = LeadModel::class;

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
     * @return BaseApiCollection
     */
    protected function processUpdate(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        return $this->processAction($collection, $response);
    }

    /**
     * @param BaseApiCollection $collection
     * @param array $response
     * @return BaseApiCollection
     */
    protected function processAdd(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        return $this->processAction($collection, $response);
    }

    protected function processAction(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        $entities = $this->getEntitiesFromResponse($response);
        foreach ($entities as $entity) {
            if (!empty($entity['request_id'])) {
                $initialEntity = $collection->getBy('requestId', $entity['request_id']);
                if (!empty($initialEntity)) {
                    $this->processModelAction($initialEntity, $entity);
                }
            }
        }

        return $collection;
    }

    /**
     * @param BaseApiModel $apiModel
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
     * @param int $id
     * @param bool $isLink
     * @return string
     */
    protected function getLinkMethod(int $id, bool $isLink = true): string
    {
        $action = $isLink ? 'link' : 'unlink';
        return $this->getMethod() . '/' . $id . '/' . $action;
    }

    protected function getAvailableLinkTypes(): array
    {
        return [
            ContactModel::class,
            CatalogElementModel::class,
        ];
    }

    /**
     * @param BaseApiCollection $linkedEntities
     * @return array
     */
    protected function prepareLinkBody(BaseApiCollection $linkedEntities): array
    {
        $body = [];

        foreach ($linkedEntities as $linkedEntity) {
            if (
                $linkedEntity instanceof TypeAwareInterface &&
                in_array(get_class($linkedEntity), $this->getAvailableLinkTypes(), true)
            ) {
                $link = [
                    'to_entity_type' => $linkedEntity->getType(),
                    'to_entity_id' => $linkedEntity->getId()
                    //, 'metadata': {'updated_by': 0}
                ];
                if ($linkedEntity instanceof CatalogElementModel) {
                    $link['metadata']['to_catalog_id'] = $linkedEntity->getCatalogId();
                    if (!is_null($linkedEntity->getQuantity())) {
                        $link['metadata']['quantity'] = $linkedEntity->getQuantity();
                    }
                }
                //todo support all metadata
                $body[] = $link;
            }
        }

        return $body;
    }

    public function link(BaseApiModel $mainEntity, BaseApiCollection $linkedEntities)
    {
        $body = $this->prepareLinkBody($linkedEntities);

        $response = $this->request->post($this->getLinkMethod($mainEntity->getId(), true), $body);
        //todo add link to base model
    }

    public function unlink(BaseApiModel $mainEntity, BaseApiCollection $linkedEntities)
    {
        $body = $this->prepareLinkBody($linkedEntities);

        $response = $this->request->post($this->getLinkMethod($mainEntity->getId(), false), $body);
        //todo remove link from base model
    }
}
