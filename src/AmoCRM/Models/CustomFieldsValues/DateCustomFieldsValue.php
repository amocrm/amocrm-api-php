<?php

namespace AmoCRM\Models\CustomFieldsValues;

use Libs\Locale\Date;

class DateCustomFieldsValue extends BaseCustomFieldsValue
{
    public function setValue($value): BaseCustomFieldsValue
    {
        $this->value = strtotime($value);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return date(Date::FORMAT_PHP_DB_DATE_ONLY, $this->value);
    }

    /**
     * @return array
     */
    public function toOldApi(): array
    {
        $result = [
            'value' => date(Date::FORMAT_PHP_DB_DATE, $this->value),
        ];

        return $result;
    }
}
