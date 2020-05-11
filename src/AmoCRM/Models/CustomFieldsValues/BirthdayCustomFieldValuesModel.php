<?php

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Helpers\CustomFieldHelper;

/**
 * Class BirthdayCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class BirthdayCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldHelper::FIELD_TYPE_CODE_BIRTHDAY;
    }
}
