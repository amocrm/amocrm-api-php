<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\TaskModel;

class TasksCollection extends BaseApiCollection
{
    protected $itemClass = TaskModel::class;
}
