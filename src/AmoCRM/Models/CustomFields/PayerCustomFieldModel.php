<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFields;

/**
 * Class PayerCustomFieldModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class PayerCustomFieldModel extends CustomFieldModel
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_PAYER;
    }
}
