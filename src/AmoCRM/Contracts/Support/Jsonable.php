<?php

declare(strict_types=1);

namespace AmoCRM\Contracts\Support;

interface Jsonable
{
    /**
     * Get the instance as a json.
     *
     * @return string
     */
    public function toJson();
}
