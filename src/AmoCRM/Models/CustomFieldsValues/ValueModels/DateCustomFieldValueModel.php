<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

use AmoCRM\Exceptions\InvalidArgumentException;

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
     * @throws InvalidArgumentException
     */
    public function setValue($value): BaseCustomFieldValueModel
    {
        if (is_numeric($value)) {
            $this->value = (int)$value;
        } else {
            if (!is_scalar($value)) {
                throw new InvalidArgumentException('Given value is not valid');
            }

            $value = strtotime($value);
            if (!$value) {
                throw new InvalidArgumentException('Given value is not valid - ' . $value);
            } else {
                $this->value = (int)$value;
            }
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
