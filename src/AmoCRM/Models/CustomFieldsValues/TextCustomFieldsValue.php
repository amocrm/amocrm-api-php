<?php

namespace AmoCRM\Models\CustomFieldsValues;

class TextCustomFieldsValue extends BaseCustomFieldsValue
{
    public function setValue($value): BaseCustomFieldsValue
    {
        $this->value = (string)$value;

        return $this;
    }
}
