<?php

namespace AmoCRM\Filters;

use AmoCRM\Filters\Interfaces\HasPagesInterface;
use AmoCRM\Filters\Traits\PagesFilterTrait;

class NoteFilter extends BaseEntityFilter implements HasPagesInterface
{
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
        //todo validate only types
        if (!empty($types)) {
            $this->noteTypes = $types;
        }

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

        $filter = $this->buildPagesFilter($filter);

        return $filter;
    }
}
