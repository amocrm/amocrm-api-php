<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\AccountSettings\UsersGroup;

/**
 * Class UsersGroupsCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|UsersGroup current()
 * @method null|UsersGroup last()
 * @method null|UsersGroup first()
 * @method null|UsersGroup offsetGet($offset)
 * @method void offsetSet($offset, UsersGroup $value)
 * @method UsersGroupsCollection prepend(UsersGroup $value)
 * @method UsersGroupsCollection add(UsersGroup $value)
 * @method null|UsersGroup getBy($key, $value)
 * @method UsersGroupsCollection fromArray($array)
 */
class UsersGroupsCollection extends BaseApiCollection
{
    public const ITEM_CLASS = UsersGroup::class;
}
