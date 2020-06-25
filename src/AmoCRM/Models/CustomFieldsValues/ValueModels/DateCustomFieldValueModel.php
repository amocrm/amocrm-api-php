<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

/**
 * Class DateCustomFieldsValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 */
class DateCustomFieldValueModel extends BaseCustomFieldValueModel
{
    /**
     * @param array|int|string|null $value
     *
     * @return $this|BaseCustomFieldValueModel
     */
    public function setValue($value): BaseCustomFieldValueModel
    {
        if (is_numeric($value)) {
            $this->value = $value;
        } else {
            $this->value = strtotime($value);
        }

        return $this;
    }

    /**
     * @param string|null $requestId
     *
     * @return array
     */
    public function toApi(string $requestId = null): array
    {
        return [
            'value' => $this->value,
        ];
    }
}
