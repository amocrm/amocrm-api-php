<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

use AmoCRM\Models\BaseApiModel;

/**
 * Class BaseCustomFieldValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 */
class BaseCustomFieldValueModel extends BaseApiModel
{
    /**
     * @var int|string|null|array|bool
     */
    protected $value;

    /**
     * @param int|string|null $value
     *
     * @return BaseCustomFieldValueModel
     */
    public static function fromArray($value): BaseCustomFieldValueModel
    {
        $model = new self();

        $model
            ->setValue($value['value'] ?? null);

        return $model;
    }

    /**
     * @return int|string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int|string|null|array|bool $value
     *
     * @return BaseCustomFieldValueModel
     */
    public function setValue($value): BaseCustomFieldValueModel
    {
        $this->value = $value;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'value' => $this->getValue(),
        ];
    }

    public function toApi(string $requestId = null): array
    {
        return [
            'value' => $this->getValue(),
        ];
    }
}
