<?php

namespace AmoCRM\Collections\CustomFields;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\CustomFields\EnumModel;

/**
 * Class CustomFieldEnumsCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|EnumModel current()
 * @method null|EnumModel last()
 * @method null|EnumModel first()
 * @method null|EnumModel offsetGet($offset)
 * @method void offsetSet($offset, EnumModel $value)
 * @method CustomFieldEnumsCollection prepend(EnumModel $value)
 * @method CustomFieldEnumsCollection add(EnumModel $value)
 * @method null|EnumModel getBy($key, $value)
 */
class CustomFieldEnumsCollection extends BaseApiCollection
{
    public const ITEM_CLASS = EnumModel::class;
}
