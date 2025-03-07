<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueCollections;

use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;

/**
 * Class MultitextCustomFieldValueCollection
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueCollections
 */
class MultitextCustomFieldValueCollection extends BaseCustomFieldValueCollection
{
    public const ITEM_CLASS = MultitextCustomFieldValueModel::class;
}
