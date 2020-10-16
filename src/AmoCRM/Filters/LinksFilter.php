<?php

namespace AmoCRM\Filters;

class LinksFilter extends BaseEntityFilter
{
    /**
     * @var int|null
     */
    private $toEntityId = null;

    /**
     * @var string|null
     */
    private $toEntityType = null;

    /**
     * @var int|null
     */
    private $toCatalogId = null;

    /**
     * @return int|null
     */
    public function getToEntityId(): ?int
    {
        return $this->toEntityId;
    }

    /**
     * @param int|null $toEntityId
     *
     * @return LinksFilter
     */
    public function setToEntityId(?int $toEntityId): LinksFilter
    {
        $this->toEntityId = $toEntityId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getToEntityType(): ?string
    {
        return $this->toEntityType;
    }

    /**
     * @param string|null $toEntityType
     *
     * @return LinksFilter
     */
    public function setToEntityType(?string $toEntityType): LinksFilter
    {
        $this->toEntityType = $toEntityType;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getToCatalogId(): ?int
    {
        return $this->toCatalogId;
    }

    /**
     * @param int|null $toCatalogId
     *
     * @return LinksFilter
     */
    public function setToCatalogId(?int $toCatalogId): LinksFilter
    {
        $this->toCatalogId = $toCatalogId;

        return $this;
    }

    /**
     * @return array
     */
    public function buildFilter(): array
    {
        $filter = [];

        if (!is_null($this->getToEntityId())) {
            $filter['filter']['to_entity_id'] = $this->getToEntityId();
        }

        if (!empty($this->getToEntityType())) {
            $filter['filter']['to_entity_type'] = $this->getToEntityType();
        }

        if (!empty($this->getToCatalogId())) {
            $filter['filter']['to_catalog_id'] = $this->getToCatalogId();
        }

        return $filter;
    }
}
