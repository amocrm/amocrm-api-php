<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueCollections;

use AmoCRM\Models\CustomFieldsValues\ValueModels\LinkedEntityCustomFieldValueModel;

/**
 * Class LinkedEntityCustomFieldValueCollection
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueCollections
 */
class LinkedEntityCustomFieldValueCollection extends BaseCustomFieldValueCollection
{
    public const ITEM_CLASS = LinkedEntityCustomFieldValueModel::class;
}
