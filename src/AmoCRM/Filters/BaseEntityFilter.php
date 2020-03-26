<?php

namespace AmoCRM\Filters;

abstract class BaseEntityFilter
{
    abstract public function buildFilter(): array;
}
