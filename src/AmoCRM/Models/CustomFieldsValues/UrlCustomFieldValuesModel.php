<?php

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Helpers\CustomFieldHelper;

/**
 * Class UrlCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class UrlCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldHelper::FIELD_TYPE_CODE_URL;
    }
}
