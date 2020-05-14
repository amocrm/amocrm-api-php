<?php

namespace AmoCRM\Models\CustomFields;

/**
 * Class TextCustomFieldModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class TextCustomFieldModel extends CustomFieldModel
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_TEXT;
    }
}
