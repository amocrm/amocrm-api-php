<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\NotesCollection;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\NoteModel;

class EntityNotes extends BaseEntityTypeEntity
{
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/%s/notes';

    protected $collectionClass = NotesCollection::class;

    protected $itemClass = NoteModel::class;

    protected function validateEntityType(string $entityType): void
    {
        $availableTypes = [
            EntityTypesInterface::LEADS,
            EntityTypesInterface::CONTACTS,
            EntityTypesInterface::COMPANIES,
            EntityTypesInterface::CUSTOMERS,
        ];

        if (!in_array($entityType, $availableTypes, true)) {
            throw new \Exception('This method doesn\'t support given entity type');
        }
    }

    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::NOTES])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::NOTES];
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

    public function getOne($id, array $with = []): ?BaseApiModel
    {
        throw new \Exception('No such method for this entity');
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
            } elseif (!empty($entity['id'])) {
                $initialEntity = $collection->getBy('id', $entity['id']);
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

        if (isset($entity['entity_id'])) {
            $apiModel->setEntityId($entity['entity_id']);
        }
    }
}
