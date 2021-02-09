<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\WebhooksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\WebhookModel;
use AmoCRM\Models\BaseApiModel;

/**
 * Class Webhooks
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method null|WebhooksCollection get(WebhookModel $filter = null, array $with = [])
 */
class Webhooks extends BaseEntity
{
    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::WEBHOOKS;

    /**
     * @var string
     */
    protected $collectionClass = WebhooksCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = WebhookModel::class;

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::WEBHOOKS])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::WEBHOOKS];
        }

        return $entities;
    }

    /**
     * @param int|string $id
     * @param array $with
     *
     * @return BaseApiModel|null
     * @throws NotAvailableForActionException
     */
    public function getOne($id, array $with = []): ?BaseApiModel
    {
        throw new NotAvailableForActionException('Use get for this entity');
    }

    /**
     * @param BaseApiModel $model
     *
     * @return BaseApiModel
     * @throws NotAvailableForActionException
     */
    public function addOne(BaseApiModel $model): BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiCollection $collection
     *
     * @return BaseApiCollection
     * @throws NotAvailableForActionException
     */
    public function add(BaseApiCollection $collection): BaseApiCollection
    {
        throw new NotAvailableForActionException('Method not available for this entity');
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


    /**
     * Подписка на хук
     * @param WebhookModel $webhookModel
     *
     * @return WebhookModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function subscribe(WebhookModel $webhookModel): WebhookModel
    {
        $response = $this->request->post($this->getMethod(), $webhookModel->toApi());
        foreach ($response as $key => $value) {
            $webhookModel->$key = $value;
        }

        return $webhookModel;
    }

    /**
     * Отписка от хука
     * @param WebhookModel $webhookModel
     *
     * @return bool
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function unsubscribe(WebhookModel $webhookModel): bool
    {
        $result = $this->request->delete($this->getMethod(), $webhookModel->toUnsubscribeApi());

        return $result['result'];
    }
}
