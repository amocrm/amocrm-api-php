<?php

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Helpers\CustomFieldHelper;

/**
 * Class LegalEntityCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class LegalEntityCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldHelper::FIELD_TYPE_CODE_LEGAL_ENTITY;
    }
}
