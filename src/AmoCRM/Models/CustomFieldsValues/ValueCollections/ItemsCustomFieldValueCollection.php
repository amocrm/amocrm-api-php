<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueCollections;

use AmoCRM\Models\CustomFieldsValues\ValueModels\ItemsCustomFieldValueModel;

/**
 * Class ItemsCustomFieldValueCollection
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueCollections
 */
class ItemsCustomFieldValueCollection extends BaseCustomFieldValueCollection
{
    public const ITEM_CLASS = ItemsCustomFieldValueModel::class;
}
