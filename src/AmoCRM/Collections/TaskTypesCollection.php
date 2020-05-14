<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\AccountSettings\TaskType;

/**
 * Class TaskTypesCollection
 *
 * @package AmoCRM\Collections
 *
 * @method TaskType current() : ?BaseApiModel
 * @method TaskType last() : ?BaseApiModel
 * @method TaskType first() : ?BaseApiModel
 * @method TaskType offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, TaskType $value) : BaseApiCollection
 * @method self prepend(TaskType $value) : BaseApiCollection
 * @method self add(TaskType $value) : BaseApiCollection
 * @method TaskType getBy($key, $value) : ?BaseApiModel
 */
class TaskTypesCollection extends BaseApiCollection
{
    public const ITEM_CLASS = TaskType::class;
}
