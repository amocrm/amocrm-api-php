<?php

namespace AmoCRM\Filters;

class EntitiesLinksFilter extends LinksFilter
{
    /** @var int[] */
    private $entityId;

    /**
     * @param int|int[] $entityId
     */
    public function __construct($entityId)
    {
        $this->entityId = (array)$entityId;
    }

    /**
     * @return int[]
     */
    public function getEntityId(): array
    {
        return $this->entityId;
    }

    /**
     * @return array
     */
    public function buildFilter(): array
    {
        $filter = parent::buildFilter();
        $filter['filter']['entity_id'] = $this->getEntityId();

        return $filter;
    }
}
