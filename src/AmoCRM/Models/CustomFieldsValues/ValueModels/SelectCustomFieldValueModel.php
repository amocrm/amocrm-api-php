<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

/**
 * Class SelectCustomFieldValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 *
 * @method SelectCustomFieldValueModel fromArray($value)
 */
class SelectCustomFieldValueModel extends BaseEnumCodeCustomFieldValueModel
{
    public function toApi(string $requestId = null): array
    {
        // в приоритете всегда идентификатор, затем код - а уже после значение
        if ($this->hasEnumId()) {
            return [
                'enum_id' => $this->getEnumId(),
            ];
        }

        if ($this->hasEnumCode()) {
            return [
                'enum_code' => $this->getEnumCode(),
            ];
        }

        return [
            'value' => $this->getValue(),
        ];
    }
}
