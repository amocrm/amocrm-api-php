<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\AccountSettings\TaskType;

/**
 * Class TaskTypesCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|TaskType current()
 * @method null|TaskType last()
 * @method null|TaskType first()
 * @method null|TaskType offsetGet($offset)
 * @method void offsetSet($offset, TaskType $value)
 * @method TaskTypesCollection prepend(TaskType $value)
 * @method TaskTypesCollection add(TaskType $value)
 * @method null|TaskType getBy($key, $value)
 * @method TaskTypesCollection fromArray($array)
 */
class TaskTypesCollection extends BaseApiCollection
{
    public const ITEM_CLASS = TaskType::class;
}
