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
 * @method TaskModel current() : ?BaseApiModel
 * @method TaskModel last() : ?BaseApiModel
 * @method TaskModel first() : ?BaseApiModel
 * @method TaskModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, TaskModel $value) : BaseApiCollection
 * @method self prepend(TaskModel $value) : BaseApiCollection
 * @method self add(TaskModel $value) : BaseApiCollection
 * @method TaskModel getBy($key, $value) : ?BaseApiModel
 */
class TasksCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = TaskModel::class;
}
