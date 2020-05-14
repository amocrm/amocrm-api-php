<?php

namespace AmoCRM\Collections\CustomFields;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\CustomFields\NestedModel;

/**
 * Class CustomFieldNestedCollection
 *
 * @package AmoCRM\Collections
 *
 * @method NestedModel current() : ?BaseApiModel
 * @method NestedModel last() : ?BaseApiModel
 * @method NestedModel first() : ?BaseApiModel
 * @method NestedModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, NestedModel $value) : BaseApiCollection
 * @method self prepend(NestedModel $value) : BaseApiCollection
 * @method self add(NestedModel $value) : BaseApiCollection
 * @method NestedModel getBy($key, $value) : ?BaseApiModel
 */
class CustomFieldNestedCollection extends BaseApiCollection
{
    public const ITEM_CLASS = NestedModel::class;
}
