<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

/**
 * Class MultitextCustomFieldValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 */
class MultitextCustomFieldValueModel extends BaseEnumCustomFieldValueModel
{
    /**
     * @var string
     */
    protected $enum;

    /**
     * @return null|string
     */
    public function getEnum(): ?string
    {
        return $this->enum;
    }

    /**
     * @param string $enum
     *
     * @return MultitextCustomFieldValueModel
     */
    public function setEnum(string $enum): MultitextCustomFieldValueModel
    {
        $this->enum = $enum;

        return $this;
    }

    /**
     * @param array $value
     *
     * @return BaseCustomFieldValueModel
     */
    public static function fromArray($value): BaseCustomFieldValueModel
    {
        $model = new self();

        $enumId = isset($value['enum_id']) ? (int)$value['enum_id'] : null;
        $enum = isset($value['enum_code']) ? (string)$value['enum_code'] : null;
        $value = isset($value['value']) ? $value['value'] : null;

        $model
            ->setValue($value)
            ->setEnumId($enumId)
            ->setEnum($enum);

        return $model;
    }

    public function toApi(string $requestId = null): array
    {
        return [
            'value' => $this->getValue(),
            'enum_id' => $this->getEnumId(),
            'enum_code' => $this->getEnum(),
        ];
    }

    public function toArray(): array
    {
        return [
            'value' => $this->getValue(),
            'enum_id' => $this->getEnumId(),
            'enum_code' => $this->getEnum(),
        ];
    }
}
