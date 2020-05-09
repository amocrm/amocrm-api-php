<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Leads\Unsorted\FormsUnsortedCollection;
use AmoCRM\Collections\Leads\Unsorted\SipUnsortedCollection;
use AmoCRM\Collections\Leads\Unsorted\UnsortedCollection;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Unsorted\AcceptUnsortedModel;
use AmoCRM\Models\Unsorted\BaseUnsortedModel;
use AmoCRM\Models\Unsorted\DeclineUnsortedModel;
use AmoCRM\Models\Unsorted\LinkUnsortedModel;
use AmoCRM\Models\Unsorted\UnsortedSummaryModel;

class Unsorted extends BaseEntity implements HasPageMethodsInterface
{
    use PageMethodsTrait;

    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/leads/unsorted';

    protected $collectionClass = UnsortedCollection::class;

    protected $itemClass = BaseUnsortedModel::class;

    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::UNSORTED])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::UNSORTED];
        }

        return $entities;
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

    protected function processAction(BaseApiCollection $collection, array $response): BaseApiCollection
    {
        $entities = $this->getEntitiesFromResponse($response);
        foreach ($entities as $entity) {
            if (!empty($entity['request_id'])) {
                $initialEntity = $collection->getBy('requestId', $entity['request_id']);
                if (!empty($initialEntity)) {
                    $this->processModelAction($initialEntity, $entity);
                }
            }
        }

        return $collection;
    }

    /**
     * Принятие неразорбранного
     * @param BaseApiModel $unsortedModel
     * @param array $body
     * @return AcceptUnsortedModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function accept(BaseApiModel $unsortedModel, $body = []): AcceptUnsortedModel
    {
        /** @var $unsortedModel BaseUnsortedModel */
        $response = $this->request->post($this->getMethod() . '/' . $unsortedModel->getUid() . '/accept', $body);

        return AcceptUnsortedModel::fromArray($response);
    }

    /**
     * Откроление неразорбранного
     * @param BaseApiModel $unsortedModel
     * @param array $body
     * @return DeclineUnsortedModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function decline(BaseApiModel $unsortedModel, $body = []): DeclineUnsortedModel
    {
        /** @var $unsortedModel BaseUnsortedModel */
        $response = $this->request->delete($this->getMethod() . '/' . $unsortedModel->getUid() . '/decline', $body);

        return DeclineUnsortedModel::fromArray($response);
    }

    /**
     * Привязка неразорбранного
     * @param BaseApiModel $unsortedModel
     * @param array $body
     * @return LinkUnsortedModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function link(BaseApiModel $unsortedModel, $body = []): LinkUnsortedModel
    {
        /** @var $unsortedModel BaseUnsortedModel */
        if (
            !in_array(
                $unsortedModel->getCategory(),
                [BaseUnsortedModel::CATEGORY_CODE_CHATS, BaseUnsortedModel::CATEGORY_CODE_MAIL],
                true
            )
        ) {
            throw new NotAvailableForActionException('Only chats and mail');
        }
        $response = $this->request->post($this->getMethod() . '/' . $unsortedModel->getUid() . '/link', $body);

        return LinkUnsortedModel::fromArray($response);
    }

    /**
     * Статистика по неразобранному
     * @param BaseEntityFilter $filter
     * @return UnsortedSummaryModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function summary(BaseEntityFilter $filter): UnsortedSummaryModel
    {
        $queryParams = [];
        if ($filter instanceof BaseEntityFilter) {
            $queryParams = $filter->buildFilter();
        }

        $response = $this->request->get($this->getMethod() . '/summary', $queryParams);

        return UnsortedSummaryModel::fromArray($response);
    }

    /**
     * @param BaseApiModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        /** @var $apiModel BaseUnsortedModel */
        //todo get more data from response
        if (isset($entity['uid'])) {
            $apiModel->setUid($entity['uid']);
        }
    }

    /**
     * Добавление коллекции сущностей
     * @param BaseApiCollection $collection
     * @return BaseApiCollection
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function add(BaseApiCollection $collection): BaseApiCollection
    {
        /** @var UnsortedCollection $collection */
        if (
            !$collection->getCategory() ||
            !in_array(
                $collection->getCategory(),
                [BaseUnsortedModel::CATEGORY_CODE_SIP, BaseUnsortedModel::CATEGORY_CODE_FORMS],
                true
            )
        ) {
            throw new NotAvailableForActionException('Only forms and sip');
        }

        $response = $this->request->post($this->getMethod() . '/' . $collection->getCategory(), $collection->toApi());
        $collection = $this->processAdd($collection, $response);

        return $collection;
    }

    /**
     * Добавление сщуности
     * @param BaseApiModel $model
     * @return BaseApiModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function addOne(BaseApiModel $model): BaseApiModel
    {
        /** @var BaseUnsortedModel $model */
        if (
            !$model->getCategory() ||
            !in_array(
                $model->getCategory(),
                [BaseUnsortedModel::CATEGORY_CODE_SIP, BaseUnsortedModel::CATEGORY_CODE_FORMS],
                true
            )
        ) {
            throw new NotAvailableForActionException('Only forms and sip');
        }

        switch ($model->getCategory()) {
            case BaseUnsortedModel::CATEGORY_CODE_SIP:
                $collection = new SipUnsortedCollection();
                break;
            case BaseUnsortedModel::CATEGORY_CODE_FORMS:
                $collection = new FormsUnsortedCollection();
                break;
            default:
                throw new NotAvailableForActionException('Only forms and sip');
        }

        $collection->add($model);
        $response = $this->request->post($this->getMethod(), $collection->toApi());
        $collection = $this->processAdd($collection, $response);

        return $collection->first();
    }


    /**
     * @param BaseApiCollection $collection
     * @return BaseApiCollection
     * @throws NotAvailableForActionException
     */
    public function update(BaseApiCollection $collection): BaseApiCollection
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiModel $apiModel
     * @return BaseApiModel
     * @throws NotAvailableForActionException
     */
    public function updateOne(BaseApiModel $apiModel): BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }
}
