<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\CustomFieldGroupModel;

/**
 * Class CustomFieldGroupsCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|CustomFieldGroupModel current()
 * @method null|CustomFieldGroupModel last()
 * @method null|CustomFieldGroupModel first()
 * @method null|CustomFieldGroupModel offsetGet($offset)
 * @method void offsetSet($offset, CustomFieldGroupModel $value)
 * @method CustomFieldGroupsCollection prepend(CustomFieldGroupModel $value)
 * @method CustomFieldGroupsCollection add(CustomFieldGroupModel $value)
 * @method null|CustomFieldGroupModel getBy($key, $value)
 */
class CustomFieldGroupsCollection extends BaseApiCollection
{
    public const ITEM_CLASS = CustomFieldGroupModel::class;
}
