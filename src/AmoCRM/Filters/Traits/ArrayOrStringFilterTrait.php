<?php

namespace AmoCRM\Filters\Traits;

use function array_filter;
use function array_map;
use function is_array;
use function is_null;
use function is_string;
use function strlen;

trait ArrayOrStringFilterTrait
{
    /**
     * @param array|string $value
     *
     * @return array|null
     */
    public function parseArrayOrStringFilter($value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        $value = array_map(function ($string) {
            return is_string($string) && strlen($string) > 0 ? $string : null;
        }, $value);

        $value = array_filter($value, function ($string) {
            return !is_null($string);
        });

        if (empty($value)) {
            $value = null;
        }

        return $value;
    }
}
