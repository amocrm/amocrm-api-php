<?php

namespace AmoCRM\Models\CustomFields;

/**
 * Class NumericCustomFieldModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class NumericCustomFieldModel extends CustomFieldModel
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_NUMERIC;
    }
}
