<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueCollections;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\BaseCustomFieldValueModel;

/**
 * Class BaseCustomFieldValueCollection
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueCollections
 *
 * @method BaseCustomFieldValueModel first()
 * @method BaseCustomFieldValueModel last()
 */
class BaseCustomFieldValueCollection extends BaseApiCollection
{
    public const ITEM_CLASS = BaseCustomFieldValueModel::class;
}
