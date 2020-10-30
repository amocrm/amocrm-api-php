<?php

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Models\CustomFields\CustomFieldModel;

/**
 * Class MultiselectCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class MultiselectCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldModel::TYPE_MULTISELECT;
    }
}
