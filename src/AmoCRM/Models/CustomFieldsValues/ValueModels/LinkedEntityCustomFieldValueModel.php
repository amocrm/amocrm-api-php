<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

/**
 * Class LinkedEntityCustomFieldValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 */
class LinkedEntityCustomFieldValueModel extends BaseArrayCustomFieldValueModel
{
    public const NAME = 'name';
    public const ENTITY_ID = 'entity_id';
    public const ENTITY_TYPE = 'entity_type';
    public const CATALOG_ID = 'catalog_id';

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var int|null
     */
    protected $entityId;

    /**
     * @var string|null
     */
    protected $entityType;

    /**
     * @var int|null
     */
    protected $catalogId;

    /**
     * @param array|null $value
     *
     * @return LinkedEntityCustomFieldValueModel
     */
    public static function fromArray($value): BaseCustomFieldValueModel
    {
        $model = new static();

        $model
            ->setName($value['value'][self::NAME] ?? null)
            ->setEntityId($value['value'][self::ENTITY_ID] ?? null)
            ->setEntityType($value['value'][self::ENTITY_TYPE] ?? null)
            ->setCatalogId($value['value'][self::CATALOG_ID] ?? null);

        return $model;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return LinkedEntityCustomFieldValueModel
     */
    public function setName(?string $name): LinkedEntityCustomFieldValueModel
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    /**
     * @param int|null $entityId
     *
     * @return LinkedEntityCustomFieldValueModel
     */
    public function setEntityId(?int $entityId): LinkedEntityCustomFieldValueModel
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEntityType(): ?string
    {
        return $this->entityType;
    }

    /**
     * @param string|null $entityType
     *
     * @return LinkedEntityCustomFieldValueModel
     */
    public function setEntityType(?string $entityType): LinkedEntityCustomFieldValueModel
    {
        $this->entityType = $entityType;

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
     * @return LinkedEntityCustomFieldValueModel
     */
    public function setCatalogId(?int $catalogId): LinkedEntityCustomFieldValueModel
    {
        $this->catalogId = $catalogId;

        return $this;
    }


    public function toArray(): array
    {
        return [
            self::NAME => $this->getName(),
            self::ENTITY_ID => $this->getEntityId(),
            self::ENTITY_TYPE => $this->getEntityType(),
            self::CATALOG_ID => $this->getCatalogId(),
        ];
    }

    /**
     * @return array
     */
    public function getValue()
    {
        return $this->toArray();
    }


    public function toApi(string $requestId = null): array
    {
        return [
            'value' => $this->getValue(),
        ];
    }
}
