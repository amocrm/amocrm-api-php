<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

use AmoCRM\Exceptions\InvalidArgumentException;
use Carbon\Carbon;
use DateTime;
use DateTimeInterface;

/**
 * Class DateCustomFieldsValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 *
 * @method DateCustomFieldValueModel fromArray($value)
 * @method DateTimeInterface|null getValue()
 */
class DateCustomFieldValueModel extends BaseCustomFieldValueModel
{
    /**
     * @var DateTimeInterface|null
     */
    protected $value;

    /**
     * @param int|string|DateTimeInterface|null $value
     *
     * @return $this|BaseCustomFieldValueModel
     * @throws InvalidArgumentException
     */
    public function setValue($value): BaseCustomFieldValueModel
    {
        if ($value instanceof DateTimeInterface) {
            $this->value = $value;
        } else {
            if (!is_scalar($value)) {
                throw new InvalidArgumentException('Given value is not scalar');
            }

            if (is_numeric($value)) {
                $fieldValue = (int)$value;
            } else {
                $fieldValue = strtotime($value);
            }

            if (!$fieldValue && $fieldValue !== 0) {
                throw new InvalidArgumentException('Given value is not valid - ' . $value);
            } else {
                $dateTimeValue = Carbon::createFromTimestamp($fieldValue);
                $this->value = $dateTimeValue;
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
            'value' => $this->value->format(DateTime::RFC3339),
        ];
    }
}
