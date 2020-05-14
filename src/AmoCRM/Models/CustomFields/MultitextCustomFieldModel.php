<?php

namespace AmoCRM\Models\CustomFields;

/**
 * Class MultitextCustomFieldModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class MultitextCustomFieldModel extends WithEnumCustomFieldModel
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_MULTITEXT;
    }
}
