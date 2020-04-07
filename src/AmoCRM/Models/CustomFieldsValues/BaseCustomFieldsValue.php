<?php

namespace AmoCRM\Models\CustomFieldsValues;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class BaseCustomFieldsValue implements \ArrayAccess, Arrayable, Jsonable
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        $value = null;

        if ($this->offsetExists($offset)) {
            $value = $this->$offset;
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        // will not be implemented
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        // will not be implemented
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = [
            'value' => $this->getValue(),
        ];

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @return array
     */
    public function toOldApi(): array
    {
        $result = [
            'value' => $this->getValue(),
        ];

        return $result;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }
}
