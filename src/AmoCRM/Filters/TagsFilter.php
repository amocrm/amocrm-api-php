<?php

namespace AmoCRM\Filters;

use AmoCRM\Filters\Interfaces\HasPagesInterface;
use AmoCRM\Filters\Traits\ArrayOrNumericFilterTrait;
use AmoCRM\Filters\Traits\PagesFilterTrait;

class TagsFilter extends BaseEntityFilter implements HasPagesInterface
{
    use PagesFilterTrait;
    use ArrayOrNumericFilterTrait;

    /**
     * @var array|null
     */
    private $ids = null;

    /**
     * @var string|null
     */
    private $query = null;

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
     *
     * @return TagsFilter
     */
    public function setIds(array $ids): self
    {
        $this->ids = $this->parseArrayOrNumberFilter($ids);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * @param string|null $query
     *
     * @return TagsFilter
     */
    public function setQuery(?string $query): self
    {
        if (!empty($query)) {
            $this->query = (string)$query;
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
     *
     * @return TagsFilter
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

        if (!is_null($this->getQuery())) {
            $filter['query'] = $this->getQuery();
        }

        if (!is_null($this->getName())) {
            $filter['filter']['name'] = $this->getName();
        }

        $filter = $this->buildPagesFilter($filter);

        return $filter;
    }
}
