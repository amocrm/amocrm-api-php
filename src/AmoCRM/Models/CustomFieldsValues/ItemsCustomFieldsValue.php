<?php

namespace AmoCRM\Models\CustomFieldsValues;

class ItemsCustomFieldsValue extends BaseCustomFieldsValue
{
    public function setValue($value): BaseCustomFieldsValue
    {
        $this->value = (array)$value;

        return $this;
    }
}
