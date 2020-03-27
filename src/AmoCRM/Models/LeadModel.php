<?php

namespace AmoCRM\Models;

use InvalidArgumentException;

class LeadModel extends BaseApiModel
{
    const CATALOG_ELEMENTS_LINKS = 'catalog_elements_links';
    const IS_PRICE_BY_ROBOT = 'is_price_modified_by_robot';
    const LOSS_REASON = 'loss_reason';
    const CONTACTS = 'contacts';
//
//    /**
//     * @var array
//     */
//    protected $appends = [];

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
     * @var bool
     */
    protected $isDeleted;

//    /**
//     * @var EntityTagsCollection|null
//     */
//    protected $tags;

    /**
     * @var int|null
     */
    protected $sourceId;

    /**
     * @var int
     */
    protected $companyId;

//    /**
//     * @var CustomFieldsValuesApiCollection|null
//     */
//    protected $customFields;

    /**
     * @var int|null
     */
    protected $mainContactId;

    /**
     * @var int|null
     */
    protected $score;

//    /**
//     * @var CatalogElementsLinksCollection|null
//     */
//    protected $catalogElementsLinks = null;

    /**
     * @var null|bool
     */
    protected $isPriceModifiedByRobot = null;

//    /**
//     * @var LossReasonCollection
//     */
//    protected $lossReason = null;
//
//    /**
//     * @var ContactsApiCollection
//     */
//    protected $contacts = null;

    /**
     * @var null|int
     */
    protected $requestId = null;

    /**
     * @return int
     */
    public function getId(): int
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
     * @param int $price
     *
     * @return self
     */
    public function setPrice(int $price): self
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
     * @param int $id
     *
     * @return self
     */
    public function setAccountId(int $id): self
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
     * @param int $groupId
     *
     * @return self
     */
    public function setGroupId(int $groupId): self
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
     * @param int $userId
     *
     * @return self
     */
    public function setResponsibleUserId(int $userId): self
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
     * @param int $userId
     *
     * @return self
     */
    public function setCreatedBy(int $userId): self
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
     * @param int $userId
     *
     * @return self
     */
    public function setUpdatedBy(int $userId): self
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
     * @param int $timestamp
     *
     * @return self
     */
    public function setCreatedAt(int $timestamp): self
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
     * @param int $timestamp
     *
     * @return self
     */
    public function setUpdatedAt(int $timestamp): self
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
     * @param int $pipelineId
     *
     * @return self
     */
    public function setPipelineId(int $pipelineId): self
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
     * @param int $statusId
     *
     * @return self
     */
    public function setStatusId(int $statusId): self
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
     * @return null|bool
     */
    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $flag
     *
     * @return self
     */
    public function setIsDeleted(bool $flag): self
    {
        $this->isDeleted = $flag;

        return $this;
    }

//    /**
//     * @return EntityTagsCollection|null
//     */
//    public function getTags(): ?EntityTagsCollection
//    {
//        return $this->tags;
//    }
//
//    /**
//     * @param EntityTagsCollection|null $tags
//     *
//     * @return self
//     */
//    public function setTags(?EntityTagsCollection $tags): self
//    {
//        $this->tags = $tags;
//
//        return $this;
//    }

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
     * @return int|null
     */
    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    /**
     * @param int|null $id
     *
     * @return self
     */
    public function setCompanyId(?int $id): self
    {
        $this->companyId = $id;

        return $this;
    }

//    /**
//     * @return CustomFieldsValuesApiCollection|null
//     */
//    public function getCustomFields(): ?CustomFieldsValuesApiCollection
//    {
//        return $this->customFields;
//    }
//
//    /**
//     * @param CustomFieldsValuesApiCollection|null $values
//     *
//     * @return self
//     */
//    public function setCustomFields(?CustomFieldsValuesApiCollection $values): self
//    {
//        $this->customFields = $values;
//
//        return $this;
//    }

    /**
     * @return int|null
     */
    public function getMainContactId(): ?int
    {
        return $this->mainContactId;
    }

    /**
     * @param int|null $id
     *
     * @return self
     */
    public function setMainContactId(?int $id): self
    {
        $this->mainContactId = $id;

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

//    /**
//     * @return CatalogElementsLinksCollection|null
//     */
//    public function getCatalogElementsLinks(): ?CatalogElementsLinksCollection
//    {
//        return $this->catalogElementsLinks;
//    }
//
//    /**
//     * @param CatalogElementsLinksCollection $collection
//     *
//     * @return self
//     */
//    public function setCatalogElementsLinks(?CatalogElementsLinksCollection $collection): self
//    {
//        $this->catalogElementsLinks = $collection;
//
//        return $this;
//    }

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
    public function setIsPriceModifiedByRobot(?bool $flag): self
    {
        $this->isPriceModifiedByRobot = $flag;

        return $this;
    }

//    /**
//     * @return null|LossReasonCollection
//     */
//    public function getLossReason(): ?LossReasonCollection
//    {
//        return $this->lossReason;
//    }
//
//    /**
//     * @param null|LossReasonCollection $lossReason
//     *
//     * @return self
//     */
//    public function setLossReason(?LossReasonCollection $lossReason = null): self
//    {
//        $this->lossReason = $lossReason;
//
//        return $this;
//    }
//
//    /**
//     * @return null|ContactsApiCollection
//     */
//    public function getContacts(): ?ContactsApiCollection
//    {
//        return $this->contacts;
//    }
//
//    /**
//     * @param null|ContactsApiCollection $contactsApiCollection
//     *
//     * @return self
//     */
//    public function setContacts(?ContactsApiCollection $contactsApiCollection = null): self
//    {
//        $this->contacts = $contactsApiCollection;
//
//        return $this;
//    }

//    /**
//     * @param string $group
//     *
//     * @return self
//     */
//    public static function fromJson(string $json): self
//    {
//        $jsonDecoded = json_decode($json, true);
//        if (empty($jsonDecoded)) {
//            throw new \ParseError('Invalid json string ' . $json);
//        }
//
//        return self::fromArray($jsonDecoded);
//    }

    /**
     * @param array $lead
     *
     * @return self
     */
    public static function fromArray(array $lead): self
    {
        if (empty($lead['id'])) {
            //todo amocrm exception
            throw new InvalidArgumentException('Lead id is empty in ' . json_encode($lead));
        }

        $leadModel = new self();

        $leadModel->setId((int)$lead['id']);

        if (!empty($lead['name'])) {
            $leadModel->setName($lead['name']);
        }
        if (!is_null($lead['price'])) {
            $leadModel->setPrice((int)$lead['price']);
        }
        if (!is_null($lead['responsible_user_id'])) {
            $leadModel->setResponsibleUserId((int)$lead['responsible_user_id']);
        }
        if (!is_null($lead['group_id'])) {
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
        if (!empty($lead['source_id'])) {
            $leadModel->setSourceId($lead['source_id'] > 0 ? (int)$lead['source_id'] : null);
        }
        if (!empty($lead['main_contact_id'])) {
            $leadModel->setMainContactId($lead['main_contact_id'] > 0 ? (int)$lead['main_contact_id'] : null);
        }
        if (!empty($lead['linked_company_id'])) {
            $leadModel->setCompanyId($lead['linked_company_id'] > 0 ? (int)$lead['linked_company_id'] : null);
        }
//        $customFieldsValues = null;
//        if (!empty($lead['custom_fields'])) {
//            $customFieldsValues = CustomFieldsValuesApiCollection::fromArray($lead['custom_fields']);
//        }
//        $leadModel->setCustomFields($customFieldsValues);
        if (!is_null($lead['created_by'])) {
            $leadModel->setCreatedBy((int)$lead['created_by']);
        }
        if (!is_null($lead['updated_by'])) {
            $leadModel->setUpdatedBy((int)$lead['updated_by']);
        }
        if (!empty($lead['created_at'])) {
            $leadModel->setCreatedAt($lead['created_at']);
        }
        if (!empty($lead['updated_at'])) {
            $leadModel->setUpdatedAt($lead['updated_at']);
        }
        if (!empty($lead['date_close'])) {
            $leadModel->setClosedAt($lead['date_close'] > 0 ? (int)$lead['date_close'] : null);
        }
        if (!empty($lead['closed_at'])) {
            $leadModel->setClosestTaskAt($lead['closed_at'] > 0 ? (int)$lead['closed_at'] : null);
        }
        if (!is_null($lead['is_deleted'])) {
            $leadModel->setIsDeleted((bool)$lead['is_deleted']);
        }
//        $leadModel->setTags(empty($lead['tags']) ? null : EntityTagsCollection::fromArray($lead['tags']));
        $leadModel->setScore(isset($lead['score']) && $lead['score'] > 0 ? (int)$lead['score'] : null);
        if (!empty($lead['account_id'])) {
            $leadModel->setAccountId((int)$lead['account_id']);
        }
        if (!empty($lead['request_id'])) {
            $leadModel->setRequestId($lead['request_id']);
        }

        if (isset($lead['is_price_modified_by_robot']) && !is_null($lead['is_price_modified_by_robot'])) {
            $leadModel->setIsPriceModifiedByRobot((bool)$lead['is_price_modified_by_robot']);
        }

        return $leadModel;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        $value = null;

        if ($this->offsetExists($offset)) {
            $value = $this->$offset;
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        // will not be implemented
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        // will not be implemented
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = [
            'id' => $this->getId(),
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
//            'custom_fields_values' =>
//                $this->getCustomFields() ? $this->getCustomFields()->toArray() : $this->getCustomFields(),
            'score' => $this->getScore(),
            'account_id' => $this->getAccountId(),
        ];
//
//        $appends = $this->getAppends();
//
//        if ($this->getTags()) {
//            $result['tags'] = $this->getTags();
//        } else {
//            $result['tags'] = new EntityTagsCollection();
//        }
//
//        if (in_array(self::CATALOG_ELEMENTS_LINKS, $appends, true)) {
//            $result['catalog_elements_links'] = $this->getCatalogElementsLinks();
//        }
//
//        if (in_array(self::IS_PRICE_BY_ROBOT, $appends, true)) {
        if (!is_null($this->getIsPriceModifiedByRobot())) {
            $result['is_price_modified_by_robot'] = $this->getIsPriceModifiedByRobot();
        }
//        }
//
//        if (in_array(self::LOSS_REASON, $appends, true)) {
//            $result['loss_reason'] = new HalResource();
//            if (!empty($this->getLossReason())) {
//                $result['loss_reason'] = $this->getLossReason();
//            }
//        }
//
//        $companiesCollection = new CompaniesApiCollection();
//        if (!empty($this->getCompanyId())) {
//            $company = new LeadCompanyApi();
//            $company->setId($this->getCompanyId());
//            $companiesCollection->add($company);
//        }
//        $result['companies'] = $companiesCollection;
//
//        if (in_array(self::CONTACTS, $appends, true)) {
//            $result['contacts'] = $this->getContacts();
//        }
//
//        if (in_array(self::REQUEST_ID, $appends, true)) {
//            $result['request_id'] = $this->getRequestId();
//        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @return array
     */
    public function getArrayCopy(): array
    {
        return $this->toArray();
    }

    /**
     * @return int|null
     */
    public function getRequestId(): ?int
    {
        return $this->requestId;
    }

    /**
     * @param int|null $requestId
     * @return LeadModel
     */
    public function setRequestId(?int $requestId): self
    {
        $this->requestId = $requestId;

        return $this;
    }

    /**
     * @return array
     */
    public static function getAvailableWith(): array
    {
        return [
            self::CATALOG_ELEMENTS_LINKS,
            self::IS_PRICE_BY_ROBOT,
            self::CONTACTS,
            self::LOSS_REASON,
        ];
    }
}
