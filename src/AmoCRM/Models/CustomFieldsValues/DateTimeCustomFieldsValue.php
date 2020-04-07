<?php

namespace AmoCRM\Models\CustomFieldsValues;

use Libs\Locale\Date;

class DateTimeCustomFieldsValue extends DateCustomFieldsValue
{
    /**
     * @return mixed
     */
    public function getValue()
    {
        return date(Date::FORMAT_PHP_DB_TIME, $this->value);
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
