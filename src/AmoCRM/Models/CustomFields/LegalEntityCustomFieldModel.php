<?php

namespace AmoCRM\Models\CustomFields;

/**
 * Class LegalEntityCustomFieldModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class LegalEntityCustomFieldModel extends WithEnumCustomFieldModel
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_LEGAL_ENTITY;
    }
}
