<?php

namespace AmoCRM\Filters;

use AmoCRM\Filters\Interfaces\HasPagesInterface;
use AmoCRM\Filters\Traits\OrderTrait;
use AmoCRM\Filters\Traits\PagesFilterTrait;

class CustomersFilter extends BaseEntityFilter implements HasPagesInterface
{
    use OrderTrait;
    use PagesFilterTrait;

    /**
     * @var array|int|null
     */
    private $ids = null;

    /**
     * @var array|string|null
     */
    private $names = null;

    /**
     * @var null|array|int
     */
    private $createdBy = null;

    /**
     * @var null|array|int
     */
    private $updatedBy = null;

    /**
     * @var int|array|null
     */
    private $responsibleUserId = null;

    /**
     * @var null|array|int
     */
    private $createdAt = null;

    /**
     * @var null|array|int
     */
    private $updatedAt = null;

    /**
     * @var null|array|int
     */
    private $closedAt = null;

    /**
     * @var int|null|array
     */
    private $closestTaskAt = null;

    /**
     * @var null|array
     */
    private $customFieldsValues = null;

    /**
     * @var string|null
     */
    private $query = null;

    //todo add customers statuses, segments, next_date, next_price

    /**
     * @return array|int|null
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * @param array|int|null $ids
     *
     * @return CustomersFilter
     */
    public function setIds($ids)
    {
        if (is_numeric($ids)) {
            $ids = [$ids];
        }

        $this->ids = array_map('intval', $ids);

        return $this;
    }

    /**
     * @return array|string|null
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * @param array|string|null $names
     *
     * @return CustomersFilter
     */
    public function setNames($names)
    {
        if (is_string($names)) {
            $names = [$names];
        }

        $this->names = array_map('strval', $names);

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param array|int|null $createdBy
     *
     * @return CustomersFilter
     */
    public function setCreatedBy($createdBy)
    {
        if (is_numeric($createdBy)) {
            $createdBy = [$createdBy];
        }

        $this->createdBy = array_map('intval', $createdBy);

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param array|int|null $updatedBy
     *
     * @return CustomersFilter
     */
    public function setUpdatedBy($updatedBy)
    {
        if (is_numeric($updatedBy)) {
            $updatedBy = [$updatedBy];
        }

        $this->updatedBy = array_map('intval', $updatedBy);

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getResponsibleUserId()
    {
        return $this->responsibleUserId;
    }

    /**
     * @param array|int|null $responsibleUserId
     *
     * @return CustomersFilter
     */
    public function setResponsibleUserId($responsibleUserId)
    {
        if (is_numeric($responsibleUserId)) {
            $responsibleUserId = [$responsibleUserId];
        }

        $this->responsibleUserId = array_map('intval', $responsibleUserId);

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param BaseRangeFilter|int|null $createdAt
     *
     * @return CustomersFilter
     */
    public function setCreatedAt($createdAt)
    {
        if ($createdAt instanceof BaseRangeFilter) {
            $createdAt = $createdAt->toFilter();
        }

        $this->createdAt = $createdAt;

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
     * @param BaseRangeFilter|int|null $updatedAt
     *
     * @return CustomersFilter
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
    public function getClosedAt()
    {
        return $this->closedAt;
    }

    /**
     * @param BaseRangeFilter|int|null $closedAt
     *
     * @return CustomersFilter
     */
    public function setClosedAt($closedAt)
    {
        if ($closedAt instanceof BaseRangeFilter) {
            $closedAt = $closedAt->toFilter();
        }

        $this->closedAt = $closedAt;

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getClosestTaskAt()
    {
        return $this->closestTaskAt;
    }

    /**
     * @param BaseRangeFilter|int|null $closestTaskAt
     *
     * @return CustomersFilter
     */
    public function setClosestTaskAt($closestTaskAt)
    {
        if ($closestTaskAt instanceof BaseRangeFilter) {
            $closestTaskAt = $closestTaskAt->toFilter();
        }

        $this->closestTaskAt = $closestTaskAt;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getCustomFieldsValues(): ?array
    {
        return $this->customFieldsValues;
    }

    /**
     * @param array|null $customFieldsValues
     *
     * @return CustomersFilter
     */
    public function setCustomFieldsValues(?array $customFieldsValues): CustomersFilter
    {
        $cfFilter = [];

        foreach ($customFieldsValues as $fieldId => $customFieldsValue) {
            if ($customFieldsValue instanceof BaseRangeFilter) {
                $cfFilter[$fieldId] = $customFieldsValue->toFilter();
            } else {
                $cfFilter[$fieldId][] = $customFieldsValue;
            }
        }

        $this->customFieldsValues = $customFieldsValues;

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
     * @return CustomersFilter
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
            $filter['filter']['id'] = $this->getIds();
        }

        if (!is_null($this->getNames())) {
            $filter['filter']['name'] = $this->getNames();
        }

        if (!is_null($this->getCreatedBy())) {
            $filter['filter']['created_by'] = $this->getCreatedBy();
        }

        if (!is_null($this->getUpdatedBy())) {
            $filter['filter']['updated_by'] = $this->getUpdatedBy();
        }

        if (!is_null($this->getResponsibleUserId())) {
            $filter['filter']['responsible_user_id'] = $this->getResponsibleUserId();
        }

        if (!is_null($this->getCreatedAt())) {
            $filter['filter']['created_at'] = $this->getCreatedAt();
        }

        if (!is_null($this->getUpdatedAt())) {
            $filter['filter']['updated_at'] = $this->getUpdatedAt();
        }

        if (!is_null($this->getClosedAt())) {
            $filter['filter']['closed_at'] = $this->getClosedAt();
        }

        if (!is_null($this->getClosestTaskAt())) {
            $filter['filter']['closest_task_at'] = $this->getClosestTaskAt();
        }

        if (!is_null($this->getCustomFieldsValues())) {
            $filter['filter']['custom_fields_values'] = $this->getCustomFieldsValues();
        }

        if (!is_null($this->getQuery())) {
            $filter['query'] = $this->getQuery();
        }

        if (!is_null($this->getOrder())) {
            $filter['order'] = $this->getOrder();
        }

        $filter = $this->buildPagesFilter($filter);

        return $filter;
    }
}
