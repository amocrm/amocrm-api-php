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
     * @return string
     */
    public function getPlainValueFromCf(int $fieldId): ?string
    {
        if (!$this->hasCustomFields()) {
            throw new NotContainCustomFieldsException(sprintf('%s does not contain custom fields', self::class));
        }

        /** @var CustomFieldsValuesCollection|null $customFieldsValues */
        $customFieldsValues = $this->getCustomFieldsValues();

        if ($customFieldsValues !== null) {
            $valueModel = $customFieldsValues->getBy('fieldId', $fieldId);

            if ($valueModel && $values = $valueModel->getValues()->first()) {
                $result = $values->toArray();

                if (empty($result['value'])) {
                    $value = null;
                } else {
                    $value = (string)$result['value'];
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
