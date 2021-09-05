<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

/**
 * Class BaseEnumCodeCustomFieldValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 */
class BaseEnumCodeCustomFieldValueModel extends BaseCustomFieldValueModel
{
    /**
     * @var string|null
     */
    protected $enumCode;

    /**
     * @var int|null
     */
    protected $enumId;

    /**
     * @param array $value
     *
     * @return BaseCustomFieldValueModel
     */
    public static function fromArray($value): BaseCustomFieldValueModel
    {
        $model = new static();

        $enumId = isset($value['enum_id']) ? (int)$value['enum_id'] : null;
        $enumCode = $value['enum_code'] ?? null;
        $fieldValue = $value['value'] ?? null;

        $model
            ->setValue($fieldValue)
            ->setEnumId($enumId)
            ->setEnumCode($enumCode);

        return $model;
    }

    /**
     * @return string|null
     */
    public function getEnumCode(): ?string
    {
        return $this->enumCode;
    }

    /**
     * @param string|null $enumCode
     *
     * @return BaseEnumCodeCustomFieldValueModel
     */
    public function setEnumCode(?string $enumCode): BaseEnumCodeCustomFieldValueModel
    {
        $this->enumCode = $enumCode;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getEnumId(): ?int
    {
        return $this->enumId;
    }

    /**
     * @param int|null $enumId
     *
     * @return BaseEnumCodeCustomFieldValueModel
     */
    public function setEnumId(?int $enumId): BaseEnumCodeCustomFieldValueModel
    {
        $this->enumId = $enumId;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'value' => $this->getValue(),
            'enum_id' => $this->getEnumId(),
            'enum_code' => $this->getEnumCode(),
        ];
    }

    public function toApi(string $requestId = null): array
    {
        return [
            'value' => $this->getValue(),
            'enum_id' => $this->getEnumId(),
            'enum_code' => $this->getEnumCode(),
        ];
    }
}
