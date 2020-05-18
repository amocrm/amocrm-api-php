<?php

namespace AmoCRM\Filters;

class UnsortedSummaryFilter extends BaseEntityFilter
{
    /**
     * @var array|int|null
     */
    private $createdAt = null;

    /**
     * @var int|null
     */
    private $pipelineId = null;

    /**
     * @return int|null
     */
    public function getPipelineId(): ?int
    {
        return $this->pipelineId;
    }

    /**
     * @param int|null $pipelineId
     * @return UnsortedSummaryFilter
     */
    public function setPipelineId(?int $pipelineId): UnsortedSummaryFilter
    {
        $this->pipelineId = $pipelineId;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getCreatedAt(): ?array
    {
        return $this->createdAt;
    }

    /**
     * @param BaseRangeFilter|int|null $createdAt
     * @return UnsortedSummaryFilter
     */
    public function setCreatedAt($createdAt): UnsortedSummaryFilter
    {
        if ($createdAt instanceof BaseRangeFilter) {
            $createdAt = $createdAt->toFilter();
        }

        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return array
     */
    public function buildFilter(): array
    {
        $filter = [];

        if (!is_null($this->getCreatedAt())) {
            $filter['filter']['created_at'] = $this->getCreatedAt();
        }

        if (!is_null($this->getPipelineId())) {
            $filter['filter']['pipeline_id'] = $this->getPipelineId();
        }

        return $filter;
    }
}
