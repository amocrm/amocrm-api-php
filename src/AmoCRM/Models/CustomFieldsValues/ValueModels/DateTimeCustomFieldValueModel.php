<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

/**
 * Class DateTimeCustomFieldValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 */
class DateTimeCustomFieldValueModel extends DateCustomFieldValueModel
{
    /**
     * @param string|null $requestId
     *
     * @return array
     */
    public function toApi(string $requestId = null): array
    {
        return [
            'value' => date('Y-m-d H:i:s', $this->value),
        ];
    }
}
