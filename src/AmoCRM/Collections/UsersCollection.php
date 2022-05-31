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
 * @method null|UserModel current()
 * @method null|UserModel last()
 * @method null|UserModel first()
 * @method null|UserModel offsetGet($offset)
 * @method void offsetSet($offset, UserModel $value)
 * @method UsersCollection prepend(UserModel $value)
 * @method UsersCollection add(UserModel $value)
 * @method null|UserModel getBy($key, $value)
 */
class UsersCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = UserModel::class;
}
