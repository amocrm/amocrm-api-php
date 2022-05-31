<?php

namespace AmoCRM\Collections\CustomFields;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\CustomFields\NestedModel;

/**
 * Class CustomFieldNestedCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|NestedModel current()
 * @method null|NestedModel last()
 * @method null|NestedModel first()
 * @method null|NestedModel offsetGet($offset)
 * @method void offsetSet($offset, NestedModel $value)
 * @method CustomFieldNestedCollection prepend(NestedModel $value)
 * @method CustomFieldNestedCollection add(NestedModel $value)
 * @method null|NestedModel getBy($key, $value)
 */
class CustomFieldNestedCollection extends BaseApiCollection
{
    public const ITEM_CLASS = NestedModel::class;
}
