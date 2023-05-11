<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFields;

/**
 * Class SupplierCustomFieldModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class SupplierCustomFieldModel extends CustomFieldModel
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_SUPPLIER;
    }
}
