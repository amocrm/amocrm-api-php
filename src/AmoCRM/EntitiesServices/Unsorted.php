<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Helpers\EntityTypesInterface;
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
use AmoCRM\Models\Unsorted\FormUnsortedModel;
use AmoCRM\Models\Unsorted\LinkUnsortedModel;
use AmoCRM\Models\Unsorted\SipUnsortedModel;
use AmoCRM\Models\Unsorted\UnsortedSummaryModel;

/**
 * Class Unsorted
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method BaseUnsortedModel getOne($id, array $with = []) : ?BaseUnsortedModel
 * @method UnsortedCollection get(BaseEntityFilter $filter = null, array $with = []) : ?UnsortedCollection
 */
class Unsorted extends BaseEntity implements HasPageMethodsInterface
{
    use PageMethodsTrait;

    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/leads/unsorted';

    /**
     * @var string
     */
    protected $collectionClass = UnsortedCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = BaseUnsortedModel::class;

    /**
     * @param array $response
     *
     * @return array
     */
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
     *
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
     * Принятие неразорбранного
     * @param BaseApiModel|BaseUnsortedModel $unsortedModel
     * @param array $body
     *
     * @return AcceptUnsortedModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function accept(BaseApiModel $unsortedModel, $body = []): AcceptUnsortedModel
    {
        $response = $this->request->post($this->getMethod() . '/' . $unsortedModel->getUid() . '/accept', $body);

        return AcceptUnsortedModel::fromArray($response);
    }

    /**
     * Откроление неразорбранного
     * @param BaseApiModel|BaseUnsortedModel $unsortedModel
     * @param array $body
     *
     * @return DeclineUnsortedModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function decline(BaseApiModel $unsortedModel, $body = []): DeclineUnsortedModel
    {
        $response = $this->request->delete($this->getMethod() . '/' . $unsortedModel->getUid() . '/decline', $body);

        return DeclineUnsortedModel::fromArray($response);
    }

    /**
     * Привязка неразорбранного
     * @param BaseApiModel|BaseUnsortedModel $unsortedModel
     * @param array $body
     *
     * @return LinkUnsortedModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function link(BaseApiModel $unsortedModel, $body = []): LinkUnsortedModel
    {
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
     *
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
     * @param BaseApiModel|BaseUnsortedModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        if (isset($entity['uid'])) {
            $apiModel->setUid($entity['uid']);
        }
    }

    /**
     * Добавление коллекции сущностей
     * @param BaseApiCollection|UnsortedCollection $collection
     *
     * @return BaseApiCollection
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function add(BaseApiCollection $collection): BaseApiCollection
    {
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
     * @param BaseApiModel|BaseUnsortedModel|SipUnsortedModel|FormUnsortedModel $model
     *
     * @return BaseApiModel|BaseUnsortedModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function addOne(BaseApiModel $model): BaseApiModel
    {
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
     * @return BaseUnsortedModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function syncOne(BaseApiModel $apiModel, $with = []): BaseApiModel
    {
        /** @var BaseUnsortedModel $apiModel */
        return $this->mergeModels($this->getOne($apiModel->getUid(), $with), $apiModel);
    }

    /**
     * @param BaseApiModel $objectA
     * @param BaseApiModel $objectB
     *
     * @throws InvalidArgumentException
     */
    protected function checkModelsClasses(
        BaseApiModel $objectA,
        BaseApiModel $objectB
    ) {
        if (!$objectB instanceof $objectA) {
            throw new InvalidArgumentException('Can not merge 2 different objects');
        }
    }
}
