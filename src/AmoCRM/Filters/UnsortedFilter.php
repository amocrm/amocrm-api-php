<?php

namespace AmoCRM\Filters;

use AmoCRM\Filters\Interfaces\HasOrderInterface;
use AmoCRM\Filters\Interfaces\HasPagesInterface;
use AmoCRM\Filters\Traits\ArrayOrStringFilterTrait;
use AmoCRM\Filters\Traits\OrderTrait;
use AmoCRM\Filters\Traits\PagesFilterTrait;

class UnsortedFilter extends BaseEntityFilter implements HasPagesInterface, HasOrderInterface
{
    use PagesFilterTrait;
    use OrderTrait;
    use ArrayOrStringFilterTrait;

    /**
     * @var array|null
     */
    private $uids = null;

    /**
     * @var string|array|null
     */
    private $category = null;

    /**
     * @var int|null
     */
    private $pipelineId = null;

    /**
     * @return array|null
     */
    public function getUids(): ?array
    {
        return $this->uids;
    }

    /**
     * @param array|null $uids
     * @return UnsortedFilter
     */
    public function setUids(?array $uids): UnsortedFilter
    {
        $this->uids = $this->parseArrayOrStringFilter($uids);

        return $this;
    }

    /**
     * @return array|string|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param array|string|null $category
     * @return UnsortedFilter
     */
    public function setCategory($category)
    {
        $this->category = $this->parseArrayOrStringFilter($category);

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPipelineId(): ?int
    {
        return $this->pipelineId;
    }

    /**
     * @param int|null $pipelineId
     * @return UnsortedFilter
     */
    public function setPipelineId(?int $pipelineId): UnsortedFilter
    {
        $this->pipelineId = $pipelineId;

        return $this;
    }

    /**
     * @return array
     */
    public function buildFilter(): array
    {
        $filter = [];

        if (!is_null($this->getUids())) {
            $filter['filter']['uid'] = $this->getUids();
        }

        if (!is_null($this->getCategory())) {
            $filter['filter']['category'] = $this->getCategory();
        }

        if (!is_null($this->getPipelineId())) {
            $filter['filter']['pipeline_id'] = $this->getPipelineId();
        }

        if (!is_null($this->getOrder())) {
            $filter['order'] = $this->getOrder();
        }

        $filter = $this->buildPagesFilter($filter);

        return $filter;
    }
}
