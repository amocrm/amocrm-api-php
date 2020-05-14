<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel;

/**
 * Class CustomFieldsValuesCollection
 *
 * @package AmoCRM\Collections
 *
 * @method BaseCustomFieldValuesModel current() : ?BaseApiModel
 * @method BaseCustomFieldValuesModel last() : ?BaseApiModel
 * @method BaseCustomFieldValuesModel first() : ?BaseApiModel
 * @method BaseCustomFieldValuesModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, BaseCustomFieldValuesModel $value) : BaseApiCollection
 * @method self prepend(BaseCustomFieldValuesModel $value) : BaseApiCollection
 * @method self add(BaseCustomFieldValuesModel $value) : BaseApiCollection
 * @method BaseCustomFieldValuesModel getBy($key, $value) : ?BaseApiModel
 */
class CustomFieldsValuesCollection extends BaseApiCollection
{
    public const ITEM_CLASS = BaseCustomFieldValuesModel::class;
}
