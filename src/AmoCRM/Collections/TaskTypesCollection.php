<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\AccountSettings\TaskType;

class TaskTypesCollection extends BaseApiCollection
{
    protected $itemClass = TaskType::class;
}
