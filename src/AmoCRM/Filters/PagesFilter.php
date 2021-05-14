<?php

namespace AmoCRM\Filters;

use AmoCRM\Filters\Interfaces\HasPagesInterface;
use AmoCRM\Filters\Traits\PagesFilterTrait;

class PagesFilter extends BaseEntityFilter implements HasPagesInterface
{
    use PagesFilterTrait;

    /**
     * @return array
     */
    public function buildFilter(): array
    {
        return $this->buildPagesFilter();
    }
}
