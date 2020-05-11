<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\EntitiesServices\Interfaces\HasParentEntity;
use AmoCRM\EntitiesServices\Traits\WithParentEntityMethodsTrait;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\NotesCollection;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\NoteModel;

/**
 * Class EntityNotes
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method NoteModel getOne(array $id, array $with = []) : ?NoteModel
 * @method NotesCollection get(BaseEntityFilter $filter = null, array $with = []) : ?NotesCollection
 * @method NoteModel addOne(BaseApiModel $model) : NoteModel
 * @method NotesCollection add(BaseApiCollection $collection) : NotesCollection
 * @method NoteModel updateOne(BaseApiModel $apiModel) : NoteModel
 * @method NotesCollection update(BaseApiCollection $collection) : NotesCollection
 * @method NoteModel syncOne(BaseApiModel $apiModel, $with = []) : NoteModel
 */
class EntityNotes extends BaseEntityTypeEntity implements HasPageMethodsInterface, HasParentEntity
{
    use PageMethodsTrait;
    use WithParentEntityMethodsTrait;

    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/%s/notes';

    /**
     * @var string
     */
    //todo delete this after deploy method without entityId
    protected $methodWithParent = 'api/v' . AmoCRMApiClient::API_VERSION . '/%s/%s/notes/%s';

    /**
     * @var string
     */
    protected $collectionClass = NotesCollection::class;

    /**
     * @var string
     */
    protected $itemClass = NoteModel::class;

    /**
     * @param string $entityType
     *
     * @throws InvalidArgumentException
     */
    protected function validateEntityType(string $entityType): void
    {
        $availableTypes = [
            EntityTypesInterface::LEADS,
            EntityTypesInterface::CONTACTS,
            EntityTypesInterface::COMPANIES,
            EntityTypesInterface::CUSTOMERS,
        ];

        if (!in_array($entityType, $availableTypes, true)) {
            throw new InvalidArgumentException('This method doesn\'t support given entity type');
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

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::NOTES])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::NOTES];
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
     * @param BaseApiModel|NoteModel $apiModel
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
