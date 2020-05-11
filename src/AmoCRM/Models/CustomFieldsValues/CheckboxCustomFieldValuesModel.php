<?php

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Helpers\CustomFieldHelper;

/**
 * Class CheckboxCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class CheckboxCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldHelper::FIELD_TYPE_CODE_CHECKBOX;
    }
}
