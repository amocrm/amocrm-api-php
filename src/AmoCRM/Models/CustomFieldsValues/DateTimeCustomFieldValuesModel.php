<?php

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Helpers\CustomFieldHelper;

/**
 * Class DateTimeCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class DateTimeCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldHelper::FIELD_TYPE_CODE_DATE_TIME;
    }
}
