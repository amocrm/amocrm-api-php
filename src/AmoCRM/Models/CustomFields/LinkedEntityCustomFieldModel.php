<?php

namespace AmoCRM\Models\CustomFields;

/**
 * Class LinkedEntityCustomFieldModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class LinkedEntityCustomFieldModel extends CustomFieldModel
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_LINKED_ENTITY;
    }
}
