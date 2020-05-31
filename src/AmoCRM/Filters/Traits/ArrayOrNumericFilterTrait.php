<?php

namespace AmoCRM\Filters\Traits;

trait ArrayOrNumericFilterTrait
{
    /**
     * @param array|int $value
     *
     * @return array|null
     */
    public function parseArrayOrNumberFilter($value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        $value = array_map(function ($number) {
            return is_numeric($number) && $number >= 0 ? (int)$number : null;
        }, $value);

        $value = array_filter($value, function ($number) {
            return !is_null($number);
        });

        if (empty($value)) {
            $value = null;
        }

        return $value;
    }
}
