<?php

namespace AmoCRM\EntitiesServices\Customers;

use AmoCRM\AmoCRM\EntitiesServices\HasLinkMethodInterface;
use AmoCRM\AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\AmoCRM\Models\TypeAwareInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Customers\CustomersCollection;
use AmoCRM\EntitiesServices\BaseEntity;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\CatalogElementModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\Customers\CustomerModel;

class Customers extends BaseEntity implements HasLinkMethodInterface, HasPageMethodsInterface
{
    use PageMethodsTrait;

    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::CUSTOMERS;

    protected $collectionClass = CustomersCollection::class;

    protected $itemClass = CustomerModel::class;

    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CUSTOMERS])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CUSTOMERS];
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
            //todo company
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
                    'entity_type' => $linkedEntity->getType(),
                    'entity_id' => $linkedEntity->getId()
                    //, 'metadata': {'updated_by': 0}
                ];
                if ($linkedEntity instanceof CatalogElementModel) {
                    $link['metadata']['catalog_id'] = $linkedEntity->getCatalogId();
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

    /**
     * @param BaseApiModel $mainEntity
     * @param BaseApiCollection $linkedEntities
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function link(BaseApiModel $mainEntity, BaseApiCollection $linkedEntities)
    {
        $body = $this->prepareLinkBody($linkedEntities);

        $response = $this->request->post($this->getLinkMethod($mainEntity->getId(), true), $body);
        //todo add link to base model
    }

    /**
     * @param BaseApiModel $mainEntity
     * @param BaseApiCollection $linkedEntities
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function unlink(BaseApiModel $mainEntity, BaseApiCollection $linkedEntities)
    {
        $body = $this->prepareLinkBody($linkedEntities);

        $response = $this->request->post($this->getLinkMethod($mainEntity->getId(), false), $body);
        //todo remove link from base model
    }
}
