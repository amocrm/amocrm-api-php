<?php

namespace AmoCRM\Filters;

class TagFilter extends BaseEntityFilter
{
    /**
     * @var array|null
     */
    private $ids = null;

    /**
     * @var string|null
     */
    private $search = null;

    /**
     * @return null|array
     */
    public function getIds(): ?array
    {
        return $this->ids;
    }

    /**
     * @param array $ids
     * @return ContactFilter
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
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * @param string|null $query
     * @return TagFilter
     */
    public function setSearch(?string $query): self
    {
        if (!empty($query)) {
            $this->search = (string)$query;
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

        if (!is_null($this->getSearch())) {
            $filter['search'] = $this->getSearch();
        }

        return $filter;
    }
}
