<?php

namespace AmoCRM\AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Models\CustomFields\CustomFieldModel;
use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel;

/**
 * Class TrackingDataCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class TrackingDataCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldModel::TYPE_TRACKING_DATA;
    }
}
