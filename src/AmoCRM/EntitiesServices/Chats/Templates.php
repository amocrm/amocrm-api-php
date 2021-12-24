<?php

declare(strict_types=1);

namespace AmoCRM\EntitiesServices\Chats;

use AmoCRM\Collections\Chats\Templates\Buttons\ButtonsCollection;
use AmoCRM\Collections\Chats\Templates\TemplatesCollection;
use AmoCRM\EntitiesServices\BaseEntity;
use AmoCRM\EntitiesServices\HasDeleteMethodInterface;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\Chats\TemplatesFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Chats\Templates\TemplateModel;

/**
 * Class Templates
 *
 * @package AmoCRM\EntitiesServices\Chats
 *
 * @method null|TemplateModel getOne($id, array $with = [])
 * @method null|TemplatesCollection get(TemplatesFilter $filter = null, array $with = [])
 * @method TemplateModel updateOne(BaseApiModel $apiModel)
 * @method TemplatesCollection update(BaseApiCollection $collection)
 * @method TemplateModel syncOne(BaseApiModel $apiModel, $with = [])
 */
class Templates extends BaseEntity implements HasPageMethodsInterface, HasDeleteMethodInterface
{
    use PageMethodsTrait;

    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/chats/templates';

    /**
     * @var string
     */
    protected $collectionClass = TemplatesCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = TemplateModel::class;

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        return $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CHAT_TEMPLATES] ?? $response;
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

        if (isset($entities['id'])) {
            $entity = $entities;
            $initialEntity = $collection->getBy('id', $entity['id']);
            if ($initialEntity !== null) {
                $this->processModelAction($initialEntity, $entity);
            } else {
                $newEntity = TemplateModel::fromArray($entity);
                $collection->add($newEntity);
            }
        } else {
            foreach ($entities as $entity) {
                if (array_key_exists('request_id', $entity)) {
                    $initialEntity = $collection->getBy('requestId', $entity['request_id']);
                    if ($initialEntity !== null) {
                        $this->processModelAction($initialEntity, $entity);
                    }
                } elseif (array_key_exists('id', $entity)) {
                    $initialEntity = $collection->getBy('id', $entity['id']);
                    if ($initialEntity !== null) {
                        $this->processModelAction($initialEntity, $entity);
                    } else {
                        $newEntity = TemplateModel::fromArray($entity);
                        $collection->add($newEntity);
                    }
                }
            }
        }

        return $collection;
    }

    /**
     * @param BaseApiModel|TemplateModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        if (isset($entity['id'])) {
            $apiModel->setId($entity['id']);
        }

        if (isset($entity['account_id'])) {
            $apiModel->setAccountId($entity['account_id']);
        }

        if (isset($entity['name'])) {
            $apiModel->setName($entity['name']);
        }

        if (isset($entity['content'])) {
            $apiModel->setContent($entity['content']);
        }

        if (isset($entity['created_at'])) {
            $apiModel->setCreatedAt($entity['created_at']);
        }

        if (isset($entity['updated_at'])) {
            $apiModel->setUpdatedAt($entity['updated_at']);
        }

        if (isset($entity['is_editable'])) {
            $apiModel->setIsEditable((bool)$entity['is_editable']);
        }

        if (isset($entity['external_id'])) {
            $apiModel->setExternalId($entity['external_id']);
        }

        $buttonsCollection = isset($entity['buttons']) && !empty($entity['buttons']) && is_array($entity['buttons'])
            ? ButtonsCollection::fromArray($entity['buttons'])
            : new ButtonsCollection();

        $apiModel->setButtons($buttonsCollection);
    }

    /**
     * @param BaseApiModel|TemplateModel $model
     *
     * @return bool
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function deleteOne(BaseApiModel $model): bool
    {
        $method = $this->getMethod() . '/' . $model->getId();
        $response = $this->request->delete($method);

        return $response['result'];
    }

    /**
     * @param BaseApiCollection $collection
     *
     * @return bool
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function delete(BaseApiCollection $collection): bool
    {
        $body = array_map(
            static function ($item) {
                return ['id' => (int)$item['id']];
            },
            $collection->toApi()
        );

        $result = $this->request->delete($this->getMethod(), $body);

        return $result['result'] ?? false;
    }
}
