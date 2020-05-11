<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

/**
 * Class BaseEnumCustomFieldValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 */
class BaseEnumCustomFieldValueModel extends BaseCustomFieldValueModel
{
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
        $model = new self();

        $enumId = isset($value['enum_id']) ? (int)$value['enum_id'] : null;
        $value = isset($value['value']) ? $value['value'] : null;

        $model
            ->setValue($value)
            ->setEnumId($enumId);

        return $model;
    }

    /**
     * @return int|null
     */
    public function getEnumId(): ?int
    {
        return $this->enumId;
    }

    /**
     * @param null|int $enumId
     *
     * @return BaseEnumCustomFieldValueModel
     */
    public function setEnumId(?int $enumId): BaseEnumCustomFieldValueModel
    {
        $this->enumId = $enumId;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'value' => $this->getValue(),
            'enum_id' => $this->getEnumId(),
        ];
    }

    public function toApi(string $requestId = null): array
    {
        return [
            'value' => $this->getValue(),
            'enum_id' => $this->getEnumId(),
        ];
    }
}
