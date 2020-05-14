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
 * @method RoleModel current() : ?BaseApiModel
 * @method RoleModel last() : ?BaseApiModel
 * @method RoleModel first() : ?BaseApiModel
 * @method RoleModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, RoleModel $value) : BaseApiCollection
 * @method self prepend(RoleModel $value) : BaseApiCollection
 * @method self add(RoleModel $value) : BaseApiCollection
 * @method RoleModel getBy($key, $value) : ?BaseApiModel
 */
class RolesCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = RoleModel::class;
}
