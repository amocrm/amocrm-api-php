<?php

namespace AmoCRM\AmoCRM\Models\Traits;

use AmoCRM\AmoCRM\Exceptions\NotContainCustomFieldsException;
use AmoCRM\AmoCRM\Models\Interfaces\HasCustomFieldsValuesInterface;

trait CustomFieldsValuesGetterTrait
{
    /**
     * @param int $fieldId
     *
     * @return string
     */
    public function getPlainValueFromCf(int $fieldId): ?string
    {
        if (!$this->hasCustomFields()) {
            throw new NotContainCustomFieldsException(sprintf('%s does not contain custom fields', self::class));
        }

        $customFieldsValues = $this->getCustomFieldsValues();

        if ($customFieldsValues !== null) {
            $valueModel = $customFieldsValues->getBy('fieldId', $fieldId);

            if ($valueModel && $values = $valueModel->getValues()->first()) {
                $value = $values->toArray()['value'] ?? null;
            }
        }

        return $value ?? null;
    }

    /**
     * @return bool
     */
    protected function hasCustomFields(): bool
    {
        return $this instanceof HasCustomFieldsValuesInterface;
    }
}
