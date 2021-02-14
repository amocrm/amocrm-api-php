<?php

namespace AmoCRM\Models;

use AmoCRM\EntitiesServices\Unsorted;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Models\Interfaces\CanBeLinkedInterface;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\Interfaces\TypeAwareInterface;
use AmoCRM\Models\Leads\LossReasons\LossReasonModel;
use AmoCRM\Models\Traits\GetLinkTrait;
use AmoCRM\Models\Traits\RequestIdTrait;
use AmoCRM\Models\Unsorted\Interfaces\UnsortedMetadataInterface;

use function is_null;

class LeadModel extends BaseApiModel implements TypeAwareInterface, CanBeLinkedInterface, HasIdInterface
{
    use RequestIdTrait;
    use GetLinkTrait;

    public const LOST_STATUS_ID = 143;
    public const CATALOG_ELEMENTS = 'catalog_elements';
    public const IS_PRICE_BY_ROBOT = 'is_price_modified_by_robot';
    public const LOSS_REASON = 'loss_reason';
    public const SOURCE_ID = 'source_id';
    public const CONTACTS = 'contacts';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var null|string
     */
    protected $name;

    /**
     * @var int
     */
    protected $responsibleUserId;

    /**
     * @var int
     */
    protected $groupId;

    /**
     * @var int
     */
    protected $createdBy;

    /**
     * @var int
     */
    protected $updatedBy;

    /**
     * @var int
     */
    protected $createdAt;

    /**
     * @var int
     */
    protected $updatedAt;

    /**
     * @var int
     */
    protected $accountId;

    /**
     * @var int
     */
    protected $pipelineId;

    /**
     * @var int
     */
    protected $statusId;

    /**
     * @var int
     */
    protected $closedAt;

    /**
     * @var int
     */
    protected $closestTaskAt;

    /**
     * @var int
     */
    protected $price;

    /**
     * @var int
     */
    protected $lossReasonId;

    /**
     * @var LossReasonModel|null
     */
    protected $lossReason;

    /**
     * @var bool
     */
    protected $isDeleted;

    /**
     * @var TagsCollection
     */
    protected $tags;

    /**
     * @var int|null
     */
    protected $sourceId;

    /**
     * @var CustomFieldsValuesCollection|null
     */
    protected $customFieldsValues;

    /**
     * @var int|null
     */
    protected $score;

    /**
     * @var null|bool
     */
    protected $isPriceModifiedByRobot = null;

    /**
     * @var ContactsCollection|null
     */
    protected $contacts = null;

    /**
     * @var CompanyModel|null
     */
    protected $company = null;

    /**
     * @var CatalogElementsCollection|null
     */
    protected $catalogElementsLinks = null;

    /**
     * @var null|string
     */
    protected $visitorUid;

    /**
     * Используется при создании сделки в статусе неразобранное через метод комплексного добавления
     * Если вам необходимо добавить неразобранное без проверки дублей - @see Unsorted
     *
     * @var null|UnsortedMetadataInterface
     */
    protected $metadata;

    /**
     * ID запросов для комплексного метода
     * Доступны только в результирующей модели после вызова комплексного метода добавления
     *
     * @var string[]
     */
    protected $complexRequestIds;

    /**
     * Было ли выполнено обновление или нет
     * Доступны только в результирующей модели после вызова комплексного метода добавления
     *
     * @var bool
     */
    protected $isMerged;

    public function getType(): string
    {
        return EntityTypesInterface::LEADS;
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param null|int $price
     *
     * @return self
     */
    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    /**
     * @param null|int $id
     *
     * @return self
     */
    public function setAccountId(?int $id): self
    {
        $this->accountId = $id;

        return $this;
    }


    /**
     * @return null|int
     */
    public function getGroupId(): ?int
    {
        return $this->groupId;
    }

    /**
     * @param int|null $groupId
     *
     * @return self
     */
    public function setGroupId(?int $groupId): self
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getResponsibleUserId(): ?int
    {
        return $this->responsibleUserId;
    }

    /**
     * @param null|int $userId
     *
     * @return self
     */
    public function setResponsibleUserId(?int $userId): self
    {
        $this->responsibleUserId = $userId;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    /**
     * @param null|int $userId
     *
     * @return self
     */
    public function setCreatedBy(?int $userId): self
    {
        $this->createdBy = $userId;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    /**
     * @param null|int $userId
     *
     * @return self
     */
    public function setUpdatedBy(?int $userId): self
    {
        $this->updatedBy = $userId;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    /**
     * @param null|int $timestamp
     *
     * @return self
     */
    public function setCreatedAt(?int $timestamp): self
    {
        $this->createdAt = $timestamp;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getUpdatedAt(): ?int
    {
        return $this->updatedAt;
    }

    /**
     * @param null|int $timestamp
     *
     * @return self
     */
    public function setUpdatedAt(?int $timestamp): self
    {
        $this->updatedAt = $timestamp;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getPipelineId(): ?int
    {
        return $this->pipelineId;
    }

    /**
     * @param null|int $pipelineId
     *
     * @return self
     */
    public function setPipelineId(?int $pipelineId): self
    {
        $this->pipelineId = $pipelineId;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getStatusId(): ?int
    {
        return $this->statusId;
    }

    /**
     * @param null|int $statusId
     *
     * @return self
     */
    public function setStatusId(?int $statusId): self
    {
        $this->statusId = $statusId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getClosedAt(): ?int
    {
        return $this->closedAt;
    }

    /**
     * @param int|null $timestamp
     *
     * @return self
     */
    public function setClosedAt(?int $timestamp): self
    {
        $this->closedAt = $timestamp;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getClosestTaskAt(): ?int
    {
        return $this->closestTaskAt;
    }

    /**
     * @param int|null $timestamp
     *
     * @return self
     */
    public function setClosestTaskAt(?int $timestamp): self
    {
        $this->closestTaskAt = $timestamp;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLossReasonId(): ?int
    {
        return $this->lossReasonId;
    }

    /**
     * @param int|null $id
     *
     * @return self
     */
    public function setLossReasonId(?int $id): self
    {
        $this->lossReasonId = $id;

        return $this;
    }

    /**
     * @return LossReasonModel|null
     */
    public function getLossReason(): ?LossReasonModel
    {
        return $this->lossReason;
    }

    /**
     * @param null|LossReasonModel $lossReason
     *
     * @return self
     */
    public function setLossReason(?LossReasonModel $lossReason): self
    {
        $this->lossReason = $lossReason;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    /**
     * @param null|bool $flag
     *
     * @return self
     */
    public function setIsDeleted(?bool $flag): self
    {
        $this->isDeleted = $flag;

        return $this;
    }

    /**
     * @return null|TagsCollection
     */
    public function getTags(): ?TagsCollection
    {
        return $this->tags;
    }

    /**
     * @param null|TagsCollection $tags
     *
     * @return self
     */
    public function setTags(?TagsCollection $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getSourceId(): ?int
    {
        return $this->sourceId;
    }

    /**
     * @param int|null $id
     *
     * @return self
     */
    public function setSourceId(?int $id): self
    {
        $this->sourceId = $id;

        return $this;
    }

    /**
     * @return null|CompanyModel
     */
    public function getCompany(): ?CompanyModel
    {
        return $this->company;
    }

    /**
     * @param null|CompanyModel $company
     *
     * @return self
     */
    public function setCompany(?CompanyModel $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return CustomFieldsValuesCollection|null
     */
    public function getCustomFieldsValues(): ?CustomFieldsValuesCollection
    {
        return $this->customFieldsValues;
    }

    /**
     * @param CustomFieldsValuesCollection|null $values
     *
     * @return self
     */
    public function setCustomFieldsValues(?CustomFieldsValuesCollection $values): self
    {
        $this->customFieldsValues = $values;

        return $this;
    }

    /**
     * @return ContactsCollection|null
     */
    public function getContacts(): ?ContactsCollection
    {
        return $this->contacts;
    }

    /**
     * @param null|ContactsCollection $contacts
     *
     * @return self
     */
    public function setContacts(?ContactsCollection $contacts): self
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getScore(): ?int
    {
        return $this->score;
    }

    /**
     * @param int|null $score
     *
     * @return self
     */
    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function getIsPriceModifiedByRobot(): ?bool
    {
        return $this->isPriceModifiedByRobot;
    }

    /**
     * @param null|bool $flag
     *
     * @return self
     */
    private function setIsPriceModifiedByRobot(?bool $flag): self
    {
        $this->isPriceModifiedByRobot = $flag;

        return $this;
    }

    /**
     * @param array $lead
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $lead): self
    {
        if (empty($lead['id'])) {
            throw new InvalidArgumentException('Lead id is empty in ' . json_encode($lead));
        }

        $leadModel = new self();

        $leadModel->setId((int)$lead['id']);

        if (!empty($lead['name'])) {
            $leadModel->setName($lead['name']);
        }

        if (array_key_exists('price', $lead) && !is_null($lead['price'])) {
            $leadModel->setPrice((int)$lead['price']);
        }

        if (array_key_exists('responsible_user_id', $lead) && !is_null($lead['responsible_user_id'])) {
            $leadModel->setResponsibleUserId((int)$lead['responsible_user_id']);
        }

        if (array_key_exists('group_id', $lead) && !is_null($lead['group_id'])) {
            $leadModel->setGroupId((int)$lead['group_id']);
        }

        if (!empty($lead['status_id'])) {
            $leadModel->setStatusId((int)$lead['status_id']);
        }

        if (!empty($lead['pipeline_id'])) {
            $leadModel->setPipelineId((int)$lead['pipeline_id']);
        }

        if (!empty($lead['loss_reason_id'])) {
            $leadModel->setLossReasonId($lead['loss_reason_id'] > 0 ? (int)$lead['loss_reason_id'] : null);
        }

        if (array_key_exists('source_id', $lead)) {
            $leadModel->setSourceId($lead['source_id'] > 0 ? (int)$lead['source_id'] : null);
        }

        if (!empty($lead['custom_fields_values'])) {
            $valuesCollection = new CustomFieldsValuesCollection();
            $customFieldsValues = $valuesCollection->fromArray($lead['custom_fields_values']);
            $leadModel->setCustomFieldsValues($customFieldsValues);
        }

        if (array_key_exists('created_by', $lead) && !is_null($lead['created_by'])) {
            $leadModel->setCreatedBy((int)$lead['created_by']);
        }

        if (array_key_exists('updated_by', $lead) && !is_null($lead['updated_by'])) {
            $leadModel->setUpdatedBy((int)$lead['updated_by']);
        }

        if (!empty($lead['created_at'])) {
            $leadModel->setCreatedAt($lead['created_at']);
        }

        if (!empty($lead['updated_at'])) {
            $leadModel->setUpdatedAt($lead['updated_at']);
        }

        if (!empty($lead['closed_at'])) {
            $leadModel->setClosedAt($lead['closed_at'] > 0 ? (int)$lead['closed_at'] : null);
        }

        if (!empty($lead['closest_task_at'])) {
            $leadModel->setClosestTaskAt($lead['closest_task_at'] > 0 ? (int)$lead['closest_task_at'] : null);
        }

        if (array_key_exists('is_deleted', $lead) && !is_null($lead['is_deleted'])) {
            $leadModel->setIsDeleted((bool)$lead['is_deleted']);
        }

        if (!empty($lead[AmoCRMApiRequest::EMBEDDED]['tags'])) {
            $tagsCollection = new TagsCollection();
            $tagsCollection = $tagsCollection->fromArray($lead[AmoCRMApiRequest::EMBEDDED]['tags']);
            $leadModel->setTags($tagsCollection);
        }

        if (!empty($lead[AmoCRMApiRequest::EMBEDDED][self::LOSS_REASON][0])) {
            $lossReason = LossReasonModel::fromArray($lead[AmoCRMApiRequest::EMBEDDED][self::LOSS_REASON][0]);
            $leadModel->setLossReason($lossReason);
        }

        if (!empty($lead[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::COMPANIES][0])) {
            $company = CompanyModel::fromArray($lead[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::COMPANIES][0]);
            $leadModel->setCompany($company);
        }

        if (!empty($lead[AmoCRMApiRequest::EMBEDDED][self::CONTACTS])) {
            $contactsCollection = new ContactsCollection();
            $contactsCollection = $contactsCollection->fromArray($lead[AmoCRMApiRequest::EMBEDDED][self::CONTACTS]);
            $leadModel->setContacts($contactsCollection);
        }

        if (!empty($lead[AmoCRMApiRequest::EMBEDDED][self::CATALOG_ELEMENTS])) {
            $catalogElementsCollection = new CatalogElementsCollection();
            $catalogElementsCollection = $catalogElementsCollection->fromArray(
                $lead[AmoCRMApiRequest::EMBEDDED][self::CATALOG_ELEMENTS]
            );
            $leadModel->setCatalogElementsLinks($catalogElementsCollection);
        }

        $leadModel->setScore(isset($lead['score']) && $lead['score'] > 0 ? (int)$lead['score'] : null);

        if (!empty($lead['account_id'])) {
            $leadModel->setAccountId((int)$lead['account_id']);
        }

        if (array_key_exists('is_price_modified_by_robot', $lead) && !is_null($lead['is_price_modified_by_robot'])) {
            $leadModel->setIsPriceModifiedByRobot((bool)$lead['is_price_modified_by_robot']);
        }

        return $leadModel;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = [
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'responsible_user_id' => $this->getResponsibleUserId(),
            'group_id' => $this->getGroupId(),
            'status_id' => $this->getStatusId(),
            'pipeline_id' => $this->getPipelineId(),
            'loss_reason_id' => $this->getLossReasonId(),
            'source_id' => $this->getSourceId(),
            'created_by' => $this->getCreatedBy(),
            'updated_by' => $this->getUpdatedBy(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'closed_at' => $this->getClosedAt(),
            'closest_task_at' => $this->getClosestTaskAt(),
            'is_deleted' => $this->getIsDeleted(),
            'custom_fields_values' => is_null($this->getCustomFieldsValues())
                ? null
                : $this->getCustomFieldsValues()->toArray(),
            'score' => $this->getScore(),
            'account_id' => $this->getAccountId(),
        ];

        if (!is_null($this->getId())) {
            $result['id'] = $this->getId();
        }

        if (!is_null($this->getCatalogElementsLinks())) {
            $result['catalog_elements'] = $this->getCatalogElementsLinks()->toArray();
        }

        if (!is_null($this->getIsPriceModifiedByRobot())) {
            $result['is_price_modified_by_robot'] = $this->getIsPriceModifiedByRobot();
        }

        if (!is_null($this->getTags())) {
            $result['tags'] = $this->getTags()->toArray();
        }

        if (!is_null($this->getLossReason())) {
            $result['loss_reason'] = $this->getLossReason()->toArray();
        }

        if (!is_null($this->getCompany())) {
            $result['company'] = $this->getCompany()->toArray();
        }

        if (!is_null($this->getContacts())) {
            $result['contacts'] = $this->getContacts()->toArray();
        }

        return $result;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = [];

        if (!is_null($this->getId())) {
            $result['id'] = $this->getId();
        }

        if (!is_null($this->getName())) {
            $result['name'] = $this->getName();
        }

        if (!is_null($this->getPrice())) {
            $result['price'] = $this->getPrice();
        }

        if (!is_null($this->getResponsibleUserId())) {
            $result['responsible_user_id'] = $this->getResponsibleUserId();
        }

        if (!is_null($this->getStatusId())) {
            $result['status_id'] = $this->getStatusId();
        }

        if (!is_null($this->getPipelineId())) {
            $result['pipeline_id'] = $this->getPipelineId();
        }

        if ($this->getStatusId() === self::LOST_STATUS_ID && !is_null($this->getLossReasonId())) {
            $result['loss_reason_id'] = $this->getLossReasonId();
        }

        if (!is_null($this->getCreatedBy())) {
            $result['created_by'] = $this->getCreatedBy();
        }

        if (!is_null($this->getUpdatedBy())) {
            $result['updated_by'] = $this->getUpdatedBy();
        }

        if (!is_null($this->getCreatedAt())) {
            $result['created_at'] = $this->getCreatedAt();
        }

        if (!is_null($this->getClosedAt())) {
            $result['closed_at'] = $this->getClosedAt();
        }

        if (!is_null($this->getCustomFieldsValues())) {
            $result['custom_fields_values'] = $this->getCustomFieldsValues()->toApi();
        }

        if (!is_null($this->getTags())) {
            $result[AmoCRMApiRequest::EMBEDDED]['tags'] = $this->getTags()->toEntityApi();
        }

        if (!is_null($this->getVisitorUid())) {
            $result['visitor_uid'] = $this->getVisitorUid();
        }

        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId);
        }

        //Если это создание - то можно передать id контактов для привязки
        if (is_null($this->getId()) && !is_null($this->getContacts())) {
            $result[AmoCRMApiRequest::EMBEDDED]['contacts'] = $this->getContacts()->toLeadApi();
        }

        //Если это создание - то можно передать id компании для привязки
        if (is_null($this->getId()) && !is_null($this->getCompany())) {
            $result[AmoCRMApiRequest::EMBEDDED]['companies'][] = [
                'id' => $this->getCompany()->getId()
            ];
        }

        $result['request_id'] = $this->getRequestId();

        return $result;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toComplexApi(?string $requestId = "0"): array
    {
        $result = $this->toApi($requestId);

        unset($result['id']);

        if (!is_null($this->getContacts())) {
            $contact = $this->getContacts()->first();
            $result[AmoCRMApiRequest::EMBEDDED]['contacts'] = [$contact->toApi(null)];
        }

        if (!is_null($this->getCompany())) {
            $result[AmoCRMApiRequest::EMBEDDED]['companies'] = [$this->getCompany()->toApi(null)];
        }

        if (!is_null($this->getMetadata())) {
            $result[AmoCRMApiRequest::EMBEDDED]['metadata'] = $this->getMetadata()->toComplexApi();
        }

        $result['request_id'] = $this->getRequestId();

        return $result;
    }

    /**
     * @return array
     */
    public static function getAvailableWith(): array
    {
        return [
            self::CATALOG_ELEMENTS,
            self::IS_PRICE_BY_ROBOT,
            self::CONTACTS,
            self::SOURCE_ID,
            self::LOSS_REASON,
        ];
    }

    /**
     * @return CatalogElementsCollection|null
     */
    public function getCatalogElementsLinks(): ?CatalogElementsCollection
    {
        return $this->catalogElementsLinks;
    }

    /**
     * @param CatalogElementsCollection|null $catalogElementsLinks
     * @return LeadModel
     */
    public function setCatalogElementsLinks(?CatalogElementsCollection $catalogElementsLinks): self
    {
        $this->catalogElementsLinks = $catalogElementsLinks;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVisitorUid(): ?string
    {
        return $this->visitorUid;
    }

    /**
     * @param string|null $visitorUid
     *
     * @return LeadModel
     */
    public function setVisitorUid(?string $visitorUid): LeadModel
    {
        $this->visitorUid = $visitorUid;

        return $this;
    }

    /**
     * @return array|null
     */
    protected function getMetadataForLink(): ?array
    {
        $result = null;

        if (!is_null($this->getUpdatedBy())) {
            $result['updated_by'] = $this->getUpdatedBy();
        }

        return $result;
    }

    /**
     * @return UnsortedMetadataInterface|null
     */
    public function getMetadata(): ?UnsortedMetadataInterface
    {
        return $this->metadata;
    }

    /**
     * @param UnsortedMetadataInterface|null $metadata
     *
     * @return LeadModel
     */
    public function setMetadata(?UnsortedMetadataInterface $metadata): LeadModel
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @return null|string[]
     */
    public function getComplexRequestIds(): ?array
    {
        return $this->complexRequestIds;
    }

    /**
     * @param string[] $complexRequestIds
     *
     * @return LeadModel
     */
    public function setComplexRequestIds(array $complexRequestIds): LeadModel
    {
        $this->complexRequestIds = $complexRequestIds;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMerged(): bool
    {
        return $this->isMerged ?? false;
    }

    /**
     * @param bool $isMerged
     *
     * @return LeadModel
     */
    public function setIsMerged(bool $isMerged): LeadModel
    {
        $this->isMerged = $isMerged;

        return $this;
    }
}
