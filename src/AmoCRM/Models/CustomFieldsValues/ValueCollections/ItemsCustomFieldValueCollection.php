<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueCollections;

use AmoCRM\Models\CustomFieldsValues\ValueModels\ItemsCustomFieldValueModel;

/**
 * Class ItemsCustomFieldValueCollection
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueCollections
 *
 * @method ItemsCustomFieldValueCollection merge(ItemsCustomFieldValueCollection $items)
 * @method static ItemsCustomFieldValueCollection make(array $items)
 */
class ItemsCustomFieldValueCollection extends BaseCustomFieldValueCollection
{
    public const ITEM_CLASS = ItemsCustomFieldValueModel::class;
}
