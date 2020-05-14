<?php

namespace AmoCRM\Models\CustomFields;

/**
 * Class StreetAddressCustomFieldModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class StreetAddressCustomFieldModel extends CustomFieldModel
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_STREET_ADDRESS;
    }
}
