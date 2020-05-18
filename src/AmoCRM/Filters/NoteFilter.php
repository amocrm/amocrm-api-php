<?php

namespace AmoCRM\Filters;

use AmoCRM\Filters\Interfaces\HasPagesInterface;
use AmoCRM\Filters\Traits\OrderTrait;
use AmoCRM\Filters\Traits\PagesFilterTrait;

class NoteFilter extends BaseEntityFilter implements HasPagesInterface
{
    use OrderTrait;
    use PagesFilterTrait;

    /**
     * @var array|null
     */
    private $ids = null;

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
     * @return NoteFilter
     */
    public function setIds(array $ids): self
    {
        $ids = array_map('intval', $ids);

        if (!empty($ids)) {
            $this->ids = $ids;
        }

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
     * @return NoteFilter
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
     * @return NoteFilter
     */
    public function setUpdatedAt($updatedAt)
    {
        if ($updatedAt instanceof BaseRangeFilter) {
            $updatedAt = $updatedAt->toFilter();
        }

        $this->updatedAt = $updatedAt;

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

        if (!is_null($this->getOrder())) {
            $filter['order'] = $this->getOrder();
        }

        $filter = $this->buildPagesFilter($filter);

        return $filter;
    }
}
