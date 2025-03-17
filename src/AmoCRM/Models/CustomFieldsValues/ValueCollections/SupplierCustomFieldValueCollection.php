<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFieldsValues\ValueCollections;

use AmoCRM\Models\CustomFieldsValues\ValueModels\SupplierCustomFieldValueModel;

/**
 * Class SupplierCustomFieldValueCollection
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueCollections
 */
class SupplierCustomFieldValueCollection extends BaseCustomFieldValueCollection
{
    public const ITEM_CLASS = SupplierCustomFieldValueModel::class;
}
