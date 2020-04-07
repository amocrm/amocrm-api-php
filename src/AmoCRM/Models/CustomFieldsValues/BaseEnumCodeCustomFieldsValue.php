<?php

namespace AmoCRM\Models\CustomFieldsValues;

class BaseEnumCodeCustomFieldsValue extends BaseEnumCustomFieldsValue
{
    /**
     * @var string
     */
    protected $enumCode;

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = [
            'value' => $this->getValue(),
            'enum_id' => $this->getEnumId(),
            'enum_code' => $this->getEnumCode(),
        ];

        return $result;
    }

    /**
     * @return string
     */
    public function getEnumCode(): string
    {
        return $this->enumCode;
    }

    /**
     * @param string $enumCode
     * @return BaseEnumCodeCustomFieldsValue
     */
    public function setEnumCode(string $enumCode): self
    {
        $this->enumCode = $enumCode;

        return $this;
    }

    /**
     * @return array
     */
    public function toOldApi(): array
    {
        $result = [
            'value' => $this->getValue(),
            'subtype' => $this->getEnumCode(),
        ];

        return $result;
    }
}
