<?php

namespace AmoCRM\Models;

use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\Interfaces\TypeAwareInterface;
use AmoCRM\Models\Traits\RequestIdTrait;

class CatalogModel extends BaseApiModel implements TypeAwareInterface, HasIdInterface
{
    use RequestIdTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var null|string
     */
    protected $name;

    /**
     * @var null|int
     */
    protected $createdBy;

    /**
     * @var null|int
     */
    protected $updatedBy;

    /**
     * @var null|int
     */
    protected $createdAt;

    /**
     * @var null|int
     */
    protected $updatedAt;

    /**
     * @var null|int
     */
    protected $sort;

    /**
     * @var string|null
     */
    protected $catalogType;

    /**
     * @var bool|null
     */
    protected $canAddElements;

    /**
     * @var bool|null
     */
    protected $canShowInCards;

    /**
     * @var bool|null
     */
    protected $canBeDeleted;

    /**
     * @var bool|null
     */
    protected $canLinkMultiple;

    /**
     * @var string|null
     */
    protected $sdkWidgetCode;

    /**
     * @var int|null
     */
    protected $accountId;

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
     * @param null|string $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

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
     * @return null|bool
     */
    public function getCanAddElements(): ?bool
    {
        return $this->canAddElements;
    }

    /**
     * @param null|bool $flag
     *
     * @return self
     */
    public function setCanAddElements(?bool $flag): self
    {
        $this->canAddElements = $flag;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function getCanShowInCards(): ?bool
    {
        return $this->canShowInCards;
    }

    /**
     * @param null|bool $flag
     *
     * @return self
     */
    public function setCanBeDeleted(?bool $flag): self
    {
        $this->canBeDeleted = $flag;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function getCanBeDeleted(): ?bool
    {
        return $this->canBeDeleted;
    }

    /**
     * @param null|bool $flag
     *
     * @return self
     */
    public function setCanShowInCards(?bool $flag): self
    {
        $this->canShowInCards = $flag;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function getCanLinkMultiple(): ?bool
    {
        return $this->canLinkMultiple;
    }

    /**
     * @param null|bool $flag
     *
     * @return self
     */
    public function setCanLinkMultiple(?bool $flag): self
    {
        $this->canLinkMultiple = $flag;

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
     * @param null|int $sort
     *
     * @return self
     */
    public function setSort(?int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCatalogType(): ?string
    {
        return $this->catalogType;
    }

    /**
     * @param null|string $type
     *
     * @return self
     */
    public function setCatalogType(?string $type): self
    {
        $this->catalogType = $type;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSdkWidgetCode(): ?string
    {
        return $this->sdkWidgetCode;
    }

    /**
     * @param null|string $code
     *
     * @return self
     */
    public function setSdkWidgetCode(?string $code): self
    {
        $this->sdkWidgetCode = $code;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    /**
     * @param int|null $accountId
     *
     * @return CatalogModel
     */
    public function setAccountId(?int $accountId): CatalogModel
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return EntityTypesInterface::CATALOGS;
    }

    /**
     * @param array $catalog
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $catalog): self
    {
        if (empty($catalog['id'])) {
            throw new InvalidArgumentException('Catalog id is empty in ' . json_encode($catalog));
        }

        $catalogModel = new self();

        $catalogModel->setId((int)$catalog['id']);

        if (!empty($catalog['name'])) {
            $catalogModel->setName($catalog['name']);
        }
        if (array_key_exists('created_by', $catalog) && !is_null($catalog['created_by'])) {
            $catalogModel->setCreatedBy((int)$catalog['created_by']);
        }
        if (array_key_exists('updated_by', $catalog) && !is_null($catalog['updated_by'])) {
            $catalogModel->setUpdatedBy((int)$catalog['updated_by']);
        }
        if (!empty($catalog['created_at'])) {
            $catalogModel->setCreatedAt($catalog['created_at']);
        }
        if (!empty($catalog['updated_at'])) {
            $catalogModel->setUpdatedAt($catalog['updated_at']);
        }
        if (array_key_exists('sort', $catalog) && !is_null($catalog['sort'])) {
            $catalogModel->setSort((int)$catalog['sort']);
        }
        if (array_key_exists('type', $catalog) && !is_null($catalog['type'])) {
            $catalogModel->setCatalogType($catalog['type']);
        }
        if (array_key_exists('can_add_elements', $catalog) && !is_null($catalog['can_add_elements'])) {
            $catalogModel->setCanAddElements((bool)$catalog['can_add_elements']);
        }
        if (array_key_exists('can_link_multiple', $catalog) && !is_null($catalog['can_link_multiple'])) {
            $catalogModel->setCanLinkMultiple((bool)$catalog['can_link_multiple']);
        }
        if (array_key_exists('can_show_in_cards', $catalog) && !is_null($catalog['can_show_in_cards'])) {
            $catalogModel->setCanShowInCards((bool)$catalog['can_show_in_cards']);
        }
        if (array_key_exists('sdk_widget_code', $catalog) && !is_null($catalog['sdk_widget_code'])) {
            $catalogModel->setSdkWidgetCode($catalog['sdk_widget_code']);
        }
        if (array_key_exists('can_be_deleted', $catalog) && !is_null($catalog['can_be_deleted'])) {
            $catalogModel->setCanBeDeleted((bool)$catalog['can_be_deleted']);
        }
        if (!empty($catalog['account_id'])) {
            $catalogModel->setAccountId((int)$catalog['account_id']);
        }

        return $catalogModel;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'created_by' => $this->getCreatedBy(),
            'updated_by' => $this->getUpdatedBy(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'sort' => $this->getSort(),
            'type' => $this->getCatalogType(),
            'can_add_elements' => $this->getCanAddElements(),
            'can_show_in_cards' => $this->getCanShowInCards(),
            'can_link_multiple' => $this->getCanLinkMultiple(),
            'can_be_deleted' => $this->getCanBeDeleted(),
            'sdk_widget_code' => $this->getSdkWidgetCode(),
            'account_id' => $this->getAccountId(),
        ];
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

        if (!is_null($this->getCreatedBy())) {
            $result['created_by'] = $this->getCreatedBy();
        }

        if (!is_null($this->getUpdatedBy())) {
            $result['updated_by'] = $this->getUpdatedBy();
        }

        if (!is_null($this->getCreatedAt())) {
            $result['created_at'] = $this->getCreatedAt();
        }

        if (!is_null($this->getCanAddElements())) {
            $result['can_add_elements'] = $this->getCanAddElements();
        }

        if (!is_null($this->getCanShowInCards())) {
            $result['can_show_in_cards'] = $this->getCanShowInCards();
        }

        if (!is_null($this->getCanLinkMultiple())) {
            $result['can_link_multiple'] = $this->getCanLinkMultiple();
        }

        if (!is_null($this->getSort())) {
            $result['sort'] = $this->getSort();
        }

        if (is_null($this->getId()) && !is_null($this->getCatalogType())) {
            $result['type'] = $this->getCatalogType();
        }

        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId);
        }

        $result['request_id'] = $this->getRequestId();

        return $result;
    }

    /**
     * @return array
     */
    public static function getAvailableWith(): array
    {
        return [];
    }
}
