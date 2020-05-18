<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\CustomFieldGroupModel;

/**
 * Class CustomFieldGroupsCollection
 *
 * @package AmoCRM\Collections
 *
 * @method CustomFieldGroupModel current() : ?BaseApiModel
 * @method CustomFieldGroupModel last() : ?BaseApiModel
 * @method CustomFieldGroupModel first() : ?BaseApiModel
 * @method CustomFieldGroupModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, CustomFieldGroupModel $value) : BaseApiCollection
 * @method self prepend(CustomFieldGroupModel $value) : BaseApiCollection
 * @method self add(CustomFieldGroupModel $value) : BaseApiCollection
 * @method CustomFieldGroupModel getBy($key, $value) : ?BaseApiModel
 */
class CustomFieldGroupsCollection extends BaseApiCollection
{
    public const ITEM_CLASS = CustomFieldGroupModel::class;
}
