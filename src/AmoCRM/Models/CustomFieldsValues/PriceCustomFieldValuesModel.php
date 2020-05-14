<?php

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Models\CustomFields\CustomFieldModel;

/**
 * Class PriceCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class PriceCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldModel::TYPE_PRICE;
    }
}
