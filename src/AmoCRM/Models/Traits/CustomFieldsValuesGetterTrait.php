<?php

namespace AmoCRM\AmoCRM\Models\Traits;

use AmoCRM\AmoCRM\Exceptions\NotContainCustomFieldsException;
use AmoCRM\AmoCRM\Models\Interfaces\HasCustomFieldsValuesInterface;
use AmoCRM\Collections\CustomFieldsValuesCollection;

trait CustomFieldsValuesGetterTrait
{
    /**
     * @param int $fieldId
     *
     * @return mixed|null
     */
    public function getPlainValueFromCustomField(int $fieldId)
    {
        if (!$this->hasCustomFields()) {
            throw new NotContainCustomFieldsException(sprintf('%s does not contain custom fields', self::class));
        }

        /** @var CustomFieldsValuesCollection|null $customFieldsValues */
        $customFieldsValues = $this->getCustomFieldsValues();

        if ($customFieldsValues !== null) {
            $valuesModel = $customFieldsValues->getBy('fieldId', $fieldId);
            $values = $valuesModel->getValues();

            if ($values !== null) {
                $valueModel = $values->first();

                if ($valueModel !== null) {
                    $value = $valueModel->toArray()['value'] ?? null;
                }
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
