<?php

namespace AmoCRM\AmoCRM\Models\CustomFields;

use AmoCRM\Models\CustomFields\CustomFieldModel;

/**
 * @since Release Spring 2022
 */
class MonetaryCustomFieldModel extends CustomFieldModel
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_MONETARY;
    }
}
