<?php

namespace AmoCRM\Models\CustomFields;

/**
 * Class CheckboxCustomFieldModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class CheckboxCustomFieldModel extends CustomFieldModel
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_CHECKBOX;
    }
}
