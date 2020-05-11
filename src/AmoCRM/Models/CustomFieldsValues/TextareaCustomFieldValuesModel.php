<?php

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Helpers\CustomFieldHelper;

/**
 * Class TextareaCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class TextareaCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldHelper::FIELD_TYPE_CODE_TEXTAREA;
    }
}
