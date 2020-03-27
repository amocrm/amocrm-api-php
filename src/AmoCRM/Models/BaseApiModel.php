<?php

namespace AmoCRM\Models;

abstract class BaseApiModel
{
    /**
     * @return mixed
     */
    abstract public function toArray();

    /**
     * @return array
     */
    public static function getAvailableWith(): array
    {
        return [];
    }
}
