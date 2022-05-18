<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

/**
 * @since Release Spring 2022
 */
class ChainedListCustomFieldValueModel extends BaseCustomFieldValueModel
{
    /** @var int */
    private $catalogId;
    /** @var int */
    private $catalogElementId;

    /**
     * @param int $catalogId
     */
    public function setCatalogId(int $catalogId): void
    {
        $this->catalogId = $catalogId;
    }

    /**
     * @return int
     */
    public function getCatalogId(): int
    {
        return $this->catalogId;
    }

    /**
     * @param int $catalogElementId
     */
    public function setCatalogElementId(int $catalogElementId): void
    {
        $this->catalogElementId = $catalogElementId;
    }

    /**
     * @return int
     */
    public function getCatalogElementId(): int
    {
        return $this->catalogElementId;
    }

    public function toArray(): array
    {
        return [
            'catalog_id' => $this->getCatalogId(),
            'catalog_element_id' => $this->getCatalogElementId(),
        ];
    }

    public function toApi(string $requestId = null): array
    {
        return [
            'catalog_id' => $this->getCatalogId(),
            'catalog_element_id' => $this->getCatalogElementId(),
        ];
    }

    public static function fromArray($value): BaseCustomFieldValueModel
    {
        $model = new static();
        $model->setCatalogId((int) ($value['catalog_id'] ?? 0));
        $model->setCatalogElementId((int) ($value['catalog_element_id'] ?? 0));

        return $model;
    }
}
