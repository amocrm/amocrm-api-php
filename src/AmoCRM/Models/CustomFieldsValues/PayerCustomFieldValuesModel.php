<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Models\CustomFields\CustomFieldModel;

/**
 * Class PayerCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class PayerCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return CustomFieldModel::TYPE_PAYER;
    }
}
