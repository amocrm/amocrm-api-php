<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

/**
 * Class CheckboxCustomFieldValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 */
class CheckboxCustomFieldValueModel extends BaseCustomFieldValueModel
{
    /**
     * @param bool $value
     *
     * @return $this|BaseCustomFieldValueModel
     */
    public function setValue($value): BaseCustomFieldValueModel
    {
        $this->value = (bool)$value;

        return $this;
    }
}
