<?php

namespace AmoCRM\Filters;

class CatalogElementsFilter extends BaseEntityFilter
{
    /**
     * @var array|null
     */
    private $ids = null;

    /**
     * @var string|null
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
        $ids = array_map('intval', $ids);

        if (!empty($ids)) {
            $this->ids = $ids;
        }

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
     * @return CatalogElementsFilter
     */
    public function setQuery(?string $query): self
    {
        if (!empty($query)) {
            $this->query = (string)$query;
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
            $filter['term'] = $this->getQuery();
        }

        return $filter;
    }
}
