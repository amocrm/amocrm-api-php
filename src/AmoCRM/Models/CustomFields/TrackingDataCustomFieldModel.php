<?php

namespace AmoCRM\AmoCRM\Models\CustomFields;

use AmoCRM\Models\CustomFields\CustomFieldModel;

/**
 * Class TrackingDataCustomFieldModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class TrackingDataCustomFieldModel extends CustomFieldModel
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_TRACKING_DATA;
    }
}
