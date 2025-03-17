<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Collections\ChatLinksCollection;
use AmoCRM\Filters\ChatLinksFilter;
use AmoCRM\Models\ChatLinkModel;
use AmoCRM\EntitiesServices\Traits\LinkMethodsTrait;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\ContactModel;

/**
 * Class Contacts
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method null|ContactModel getOne($id, array $with = [])
 * @method null|ContactsCollection get(?BaseEntityFilter $filter = null, array $with = [])
 * @method ContactModel addOne(BaseApiModel $model)
 * @method ContactsCollection add(BaseApiCollection $collection)
 * @method ContactModel updateOne(BaseApiModel $apiModel)
 * @method ContactsCollection update(BaseApiCollection $collection)
 * @method ContactModel syncOne(BaseApiModel $apiModel, $with = [])
 */
class Contacts extends BaseEntity implements HasLinkMethodInterface, HasPageMethodsInterface
{
    use PageMethodsTrait;
    use LinkMethodsTrait;

    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::CONTACTS;

    /**
     * @var string
     */
    protected $collectionClass = ContactsCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = ContactModel::class;

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CONTACTS])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CONTACTS];
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
     * @param BaseApiModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        /** @var ContactModel $apiModel */
        if (isset($entity['id'])) {
            $apiModel->setId($entity['id']);
        }

        if (isset($entity['updated_at'])) {
            $apiModel->setUpdatedAt($entity['updated_at']);
        }
    }

    /**
     * @return array|string[]
     */
    protected function getAvailableLinkTypes(): array
    {
        return [
            EntityTypesInterface::CATALOG_ELEMENTS_FULL,
            EntityTypesInterface::LEADS,
            EntityTypesInterface::CUSTOMERS,
            EntityTypesInterface::COMPANIES,
        ];
    }

    /**
     * Получение списка привязанных чатов
     *
     * @param ChatLinksFilter $filter
     * @return ChatLinksCollection
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function getChats(ChatLinksFilter $filter): ChatLinksCollection
    {
        $response = $this->request->get($this->getLinkChatsMethod(), $filter->buildFilter());

        return $this->processLinkChatsAction($response);
    }

    /**
     * @param ChatLinksCollection $linksCollection
     * @return ChatLinksCollection
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function linkChats(ChatLinksCollection $linksCollection): ChatLinksCollection
    {
        $response = $this->request->post($this->getLinkChatsMethod(), $linksCollection->toApi());

        return $this->processLinkChatsAction($response);
    }

    /**
     * @return string
     */
    protected function getLinkChatsMethod(): string
    {
        return $this->method . '/chats';
    }

    /**
     * @param array $response
     * @return ChatLinksCollection
     */
    protected function processLinkChatsAction(array $response): ChatLinksCollection
    {
        $resultCollection = new ChatLinksCollection();

        foreach ($response['_embedded']['chats'] as $linkChat) {
            $linkChat = (new ChatLinkModel())
                ->setContactId($linkChat['contact_id'])
                ->setChatId($linkChat['chat_id'])
                ->setRequestId($linkChat['request_id'] ?? 0);

            $resultCollection->add($linkChat);
        }

        return $resultCollection;
    }
}
