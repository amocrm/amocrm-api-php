<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Collections\UsersCollection;
use AmoCRM\Exceptions\AmoCRMApiConnectExceptionException;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMApiHttpClientException;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Exceptions\AmoCRMApiTooManyRedirectsException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Rights\RightModel;
use AmoCRM\Models\RoleModel;
use AmoCRM\Models\UserModel;
use InvalidArgumentException;

/**
 * Class Users
 *
 * @package AmoCRM\EntitiesServices
 * @method null|UserModel getOne($id, array $with = [])
 * @method null|UsersCollection get(?BaseEntityFilter $filter = null, array $with = [])
 * @method UserModel addOne(BaseApiModel $model)
 * @method UsersCollection add(BaseApiCollection $collection)
 * @method UserModel syncOne(BaseApiModel $apiModel, $with = [])
 */
class Users extends BaseEntity implements HasPageMethodsInterface
{
    use PageMethodsTrait;

    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::USERS;

    protected $collectionClass = UsersCollection::class;

    public const ITEM_CLASS = UserModel::class;

    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::USERS])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::USERS];
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
     * @throws AmoCRMApiException
     * @throws AmoCRMApiNoContentException
     * @throws AmoCRMApiHttpClientException
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiConnectExceptionException
     * @throws AmoCRMApiTooManyRedirectsException
     */
    public function activate(UsersCollection $users): bool
    {
        try {
            $this->request->post(sprintf('%s/activate', $this->getMethod()), $this->prepareUserIdsPayload($users));
        } catch (AmoCRMApiNoContentException $exception) {
            return true;
        }

        return false;
    }

    /**
     * @throws AmoCRMApiException
     * @throws AmoCRMApiNoContentException
     * @throws AmoCRMApiHttpClientException
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiConnectExceptionException
     * @throws AmoCRMApiTooManyRedirectsException
     */
    public function deactivate(UsersCollection $users): bool
    {
        try {
            $this->request->post(sprintf('%s/deactivate', $this->getMethod()), $this->prepareUserIdsPayload($users));
        } catch (AmoCRMApiNoContentException $exception) {
            return true;
        }

        return false;
    }

    protected function prepareUserIdsPayload(UsersCollection $users): array
    {
        if ($users->isEmpty()) {
            throw new InvalidArgumentException('Collection is empty');
        }

        if ($users->count() > 10) {
            throw new InvalidArgumentException('Maximum 10 users per request');
        }

        $payload = [];
        foreach ($users as $user) {
            /** @var UserModel $user */
            if (!$id = $user->getId()) {
                throw new InvalidArgumentException('User without ID in collection');
            }

            $payload[] = ['id' => $id];
        }

        return $payload;
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
     * @param BaseApiModel|UserModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        if (isset($entity['id'])) {
            $apiModel->setId($entity['id']);
        }

        if (isset($entity['name'])) {
            $apiModel->setName($entity['name']);
        }

        if (isset($entity['email'])) {
            $apiModel->setEmail($entity['email']);
        }

        if (isset($entity['rights'])) {
            $apiModel->setRights(RightModel::fromArray($entity['rights']));
        }
    }
}
