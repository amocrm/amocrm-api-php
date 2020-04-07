<?php

namespace AmoCRM\Models;

abstract class BaseApiModel
{
    /**
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * @return array
     */
    public static function getAvailableWith(): array
    {
        return [];
    }

    /**
     * @param int|null $requestId
     * @return array
     */
    abstract public function toApi(int $requestId = null): array;
}
