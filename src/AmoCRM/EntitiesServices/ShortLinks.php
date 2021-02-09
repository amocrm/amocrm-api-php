<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Collections\ShortLinks\ShortLinksCollection;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Exceptions\CollectionKeysNotSequentialException;
use AmoCRM\Exceptions\CollectionAndResponseKeysNotIndenticalException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Exceptions\StringCollectionKeyException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\ShortLinks\ShortLinkModel;

/**
 * Class ShortLinks
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method ShortLinksCollection add(ShortLinksCollection $collection)
 * @method ShortLinkModel addOne(ShortLinkModel $model)
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
     * Проверка ключей коллекции сущности
     * Для корректного сопоставления переданных и возвращённых моделей
     * необходимо убедиться, что коллекция не имеет строковых ключей,
     * и числовые ключи являются последовательностью [0 .. size-1]
     *
     * @param BaseApiCollection
     *
     * @throws StringCollectionKeyException
     * @throws ArrayKeysNotSequentialException
     */
    protected function validateCollectionKeys(BaseApiCollection $collection): void
    {
        $collectionKeys = $collection->keys();

        $stringKeys = array_filter(
            $collectionKeys,
            'is_string'
        );

        if (!empty($stringKeys)) {
            throw new StringCollectionKeyException();
        }

        $collectionKeysCount = count($collectionKeys);
        $estimatedKeys       = $collectionKeysCount === 0 ?
                                [] : range(0, $collectionKeysCount - 1);

        if ($collectionKeys !== $estimatedKeys) {
            throw new CollectionKeysNotSequentialException();
        }
    }

    /**
     * Проверка ключей коллекции и добавление коллекции сущностей
     * @param BaseApiCollection
     *
     * @return BaseApiCollection
     *
     * @throws StringCollectionKeyException
     * @throws ArrayKeysNotSequentialException
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function add(BaseApiCollection $collection): BaseApiCollection
    {
        $this->validateCollectionKeys($collection);

        return parent::add($collection);
    }

    /**
     * @param BaseApiCollection|ShortLinksCollection $collection
     * @param array $response
     *
     * @throws CollectionAndResponseKeysNotIndenticalException
     * @return BaseApiCollection
     */
    protected function processAction(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        $entities = $this->getEntitiesFromResponse($response);

        $collectionKeys = $collection->keys();
        $entitiesKeys   = array_keys($entities);

        if ($collectionKeys !== $entitiesKeys) {
            throw new CollectionAndResponseKeysNotIndenticalException();
        }

        foreach ($entitiesKeys as $key) {
            $this->processModelAction($collection[$key], $entities[$key]);
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
