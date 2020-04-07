<?php

namespace AmoCRM\Models\CustomFieldsValues;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class BaseEnumCustomFieldsValue extends BaseCustomFieldsValue
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var int
     */
    protected $enumId;

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = [
            'value' => $this->getValue(),
            'enum_id' => $this->getEnumId(),
        ];

        return $result;
    }

    /**
     * @return int
     */
    public function getEnumId(): int
    {
        return $this->enumId;
    }

    /**
     * @param int $enumId
     * @return BaseEnumCustomFieldsValue
     */
    public function setEnumId(int $enumId): self
    {
        $this->enumId = $enumId;

        return $this;
    }

    /**
     * @return array
     */
    public function toOldApi(): array
    {
        $result = [
            'value' => $this->getValue(),
            'enum' => $this->getEnumId(),
        ];

        return $result;
    }
}
