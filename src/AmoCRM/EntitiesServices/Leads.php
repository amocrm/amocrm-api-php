<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\EntitiesServices\Traits\LinkMethodsTrait;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\CompanyModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\LeadModel;

use function is_null;

/**
 * Class Leads
 *
 * @package AmoCRM\EntitiesServices
 *
 * @method null|LeadModel getOne($id, array $with = [])
 * @method null|LeadsCollection get(BaseEntityFilter $filter = null, array $with = [])
 * @method LeadModel addOne(BaseApiModel $model)
 * @method LeadsCollection add(BaseApiCollection $collection)
 * @method LeadModel updateOne(BaseApiModel $apiModel)
 * @method LeadsCollection update(BaseApiCollection $collection)
 * @method LeadModel syncOne(BaseApiModel $apiModel, $with = [])
 * @method LinksCollection link(BaseApiModel $mainEntity, $linkedEntities)
 * @method bool unlink(BaseApiModel $mainEntity, $linkedEntities)
 */
class Leads extends BaseEntity implements HasLinkMethodInterface, HasPageMethodsInterface
{
    use PageMethodsTrait;
    use LinkMethodsTrait;

    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/leads';

    /**
     * @var string
     */
    protected $collectionClass = LeadsCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = LeadModel::class;

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::LEADS])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::LEADS];
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
     * @param BaseApiModel|LeadModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        if (isset($entity['id'])) {
            $apiModel->setId($entity['id']);
        }

        if (isset($entity['updated_at'])) {
            $apiModel->setUpdatedAt($entity['updated_at']);
        }
    }

    /**
     * @return array
     */
    protected function getAvailableLinkTypes(): array
    {
        return [
            EntityTypesInterface::CONTACTS,
            EntityTypesInterface::CATALOG_ELEMENTS_FULL,
            EntityTypesInterface::COMPANIES,
        ];
    }

    /**
     * Добавление коллекции сущностей
     * @param LeadsCollection $collection
     *
     * @return LeadsCollection
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function addComplex(LeadsCollection $collection): LeadsCollection
    {
        $response = $this->request->post($this->getComplexMethod(), $collection->toComplexApi());

        return $this->processComplexAction($response);
    }

    /**
     * Добавление сщуности
     *
     * @param LeadModel $model
     *
     * @return LeadModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function addOneComplex(LeadModel $model): LeadModel
    {
        /** @var LeadsCollection $collection */
        $collection = new $this->collectionClass();
        $collection->add($model);
        $collection = $this->addComplex($collection);

        return $collection->first();
    }

    /**
     * @return string
     */
    protected function getComplexMethod(): string
    {
        return $this->method . '/complex';
    }

    /**
     * @param array $response
     *
     * @return LeadsCollection
     */
    protected function processComplexAction(array $response): LeadsCollection
    {
        $resultCollection = new LeadsCollection();

        foreach ($response as $responseLead) {
            $lead = (new LeadModel())
                ->setId($responseLead['id'])
                ->setComplexRequestIds($responseLead['request_id'])
                ->setIsMerged((bool)$responseLead['merged']);

            if (!is_null($responseLead['contact_id'])) {
                $contacts = (new ContactsCollection())
                    ->add(
                        (new ContactModel())
                            ->setId($responseLead['contact_id'])
                    );
                $lead->setContacts($contacts);
            }

            if (!is_null($responseLead['company_id'])) {
                $lead->setCompany((new CompanyModel())
                    ->setId($responseLead['company_id']));
            }

            $resultCollection->add($lead);
        }

        return $resultCollection;
    }
}
