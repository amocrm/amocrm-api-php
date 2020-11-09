<?php

namespace AmoCRM\Filters;

use AmoCRM\Filters\Interfaces\HasOrderInterface;
use AmoCRM\Filters\Interfaces\HasPagesInterface;
use AmoCRM\Filters\Traits\ArrayOrNumericFilterTrait;
use AmoCRM\Filters\Traits\OrderTrait;
use AmoCRM\Filters\Traits\PagesFilterTrait;
use AmoCRM\Filters\Traits\IntOrIntRangeFilterTrait;

class NotesFilter extends BaseEntityFilter implements HasPagesInterface, HasOrderInterface
{
    use OrderTrait;
    use PagesFilterTrait;
    use ArrayOrNumericFilterTrait;
    use IntOrIntRangeFilterTrait;

    /**
     * @var array|null
     */
    private $ids = null;

    /**
     * @var array|null
     */
    private $entityIds = null;

    /**
     * @var array
     */
    private $noteTypes = [];

    /**
     * @var int|array|null
     */
    private $updatedAt = null;

    /**
     * @return null|array
     */
    public function getIds(): ?array
    {
        return $this->ids;
    }

    /**
     * @param array $ids
     *
     * @return NotesFilter
     */
    public function setIds(array $ids): self
    {
        $this->ids = $this->parseArrayOrNumberFilter($ids);

        return $this;
    }

    /**
     * @return array
     */
    public function getNoteTypes(): array
    {
        return $this->noteTypes;
    }

    /**
     * @param array $types
     *
     * @return NotesFilter
     */
    public function setNoteTypes(array $types): self
    {
        if (!empty($types)) {
            $this->noteTypes = $types;
        }

        return $this;
    }

    /**
     * @param BaseRangeFilter|int|null $updatedAt
     *
     * @return NotesFilter
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $this->parseIntOrIntRangeFilter($updatedAt);

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return array|null
     */
    public function getEntityIds(): ?array
    {
        return $this->entityIds;
    }

    /**
     * @param array|null $entityIds
     *
     * @return NotesFilter
     */
    public function setEntityIds(?array $entityIds): NotesFilter
    {
        $this->entityIds = $entityIds;

        return $this;
    }

    /**
     * @return array
     */
    public function buildFilter(): array
    {
        $filter = [];

        if (!is_null($this->getIds())) {
            $filter['filter']['id'] = $this->getIds();
        }

        if (!empty($this->getNoteTypes())) {
            $filter['filter']['note_type'] = $this->getNoteTypes();
        }

        if (!empty($this->getUpdatedAt())) {
            $filter['filter']['updated_at'] = $this->getUpdatedAt();
        }

        if (!empty($this->getEntityIds())) {
            $filter['filter']['entity_id'] = $this->getEntityIds();
        }

        if (!is_null($this->getOrder())) {
            $filter['order'] = $this->getOrder();
        }

        $filter = $this->buildPagesFilter($filter);

        return $filter;
    }
}
