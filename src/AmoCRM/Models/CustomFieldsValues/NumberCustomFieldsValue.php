<?php

namespace AmoCRM\Models\CustomFieldsValues;

class NumberCustomFieldsValue extends BaseCustomFieldsValue
{
    public function setValue($value): BaseCustomFieldsValue
    {
        $this->value = (string)$value;

        return $this;
    }
}
