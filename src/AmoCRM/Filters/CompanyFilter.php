<?php

namespace AmoCRM\Filters;

class CompanyFilter extends BaseEntityFilter
{
    /**
     * @var array|null
     */
    private $ids = null;

    /**
     * @var array|null
     */
    private $responsibleUserIds = null;

    /**
     * @var string|null
     */
    private $query = null;

    /**
     * @return array
     */
    public function getIds(): array
    {
        return $this->ids;
    }

    /**
     * @param array $ids
     * @return CompanyFilter
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
     * @return CompanyFilter
     */
    public function setQuery(?string $query): self
    {
        if (!empty($query)) {
            $this->query = (string)$query;
        }

        return $this;
    }

    /**
     * @return array|null
     */
    public function getResponsibleUserIds(): ?array
    {
        return $this->responsibleUserIds;
    }

    /**
     * @param array|null $userIds
     * @return CompanyFilter
     */
    public function setResponsibleUserIds(?array $userIds): self
    {
        $userIds = array_map('intval', $userIds);

        if (!empty($userIds)) {
            $this->responsibleUserIds = $userIds;
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

        if (!is_null($this->getResponsibleUserIds())) {
            $filter['responsible_user_id'] = $this->getResponsibleUserIds();
        }

        return $filter;
    }
}
