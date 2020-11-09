<?php

namespace AmoCRM\Collections;

use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\RoleModel;

/**
 * Class RolesCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|RoleModel current()
 * @method null|RoleModel last()
 * @method null|RoleModel first()
 * @method null|RoleModel offsetGet($offset)
 * @method RolesCollection offsetSet($offset, RoleModel $value)
 * @method RolesCollection prepend(RoleModel $value)
 * @method RolesCollection add(RoleModel $value)
 * @method null|RoleModel getBy($key, $value)
 */
class RolesCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = RoleModel::class;
}
