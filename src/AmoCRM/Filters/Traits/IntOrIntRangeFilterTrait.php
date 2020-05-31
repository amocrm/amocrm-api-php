<?php

namespace AmoCRM\Filters\Traits;

use AmoCRM\Filters\BaseRangeFilter;

use function is_int;

trait IntOrIntRangeFilterTrait
{
    /**
     * @param BaseRangeFilter|int $value
     *
     * @return array|null|int
     */
    public function parseIntOrIntRangeFilter($value)
    {
        if ($value instanceof BaseRangeFilter) {
            $value = $value->toFilter();
        } elseif (!is_int($value) || $value < 0) {
            $value = null;
        }

        return $value;
    }
}
