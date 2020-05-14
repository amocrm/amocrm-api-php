<?php

namespace AmoCRM\Collections\CustomFields;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\CustomFields\EnumModel;

/**
 * Class CustomFieldEnumsCollection
 *
 * @package AmoCRM\Collections
 *
 * @method EnumModel current() : ?BaseApiModel
 * @method EnumModel last() : ?BaseApiModel
 * @method EnumModel first() : ?BaseApiModel
 * @method EnumModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, EnumModel $value) : BaseApiCollection
 * @method self prepend(EnumModel $value) : BaseApiCollection
 * @method self add(EnumModel $value) : BaseApiCollection
 * @method EnumModel getBy($key, $value) : ?BaseApiModel
 */
class CustomFieldEnumsCollection extends BaseApiCollection
{
    public const ITEM_CLASS = EnumModel::class;
}
