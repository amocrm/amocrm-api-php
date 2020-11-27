<?php

namespace AmoCRM\Filters;

use AmoCRM\Filters\Interfaces\HasPagesInterface;
use AmoCRM\Filters\Traits\ArrayOrNumericFilterTrait;
use AmoCRM\Filters\Traits\PagesFilterTrait;

class CatalogElementsFilter extends BaseEntityFilter implements HasPagesInterface
{
    //todo support order and other fields
    use PagesFilterTrait;
    use ArrayOrNumericFilterTrait;

    /**
     * @var array|null
     */
    private $ids = null;

    /**
     * @var string|array|null
     */
    private $query = null;

    /**
     * @return null|array
     */
    public function getIds(): ?array
    {
        return $this->ids;
    }

    /**
     * @param array $ids
     * @return CatalogElementsFilter
     */
    public function setIds(array $ids): self
    {
        $this->ids = $this->parseArrayOrNumberFilter($ids);

        return $this;
    }

    /**
     * @return string|array|null
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string|array|null $query
     * @return CatalogElementsFilter
     */
    public function setQuery($query): self
    {
        if (!empty($query) && (is_array($query) || is_string($query))) {
            $this->query = $query;
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
            $filter['id'] = $this->getIds();
        }

        if (!is_null($this->getQuery())) {
            $filter['query'] = $this->getQuery();
        }

        $filter = $this->buildPagesFilter($filter);

        return $filter;
    }
}
