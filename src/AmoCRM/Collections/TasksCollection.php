<?php

namespace AmoCRM\Collections;

use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\TaskModel;

/**
 * Class TasksCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|TaskModel current()
 * @method null|TaskModel last()
 * @method null|TaskModel first()
 * @method null|TaskModel offsetGet($offset)
 * @method TasksCollection offsetSet($offset, TaskModel $value)
 * @method TasksCollection prepend(TaskModel $value)
 * @method TasksCollection add(TaskModel $value)
 * @method null|TaskModel getBy($key, $value)
 */
class TasksCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = TaskModel::class;
}
