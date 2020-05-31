<?php

namespace AmoCRM\Filters;

use AmoCRM\Filters\Interfaces\HasPagesInterface;
use AmoCRM\Filters\Traits\ArrayOrNumericFilterTrait;
use AmoCRM\Filters\Traits\PagesFilterTrait;
use AmoCRM\Filters\Traits\IntOrIntRangeFilterTrait;

class EventsFilter extends BaseEntityFilter implements HasPagesInterface
{
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
    private $types = null;

    /**
     * @var array|null
     */
    private $entity = null;

    /**
     * @var array|null
     */
    private $valueAfter = null;

    /**
     * @var array|null
     */
    private $valueBefore = null;

    /**
     * @var array|null
     */
    private $createdAt = null;

    /**
     * @var array|null
     */
    private $createdBy = null;

    /**
     * @var array|null
     */
    private $entityIds = null;

    /**
     * @return array|null
     */
    public function getIds(): ?array
    {
        return $this->ids;
    }

    /**
     * @param array|null $ids
     * @return EventsFilter
     */
    public function setIds(?array $ids): EventsFilter
    {
        $this->ids = $ids;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getTypes(): ?array
    {
        return $this->types;
    }

    /**
     * @param array|null $types
     * @return EventsFilter
     */
    public function setTypes(?array $types): EventsFilter
    {
        $this->types = $types;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getEntity(): ?array
    {
        return $this->entity;
    }

    /**
     * @param array|null $entity
     * @return EventsFilter
     */
    public function setEntity(?array $entity): EventsFilter
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getValueAfter(): ?array
    {
        return $this->valueAfter;
    }

    /**
     * @param array|null $valueAfter
     * @return EventsFilter
     */
    public function setValueAfter(?array $valueAfter): EventsFilter
    {
        $this->valueAfter = $valueAfter;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getValueBefore(): ?array
    {
        return $this->valueBefore;
    }

    /**
     * @param array|null $valueBefore
     * @return EventsFilter
     */
    public function setValueBefore(?array $valueBefore): EventsFilter
    {
        $this->valueBefore = $valueBefore;

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
     * @param array|int|null $createdAt
     * @return EventsFilter
     */
    public function setCreatedAt($createdAt)
    {
        //todo support for range filter
        if (!is_array($createdAt)) {
            $createdAt = [$createdAt];
        }

        $this->createdAt = $createdAt;

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
     * @return EventsFilter
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $this->parseArrayOrNumberFilter($createdBy);

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getEntityIds()
    {
        return $this->entityIds;
    }

    /**
     * @param array|int|null $entityIds
     * @return EventsFilter
     */
    public function setEntityIds($entityIds)
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
            $filter['filter']['id'] = implode(',', $this->getIds());
        }

        if (!is_null($this->getTypes())) {
            $filter['filter']['type'] = implode(',', $this->getTypes());
        }

        if (!is_null($this->getEntity())) {
            $filter['filter']['entity'] = implode(',', $this->getEntity());
        }

        if (!is_null($this->getValueAfter())) {
            $filter['filter']['value_after'] = $this->getValueAfter();
        }

        if (!is_null($this->getValueBefore())) {
            $filter['filter']['value_before'] = $this->getValueBefore();
        }

        if (!is_null($this->getCreatedBy())) {
            $filter['filter']['created_by'] = implode(',', $this->getCreatedBy());
        }

        if (!is_null($this->getCreatedAt())) {
            $filter['filter']['created_at'] = implode(',', $this->getCreatedAt());
        }

        if (!is_null($this->getEntityIds())) {
            $filter['filter']['entity_id'] = implode(',', $this->getEntityIds());
        }

        $filter = $this->buildPagesFilter($filter);

        return $filter;
    }
}
