<?php

namespace AmoCRM\Filters;

use AmoCRM\Filters\Interfaces\HasPagesInterface;
use AmoCRM\Filters\Traits\PagesFilterTrait;

class TagFilter extends BaseEntityFilter implements HasPagesInterface
{
    use PagesFilterTrait;

    /**
     * @var array|null
     */
    private $ids = null;

    /**
     * @var string|null
     */
    private $search = null;

    /**
     * @var string|null
     */
    private $name = null;

    /**
     * @return null|array
     */
    public function getIds(): ?array
    {
        return $this->ids;
    }

    /**
     * @param array $ids
     * @return TagFilter
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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return TagFilter
     */
    public function setName(?string $name): self
    {
        if (!empty($name)) {
            $this->name = (string)$name;
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

        if (!is_null($this->getName())) {
            $filter['filter']['name'] = $this->getName();
        }

        $filter = $this->buildPagesFilter($filter);

        return $filter;
    }
}
