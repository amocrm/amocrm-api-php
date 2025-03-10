<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueCollections;

use AmoCRM\Models\CustomFieldsValues\ValueModels\LegalEntityCustomFieldValueModel;

/**
 * Class LegalEntityCustomFieldValueCollection
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueCollections
 */
class LegalEntityCustomFieldValueCollection extends BaseCustomFieldValueCollection
{
    public const ITEM_CLASS = LegalEntityCustomFieldValueModel::class;
}
