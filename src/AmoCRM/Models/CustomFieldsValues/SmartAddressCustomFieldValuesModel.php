<?php

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Helpers\CustomFieldHelper;

/**
 * Class SmartAddressCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class SmartAddressCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldHelper::FIELD_TYPE_CODE_SMART_ADDRESS;
    }
}
