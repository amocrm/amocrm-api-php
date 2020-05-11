<?php

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Helpers\CustomFieldHelper;

/**
 * Class RadiobuttonCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class RadiobuttonCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldHelper::FIELD_TYPE_CODE_RADIOBUTTON;
    }
}
