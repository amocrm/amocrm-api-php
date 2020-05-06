<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\WebhooksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\WebhookModel;
use AmoCRM\Models\BaseApiModel;
use Exception;

class Webhooks extends BaseEntity
{
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::WEBHOOKS;

    protected $collectionClass = WebhooksCollection::class;

    protected $itemClass = WebhookModel::class;

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
     * @return BaseApiModel|null
     * @throws Exception
     */
    public function getOne($id, array $with = []): ?BaseApiModel
    {
        throw new Exception('Use get for this entity');
    }

    /**
     * @param BaseApiCollection $collection
     * @return BaseApiCollection
     * @throws Exception
     */
    public function add(BaseApiCollection $collection): BaseApiCollection
    {
        throw new Exception('Method not available for this entity');
    }

    /**
     * @param BaseApiCollection $collection
     * @return BaseApiCollection
     * @throws Exception
     */
    public function update(BaseApiCollection $collection): BaseApiCollection
    {
        throw new Exception('Method not available for this entity');
    }

    /**
     * @param BaseApiModel $apiModel
     * @return BaseApiModel
     * @throws Exception
     */
    public function updateOne(BaseApiModel $apiModel): BaseApiModel
    {
        throw new Exception('Method not available for this entity');
    }

    /**
     * Подписка на хук
     * @param WebhookModel $webhookModel
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
