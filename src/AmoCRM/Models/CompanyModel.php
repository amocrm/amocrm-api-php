<?php

namespace AmoCRM\Models;

use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\Interfaces\CanBeLinkedInterface;
use AmoCRM\Models\Interfaces\CanReturnDeletedInterface;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\Interfaces\TypeAwareInterface;
use AmoCRM\Models\Traits\GetLinkTrait;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\Customers\CustomersCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Models\Traits\RequestIdTrait;

use function is_null;

class CompanyModel extends BaseApiModel implements
    TypeAwareInterface,
    CanBeLinkedInterface,
    HasIdInterface,
    CanReturnDeletedInterface
{
    use RequestIdTrait;
    use GetLinkTrait;

    public const LEADS = 'leads';
    public const CUSTOMERS = 'customers';
    public const CONTACTS = 'contacts';
    public const CATALOG_ELEMENTS = 'catalog_elements';

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
     * @var null|int
     */
    protected $closestTaskAt;

    /**
     * @var int
     */
    protected $accountId;

    /**
     * @var null|TagsCollection
     */
    protected $tags;

    /**
     * @var CustomFieldsValuesCollection|null
     */
    protected $customFieldsValues;

    /**
     * @var ContactsCollection|null
     */
    protected $contacts = null;

    /**
     * @var LeadsCollection|null
     */
    protected $leads = null;

    /**
     * @var CustomersCollection|null
     */
    protected $customers = null;

    /**
     * @var CatalogElementsCollection|null
     */
    protected $catalogElementsLinks = null;

    public function getType(): string
    {
        return EntityTypesInterface::COMPANIES;
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
     * @return CatalogElementsCollection|null
     */
    public function getCatalogElementsLinks(): ?CatalogElementsCollection
    {
        return $this->catalogElementsLinks;
    }

    /**
     * @param CatalogElementsCollection|null $catalogElementsLinks
     * @return CompanyModel
     */
    public function setCatalogElementsLinks(CatalogElementsCollection $catalogElementsLinks): self
    {
        $this->catalogElementsLinks = $catalogElementsLinks;

        return $this;
    }

    /**
     * @return LeadsCollection|null
     */
    public function getLeads(): ?LeadsCollection
    {
        return $this->leads;
    }

    /**
     * @param LeadsCollection $leads
     * @return CompanyModel
     */
    public function setLeads(LeadsCollection $leads): self
    {
        $this->leads = $leads;

        return $this;
    }

    /**
     * @return null|ContactsCollection
     */
    public function getContacts(): ?ContactsCollection
    {
        return $this->contacts;
    }

    /**
     * @param ContactsCollection $contacts
     *
     * @return self
     */
    public function setContacts(ContactsCollection $contacts): self
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * @return null|CustomersCollection
     */
    public function getCustomers(): ?CustomersCollection
    {
        return $this->customers;
    }

    /**
     * @param CustomersCollection $customersCollection
     * @return self
     */
    public function setCustomers(CustomersCollection $customersCollection): self
    {
        $this->customers = $customersCollection;

        return $this;
    }

    /**
     * @param array $company
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $company): self
    {
        if (empty($company['id'])) {
            throw new InvalidArgumentException('Contact id is empty in ' . json_encode($company));
        }

        $companyModel = new self();

        $companyModel->setId((int)$company['id']);

        if (!empty($company['name'])) {
            $companyModel->setName($company['name']);
        }
        if (isset($company['responsible_user_id']) && !is_null($company['responsible_user_id'])) {
            $companyModel->setResponsibleUserId((int)$company['responsible_user_id']);
        }
        if (isset($company['group_id']) && !is_null($company['group_id'])) {
            $companyModel->setGroupId((int)$company['group_id']);
        }

        if (!empty($company['custom_fields_values'])) {
            $valuesCollection = new CustomFieldsValuesCollection();
            $customFieldsValues = $valuesCollection->fromArray($company['custom_fields_values']);
            $companyModel->setCustomFieldsValues($customFieldsValues);
        }

        if (isset($company['created_by']) && !is_null($company['created_by'])) {
            $companyModel->setCreatedBy((int)$company['created_by']);
        }
        if (isset($company['updated_by']) && !is_null($company['updated_by'])) {
            $companyModel->setUpdatedBy((int)$company['updated_by']);
        }
        if (!empty($company['created_at'])) {
            $companyModel->setCreatedAt($company['created_at']);
        }
        if (!empty($company['updated_at'])) {
            $companyModel->setUpdatedAt($company['updated_at']);
        }
        if (!empty($company['closest_task_at'])) {
            $companyModel->setClosestTaskAt($company['closest_task_at'] > 0 ? (int)$company['closest_task_at'] : null);
        }

        if (!empty($company[AmoCRMApiRequest::EMBEDDED]['tags'])) {
            $tagsCollection = new TagsCollection();
            $tagsCollection = $tagsCollection->fromArray($company[AmoCRMApiRequest::EMBEDDED]['tags']);
            $companyModel->setTags($tagsCollection);
        }

        if (!empty($company[AmoCRMApiRequest::EMBEDDED][self::CONTACTS])) {
            $contactsCollection = new ContactsCollection();
            $contactsCollection = $contactsCollection->fromArray($company[AmoCRMApiRequest::EMBEDDED][self::CONTACTS]);
            $companyModel->setContacts($contactsCollection);
        }
        if (!empty($company[AmoCRMApiRequest::EMBEDDED][self::LEADS])) {
            $leadsCollection = new LeadsCollection();
            $leadsCollection = $leadsCollection->fromArray($company[AmoCRMApiRequest::EMBEDDED][self::LEADS]);
            $companyModel->setLeads($leadsCollection);
        }
        if (!empty($company[AmoCRMApiRequest::EMBEDDED][self::CUSTOMERS])) {
            $customersCollection = new CustomersCollection();
            $customersCollection = $customersCollection->fromArray($company[AmoCRMApiRequest::EMBEDDED][self::CUSTOMERS]);
            $companyModel->setCustomers($customersCollection);
        }

        if (!empty($company[AmoCRMApiRequest::EMBEDDED][self::CATALOG_ELEMENTS])) {
            $catalogElementsCollection = new CatalogElementsCollection();
            $catalogElementsCollection = $catalogElementsCollection->fromArray(
                $company[AmoCRMApiRequest::EMBEDDED][self::CATALOG_ELEMENTS]
            );
            $companyModel->setCatalogElementsLinks($catalogElementsCollection);
        }

        if (!empty($company['account_id'])) {
            $companyModel->setAccountId((int)$company['account_id']);
        }

        return $companyModel;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = [
            'name' => $this->getName(),
            'responsible_user_id' => $this->getResponsibleUserId(),
            'group_id' => $this->getGroupId(),
            'created_by' => $this->getCreatedBy(),
            'updated_by' => $this->getUpdatedBy(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'closest_task_at' => $this->getClosestTaskAt(),
            'custom_fields_values' => is_null($this->getCustomFieldsValues())
                ? null
                : $this->getCustomFieldsValues()->toArray(),
            'account_id' => $this->getAccountId(),
        ];

        if (!is_null($this->getId())) {
            $result['id'] = $this->getId();
        }

        if (!is_null($this->getTags())) {
            $result['tags'] = $this->getTags()->toArray();
        }

        if (!is_null($this->getCatalogElementsLinks())) {
            $result['catalog_elements'] = $this->getCatalogElementsLinks()->toArray();
        }

        if (!is_null($this->getContacts())) {
            $result['contacts'] = $this->getContacts()->toArray();
        }

        if (!is_null($this->getLeads())) {
            $result['leads'] = $this->getLeads()->toArray();
        }

        if (!is_null($this->getCustomers())) {
            $result['customers'] = $this->getCustomers()->toArray();
        }

        return $result;
    }

    public function toApi(?string $requestId = "0"): array
    {
        $result = [];

        if (!is_null($this->getId())) {
            $result['id'] = $this->getId();
        }

        if (!is_null($this->getName())) {
            $result['name'] = $this->getName();
        }

        if (!is_null($this->getResponsibleUserId())) {
            $result['responsible_user_id'] = $this->getResponsibleUserId();
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

        if (!is_null($this->getCustomFieldsValues())) {
            $result['custom_fields_values'] = $this->getCustomFieldsValues()->toApi();
        }

        if (!is_null($this->getTags())) {
            $result[AmoCRMApiRequest::EMBEDDED]['tags'] = $this->getTags()->toEntityApi();
        }

        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId);
        }

        if (!is_null($this->getRequestId())) {
            $result['request_id'] = $this->getRequestId();
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function getAvailableWith(): array
    {
        return [
            self::LEADS,
            self::CUSTOMERS,
            self::CONTACTS,
            self::CATALOG_ELEMENTS,
            self::ONLY_DELETED,
        ];
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
}
