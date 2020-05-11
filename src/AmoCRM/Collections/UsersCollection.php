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
 * @method null|UserModel getBy($key, $value) : ?UserModel
 */
class UsersCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    protected $itemClass = UserModel::class;
}
