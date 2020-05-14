<?php

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Models\CustomFields\CustomFieldModel;

/**
 * Class ItemsCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class ItemsCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldModel::TYPE_ITEMS;
    }
}
