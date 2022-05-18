<?php

declare(strict_types=1);

namespace AmoCRM\AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Models\CustomFields\CustomFieldModel;
use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel;

/**
 * @since Release Spring 2022
 */
class ChainedListCustomFieldValuesModel extends BaseCustomFieldValuesModel
{
    /**
     * @return string Тип поля
     */
    public function getFieldType(): string
    {
        return CustomFieldModel::TYPE_CHAINED_LIST;
    }
}
