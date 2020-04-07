<?php

namespace AmoCRM\Models\CustomFieldsValues;

class BaseArrayCustomFieldsValue extends BaseCustomFieldsValue
{
    /**
     * @param $value
     * @return $this
     */
    public function setValue($value): BaseCustomFieldsValue
    {
        $this->value = (array)$value;

        return $this;
    }
}
