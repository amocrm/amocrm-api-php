<?php

namespace AmoCRM\Filters\Traits;

trait PagesFilterTrait
{
    /**
     * @var int
     */
    private $page = 1;

    /**
     * @var int
     */
    private $limit = 50;

    /**
     * @param int $page
     *
     * @return PagesFilterTrait
     */
    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $limit
     * @return PagesFilterTrait
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    protected function buildPagesFilter(array $filter): array
    {
        if (!is_null($this->getLimit())) {
            $filter['limit'] = $this->getLimit();
        }

        if (!is_null($this->getPage())) {
            $filter['page'] = $this->getPage();
        }

        return $filter;
    }
}
