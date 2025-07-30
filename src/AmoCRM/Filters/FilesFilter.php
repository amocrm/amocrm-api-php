<?php

declare(strict_types=1);

namespace AmoCRM\Filters;

use AmoCRM\Filters\Interfaces\HasPagesInterface;
use AmoCRM\Filters\Traits\ArrayOrNumericFilterTrait;
use AmoCRM\Filters\Traits\ArrayOrStringFilterTrait;
use AmoCRM\Filters\Traits\IntOrIntRangeFilterTrait;
use AmoCRM\Filters\Traits\PagesFilterTrait;

use function implode;
use function is_null;

class FilesFilter extends BaseEntityFilter implements HasPagesInterface
{
    use ArrayOrNumericFilterTrait;
    use ArrayOrStringFilterTrait;
    use IntOrIntRangeFilterTrait;
    use PagesFilterTrait;

    /** @var array|null */
    private $uuid;

    /** @var string|null */
    private $name;

    /** @var array|null */
    private $extensions;

    /** @var string|null */
    private $term;

    /** @var int|null */
    private $sourceId;

    /** @var bool */
    private $deleted = false;

    /** @var array|null */
    private $size;

    /** @var array|null */
    private $date;

    /** @var array|null */
    private $createdBy;

    /** @var array|null */
    private $updatedBy;

    public function getUuid(): ?array
    {
        return $this->uuid;
    }

    /**
     * @param array|string|null $uuid
     * @return FilesFilter
     */
    public function setUuid($uuid): self
    {
        $this->uuid = $this->parseArrayOrStringFilter($uuid);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExtensions(): ?array
    {
        return $this->extensions;
    }

    /**
     * @param array|string|null $extensions
     * @return FilesFilter
     */
    public function setExtensions($extensions): self
    {
        $this->extensions = $this->parseArrayOrStringFilter($extensions);

        return $this;
    }

    public function getTerm(): ?string
    {
        return $this->term;
    }

    public function setTerm(?string $term): self
    {
        $this->term = $term;

        return $this;
    }

    public function getSourceId(): ?int
    {
        return $this->sourceId;
    }

    public function setSourceId(?int $sourceId): self
    {
        $this->sourceId = $sourceId;

        return $this;
    }

    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getSize(): ?array
    {
        return $this->size;
    }

    public function setSize(?BaseRangeFilter $sizeRange = null, ?int $sizeUnit = null): self
    {
        $size = null;

        if (!is_null($sizeRange)) {
            $size = $this->parseIntOrIntRangeFilter($sizeRange);
        }

        if (!is_null($sizeUnit)) {
            $size['unit'] = $sizeUnit;
        }

        $this->size = $size;

        return $this;
    }

    public function getDate(): ?array
    {
        return $this->date;
    }

    public function setDate(
        ?BaseRangeFilter $dateRange = null,
        ?string $dateType = null,
        ?string $datePreset = null
    ): self {
        $date = null;

        if (!is_null($dateRange)) {
            $date = $this->parseIntOrIntRangeFilter($dateRange);
        }

        if (!is_null($dateType)) {
            $date['type'] = $dateType;
        }

        if (!is_null($datePreset)) {
            $date['date_preset'] = $datePreset;
        }

        $this->date = $date;

        return $this;
    }

    public function getCreatedBy(): ?array
    {
        return $this->createdBy;
    }

    /**
     * @param array|int|null $createdBy
     * @return FilesFilter
     */
    public function setCreatedBy($createdBy): self
    {
        $this->createdBy = $this->parseArrayOrNumberFilter($createdBy);

        return $this;
    }

    public function getUpdatedBy(): ?array
    {
        return $this->updatedBy;
    }

    /**
     * @param array|int|null $updatedBy
     * @return FilesFilter
     */
    public function setUpdatedBy($updatedBy): self
    {
        $this->updatedBy = $this->parseArrayOrNumberFilter($updatedBy);

        return $this;
    }

    public function buildFilter(): array
    {
        $filter = [];

        if (!is_null($this->getUuid())) {
            $filter['filter']['uuid'] = implode(',', $this->getUuid());
        }

        if (!is_null($this->getName())) {
            $filter['filter']['name'] = $this->getName();
        }

        if (!is_null($this->getExtensions())) {
            $filter['filter']['extensions'] = $this->getExtensions();
        }

        if (!is_null($this->getTerm())) {
            $filter['filter']['term'] = $this->getTerm();
        }

        if (!is_null($this->getSourceId())) {
            $filter['filter']['source_id'] = $this->getSourceId();
        }

        if ($this->getDeleted()) {
            $filter['filter']['deleted'] = $this->getDeleted();
        }

        if (!is_null($this->getSize())) {
            $filter['filter']['size'] = $this->getSize();
        }

        if (!is_null($this->getDate())) {
            $filter['filter']['date'] = $this->getDate();
        }

        if (!is_null($this->getCreatedBy())) {
            $filter['filter']['created_by'] = $this->getCreatedBy();
        }

        if (!is_null($this->getUpdatedBy())) {
            $filter['filter']['updated_by'] = $this->getUpdatedBy();
        }

        return $this->buildPagesFilter($filter);
    }
}
