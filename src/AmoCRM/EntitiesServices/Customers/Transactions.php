<?php

namespace AmoCRM\EntitiesServices\Customers;

use AmoCRM\EntitiesServices\HasDeleteMethodInterface;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Customers\Transactions\TransactionsCollection;
use AmoCRM\EntitiesServices\BaseEntityIdEntity;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Customers\Transactions\TransactionModel;

/**
 * Class Transactions
 *
 * @package AmoCRM\EntitiesServices\Customers
 *
 * @method TransactionModel getOne($id, array $with = []) : ?TransactionModel
 * @method TransactionsCollection get(BaseEntityFilter $filter = null, array $with = []) : ?TransactionsCollection
 * @method TransactionModel addOne(BaseApiModel $model) : TransactionModel
 * @method TransactionsCollection add(BaseApiCollection $collection) : TransactionsCollection
 * @method TransactionModel syncOne(BaseApiModel $apiModel, $with = []) : TransactionModel
 */
class Transactions extends BaseEntityIdEntity implements HasDeleteMethodInterface
{
    //todo support common list
    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::CUSTOMERS . '/%s/' . EntityTypesInterface::CUSTOMERS_TRANSACTIONS;

    /**
     * @var string
     */
    protected $collectionClass = TransactionsCollection::class;

    /**
     * @var string
     */
    protected $itemClass = TransactionModel::class;

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CUSTOMERS_TRANSACTIONS])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::CUSTOMERS_TRANSACTIONS];
        }

        return $entities;
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
     * @param BaseApiModel|TransactionModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        if (isset($entity['id'])) {
            $apiModel->setId($entity['id']);
        }

        if (array_key_exists('comment', $entity)) {
            $apiModel->setComment($entity['comment']);
        }

        if (array_key_exists('price', $entity)) {
            $apiModel->setPrice($entity['price']);
        }
    }

    /**
     * @param BaseApiModel|TransactionModel $model
     *
     * @return bool
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function deleteOne(BaseApiModel $model): bool
    {
        $result = $this->request->delete($this->getMethod() . '/' . $model->getId());

        return $result['result'];
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
     * @param BaseApiModel $collection
     *
     * @return BaseApiModel
     * @throws NotAvailableForActionException
     */
    public function updateOne(BaseApiModel $collection): BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiCollection $collection
     *
     * @return bool
     * @throws NotAvailableForActionException
     */
    public function delete(BaseApiCollection $collection): bool
    {
        throw new NotAvailableForActionException('This entity supports only deleteOne method');
    }
}
