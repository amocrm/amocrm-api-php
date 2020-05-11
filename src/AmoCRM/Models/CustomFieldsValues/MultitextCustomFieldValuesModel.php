<?php

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Helpers\CustomFieldHelper;

/**
 * Class MultitextCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class MultitextCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldHelper::FIELD_TYPE_CODE_MULTITEXT;
    }
}
