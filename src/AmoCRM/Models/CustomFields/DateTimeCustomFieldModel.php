<?php

namespace AmoCRM\Models\CustomFields;

/**
 * Class DateTimeCustomFieldModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class DateTimeCustomFieldModel extends CustomFieldModel
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_DATE_TIME;
    }
}
