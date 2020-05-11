<?php

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Helpers\CustomFieldHelper;

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
        return CustomFieldHelper::FIELD_TYPE_CODE_NUMERIC;
    }
}
