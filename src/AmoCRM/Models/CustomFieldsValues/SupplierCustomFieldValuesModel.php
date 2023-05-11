<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Models\CustomFields\CustomFieldModel;

/**
 * Class SupplierCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class SupplierCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldModel::TYPE_SUPPLIER;
    }
}
