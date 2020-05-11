<?php

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Helpers\CustomFieldHelper;

/**
 * Class StreetAddressCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class StreetAddressCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldHelper::FIELD_TYPE_CODE_STREETADDRESS;
    }
}
