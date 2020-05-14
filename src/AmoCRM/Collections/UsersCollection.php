<?php

namespace AmoCRM\Collections;

use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\UserModel;

/**
 * Class UsersCollection
 *
 * @package AmoCRM\Collections
 *
 * @method UserModel current() : ?BaseApiModel
 * @method UserModel last() : ?BaseApiModel
 * @method UserModel first() : ?BaseApiModel
 * @method UserModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, UserModel $value) : BaseApiCollection
 * @method self prepend(UserModel $value) : BaseApiCollection
 * @method self add(UserModel $value) : BaseApiCollection
 * @method UserModel getBy($key, $value) : ?BaseApiModel
 */
class UsersCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = UserModel::class;
}
