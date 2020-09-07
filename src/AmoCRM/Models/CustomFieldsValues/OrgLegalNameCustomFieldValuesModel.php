<?php

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Models\CustomFields\CustomFieldModel;

/**
 * Class OrgLegalNameCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class OrgLegalNameCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldModel::TYPE_ORG_LEGAL_NAME;
    }
}
