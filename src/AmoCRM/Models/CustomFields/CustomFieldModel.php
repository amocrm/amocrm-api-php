<?php

namespace AmoCRM\Models\CustomFields;

use AmoCRM\Collections\CustomFields\CustomFieldRequiredStatusesCollection;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\Traits\RequestIdTrait;
use InvalidArgumentException;

use function array_key_exists;
use function is_numeric;

/**
 * Class CustomFieldModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class CustomFieldModel extends BaseApiModel implements HasIdInterface
{
    use RequestIdTrait;

    public const TYPE_TEXT = 'text';
    public const TYPE_NUMERIC = 'numeric';
    public const TYPE_CHECKBOX = 'checkbox';
    public const TYPE_SELECT = 'select';
    public const TYPE_MULTISELECT = 'multiselect';
    public const TYPE_MULTITEXT = 'multitext';
    public const TYPE_DATE = 'date';
    public const TYPE_URL = 'url';
    public const TYPE_TEXTAREA = 'textarea';
    public const TYPE_RADIOBUTTON = 'radiobutton';
    public const TYPE_STREET_ADDRESS = 'streetaddress';
    public const TYPE_SMART_ADDRESS = 'smart_address';
    public const TYPE_BIRTHDAY = 'birthday';
    public const TYPE_LEGAL_ENTITY = 'legal_entity';
    public const TYPE_DATE_TIME = 'date_time';
    public const TYPE_ITEMS = 'items';
    public const TYPE_CATEGORY = 'category';
    public const TYPE_PRICE = 'price';
    public const TYPE_TRACKING_DATA = 'tracking_data';
    public const TYPE_LINKED_ENTITY = 'linked_entity';
    /** @deprecated */
    public const TYPE_ORG_LEGAL_NAME = 'org_legal_name';
    /** @var string Тип денежного поля */
    public const TYPE_MONETARY = 'monetary';
    /** @var string Тип связанного списка */
    public const TYPE_CHAINED_LIST = 'chained_list';
    /** @var string Поле "Файл" */
    public const TYPE_FILE = 'file';

    protected const CAN_HAVE_REQUIRED_STATUSES = [
        EntityTypesInterface::LEADS,
        EntityTypesInterface::CONTACTS,
        EntityTypesInterface::COMPANIES,
    ];

    protected const CAN_HAVE_SEARCH_IN = [
        self::TYPE_LINKED_ENTITY,
    ];

    protected const CAN_BE_API_ONLY = [
        EntityTypesInterface::LEADS,
        EntityTypesInterface::CONTACTS,
        EntityTypesInterface::COMPANIES,
        EntityTypesInterface::CUSTOMERS,
    ];

    protected const CAN_BE_IS_DELETABLE = [
        EntityTypesInterface::CATALOGS,
    ];

    protected const CAN_BE_IS_VISIBLE = [
        EntityTypesInterface::CATALOGS,
    ];

    protected const CAN_BE_IS_REQUIRED = [
        EntityTypesInterface::CATALOGS,
    ];

    protected const CAN_BE_SEARCHED_IN = [
        EntityTypesInterface::CONTACTS,
        EntityTypesInterface::COMPANIES,
        EntityTypesInterface::CONTACTS_AND_COMPANIES,
    ];

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var null|string
     */
    protected $groupId;

    /**
     * @var int|null
     */
    protected $sort;

    /**
     * All without segments and catalog
     * @var null|bool
     */
    protected $isApiOnly;

    /**
     * Catalog only
     * @var null|bool
     */
    protected $isDeletable;

    /**
     * Catalog only
     * @var null|bool
     */
    protected $isVisible;

    /**
     * Catalog only
     * @var null|bool
     */
    protected $isRequired;

    /**
     * Catalog only
     * @var null|int
     */
    protected $catalogId;

    /**
     * Company|Contact only
     * @var bool|int
     */
    protected $isPredefined;

    /**
     * @var null|CustomFieldRequiredStatusesCollection
     */
    protected $requiredStatuses;

    /**
     * @var string|null
     */
    protected $code;

    /**
     * @var int
     */
    protected $accountId;

    /**
     * @var string
     */
    protected $entityType;

    /**
     * @var null|string
     */
    protected $trackingCallback;

    /**
     * Используется в интерфейсе счета для предложения результатов быстрого поиска
     * Указывается сущность (contacts, companies или ID каталога), по которой делаем быстрый поиск из карточки счета
     * @var null|string
     */
    protected $searchIn;

    /**
     * @param array $customField
     *
     * @return CustomFieldModel
     */
    public static function fromArray(array $customField): CustomFieldModel
    {
        if (empty($customField['id'])) {
            throw new InvalidArgumentException('Custom field id is empty in ' . json_encode($customField));
        }

        $customFieldModel = new static();

        $customFieldModel
            ->setId($customField['id'])
            ->setName($customField['name'])
            ->setSort($customField['sort'])
            ->setCode(empty($customField['code']) ? null : $customField['code'])
            ->setEntityType($customField['entity_type'])
            ->setAccountId($customField['account_id']);

        if (!empty($customField['group_id'])) {
            $customFieldModel->setGroupId($customField['group_id']);
        }

        if (!empty($customField['required_statuses'])) {
            $customFieldModel->setRequiredStatuses(
                CustomFieldRequiredStatusesCollection::fromArray($customField['required_statuses'])
            );
        }

        if (array_key_exists('is_api_only', $customField)) {
            $customFieldModel->setIsApiOnly($customField['is_api_only']);
        }

        if (array_key_exists('is_deletable', $customField)) {
            $customFieldModel->setIsDeletable($customField['is_deletable']);
        }

        if (array_key_exists('is_required', $customField)) {
            $customFieldModel->setIsRequired($customField['is_required']);
        }

        if (array_key_exists('is_visible', $customField)) {
            $customFieldModel->setIsVisible($customField['is_visible']);
        }

        if (array_key_exists('catalog_id', $customField)) {
            $customFieldModel->setCatalogId($customField['catalog_id']);
        }

        if (array_key_exists('is_predefined', $customField)) {
            $customFieldModel->setIsPredefined($customField['is_predefined']);
        }

        if (array_key_exists('tracking_callback', $customField)) {
            $customFieldModel->setTrackingCallback($customField['tracking_callback']);
        }

        if (array_key_exists('search_in', $customField)) {
            $customFieldModel->setSearchIn($customField['search_in']);
        }

        return $customFieldModel;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'sort' => $this->getSort(),
            'type' => $this->getType(),
            'is_api_only' => $this->getIsApiOnly(),
            'code' => $this->getCode(),
            'group_id' => $this->getGroupId(),
            'entity_type' => $this->getEntityType(),
            'required_statuses' => $this->getRequiredStatuses(),
            'tracking_callback' => $this->getTrackingCallback(),
            'search_in' => $this->getSearchIn(),
            'account_id' => $this->getAccountId(),
        ];
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
     * @return CustomFieldModel
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return CustomFieldModel
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getSort(): ?int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     * @return CustomFieldModel
     */
    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = [
            'type' => $this->getType(),
        ];

        if (!is_null($this->getId())) {
            $result['id'] = $this->getId();
        }

        if (!is_null($this->getName())) {
            $result['name'] = $this->getName();
        }

        if (!is_null($this->getCode())) {
            $result['code'] = $this->getCode();
        }

        if (!is_null($this->getSort())) {
            $result['sort'] = $this->getSort();
        }

        if (!is_null($this->getGroupId())) {
            $result['group_id'] = $this->getGroupId();
        }

        if (
            !is_null($this->getIsApiOnly())
            && in_array($this->getEntityType(), self::CAN_BE_API_ONLY, true)
        ) {
            $result['is_api_only'] = $this->getIsApiOnly();
        }

        if (
            !is_null($this->getRequiredStatuses())
            && in_array($this->getEntityType(), self::CAN_HAVE_REQUIRED_STATUSES, true)
        ) {
            $result['required_statuses'] = $this->getRequiredStatuses();
        }

        if (
            !is_null($this->getIsDeletable())
            && in_array($this->getEntityType(), self::CAN_BE_IS_DELETABLE, true)
        ) {
            $result['is_deletable'] = $this->getIsDeletable();
        }

        if (
            !is_null($this->getIsRequired())
            && in_array($this->getEntityType(), self::CAN_BE_IS_REQUIRED, true)
        ) {
            $result['is_required'] = $this->getIsRequired();
        }

        if (
            !is_null($this->getIsVisible())
            && in_array($this->getEntityType(), self::CAN_BE_IS_VISIBLE, true)
        ) {
            $result['is_visible'] = $this->getIsVisible();
        }

        if (
            !is_null($this->getSearchIn())
            && (
                in_array($this->getSearchIn(), self::CAN_BE_SEARCHED_IN, true)
                || is_numeric($this->getSearchIn())
            )
            && in_array($this->getType(), self::CAN_HAVE_SEARCH_IN, true)
        ) {
            $result['search_in'] = $this->getSearchIn();
        }

        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId);
        }

        $result['request_id'] = $this->getRequestId();

        return $result;
    }

    /**
     * @return null|string
     */
    public function getGroupId(): ?string
    {
        return $this->groupId;
    }

    /**
     * @param null|string $groupId
     * @return CustomFieldModel
     */
    public function setGroupId(?string $groupId): CustomFieldModel
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return '';
    }

    /**
     * @return null|bool
     */
    public function getIsApiOnly(): ?bool
    {
        return $this->isApiOnly;
    }

    /**
     * @param bool $isApiOnly
     * @return CustomFieldModel
     */
    public function setIsApiOnly(bool $isApiOnly): CustomFieldModel
    {
        if ($isApiOnly) {
            $this->setRequiredStatuses(null);
        }
        $this->isApiOnly = $isApiOnly;

        return $this;
    }

    /**
     * @return null|CustomFieldRequiredStatusesCollection
     */
    public function getRequiredStatuses(): ?CustomFieldRequiredStatusesCollection
    {
        return $this->requiredStatuses;
    }

    /**
     * @param null|CustomFieldRequiredStatusesCollection $requiredStatuses
     * @return CustomFieldModel
     */
    public function setRequiredStatuses(?CustomFieldRequiredStatusesCollection $requiredStatuses): CustomFieldModel
    {
        $this->requiredStatuses = $requiredStatuses;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     *
     * @return CustomFieldModel
     */
    public function setCode(?string $code): CustomFieldModel
    {
        $this->code = $code;

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
     * @param int $accountId
     *
     * @return CustomFieldModel
     */
    public function setAccountId(int $accountId): CustomFieldModel
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityType(): string
    {
        return $this->entityType;
    }

    /**
     * @param string $entityType
     *
     * @return CustomFieldModel
     */
    public function setEntityType(string $entityType): CustomFieldModel
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsDeletable(): ?bool
    {
        return $this->isDeletable;
    }

    /**
     * @param bool|null $isDeletable
     *
     * @return CustomFieldModel
     */
    public function setIsDeletable(?bool $isDeletable): CustomFieldModel
    {
        $this->isDeletable = $isDeletable;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    /**
     * @param bool|null $isVisible
     *
     * @return CustomFieldModel
     */
    public function setIsVisible(?bool $isVisible): CustomFieldModel
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsRequired(): ?bool
    {
        return $this->isRequired;
    }

    /**
     * @param bool|null $isRequired
     *
     * @return CustomFieldModel
     */
    public function setIsRequired(?bool $isRequired): CustomFieldModel
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCatalogId(): ?int
    {
        return $this->catalogId;
    }

    /**
     * @param int|null $catalogId
     *
     * @return CustomFieldModel
     */
    public function setCatalogId(?int $catalogId): CustomFieldModel
    {
        $this->catalogId = $catalogId;

        return $this;
    }

    /**
     * @return bool|int
     */
    public function getIsPredefined()
    {
        return $this->isPredefined;
    }

    /**
     * @param bool|int $isPredefined
     *
     * @return CustomFieldModel
     */
    public function setIsPredefined($isPredefined)
    {
        $this->isPredefined = $isPredefined;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTrackingCallback(): ?string
    {
        return $this->trackingCallback;
    }

    /**
     * @param string|null $trackingCallback
     *
     * @return CustomFieldModel
     */
    public function setTrackingCallback(?string $trackingCallback): CustomFieldModel
    {
        $this->trackingCallback = $trackingCallback;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSearchIn(): ?string
    {
        return $this->searchIn;
    }

    /**
     * @param string|null $searchIn
     *
     * @return CustomFieldModel
     */
    public function setSearchIn(?string $searchIn): CustomFieldModel
    {
        $this->searchIn = $searchIn;

        return $this;
    }
}
