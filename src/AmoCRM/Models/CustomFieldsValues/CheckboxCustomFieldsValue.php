<?php

namespace AmoCRM\Models\CustomFieldsValues;

class CheckboxCustomFieldsValue extends BaseCustomFieldsValue
{
    public function setValue($value): BaseCustomFieldsValue
    {
        $this->value = (bool)$value;

        return $this;
    }
}
