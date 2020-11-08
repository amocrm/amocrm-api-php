<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\EntitiesServices\Interfaces\HasParentEntity;
use AmoCRM\EntitiesServices\Traits\WithParentEntityMethodsTrait;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
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
use AmoCRM\Models\CustomFields\CustomFieldModel;
use AmoCRM\Models\NoteModel;

/**
 * Class EntityNotes
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method null|NotesCollection get(BaseEntityFilter $filter = null, array $with = [])
 * @method NoteModel addOne(BaseApiModel $model)
 * @method NotesCollection add(BaseApiCollection $collection)
 * @method NoteModel updateOne(BaseApiModel $apiModel)
 * @method NotesCollection update(BaseApiCollection $collection)
 * @method NoteModel syncOne(BaseApiModel $apiModel, $with = [])
 * @method null|NotesCollection getByParentId(int $parentId, BaseEntityFilter $filter = null, array $with = [])
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
     * @var NotesCollection
     */
    protected $collectionClass = NotesCollection::class;

    /**
     * @var NoteModel
     */
    public const ITEM_CLASS = NoteModel::class;

    /**
     * @var string
     */
    protected $methodWithParent = 'api/v' . AmoCRMApiClient::API_VERSION . '/%s/%s/notes';

    /**
     * @param string $entityType
     *
     * @return string
     * @throws InvalidArgumentException
     */
    protected function validateEntityType(string $entityType): string
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

        return $entityType;
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
            if (array_key_exists('request_id', $entity)) {
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

    /**
     * Получение одной конкретной сущности
     * @param array $id
     * @param array $with
     *
     * @return BaseApiModel|null|NoteModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function getOne($id, array $with = []): ?BaseApiModel
    {
        $queryParams = [];
        $class = static::ITEM_CLASS;
        $with = array_intersect($with, $class::getAvailableWith());
        if (!empty($with)) {
            $queryParams['with'] = implode(',', $with);
        }

        $parentId = $id[HasParentEntity::PARENT_ID_KEY] ?? null;
        $id = $id[HasParentEntity::ID_KEY] ?? null;

        $response = $this->request->get($this->getMethodWithParent($parentId, $id), $queryParams);

        $collection = !empty($response) ? $this->collectionClass::fromArray([$response]) : null;

        return !empty($response) ? $collection->first() : null;
    }
}
