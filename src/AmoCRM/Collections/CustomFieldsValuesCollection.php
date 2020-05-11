<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel;

/**
 * Class CustomFieldsValuesCollection
 *
 * @package AmoCRM\Collections
 * @method BaseCustomFieldValuesModel getBy($key, $value) : ?BaseCustomFieldValuesModel
 */
class CustomFieldsValuesCollection extends BaseApiCollection
{
    protected $itemClass = BaseCustomFieldValuesModel::class;
}
