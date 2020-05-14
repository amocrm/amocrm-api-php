<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\AccountSettings\UsersGroup;

/**
 * Class UsersGroupsCollection
 *
 * @package AmoCRM\Collections
 *
 * @method UsersGroup current() : ?BaseApiModel
 * @method UsersGroup last() : ?BaseApiModel
 * @method UsersGroup first() : ?BaseApiModel
 * @method UsersGroup offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, UsersGroup $value) : BaseApiCollection
 * @method self prepend(UsersGroup $value) : BaseApiCollection
 * @method self add(UsersGroup $value) : BaseApiCollection
 * @method UsersGroup getBy($key, $value) : ?BaseApiModel
 */
class UsersGroupsCollection extends BaseApiCollection
{
    public const ITEM_CLASS = UsersGroup::class;
}
