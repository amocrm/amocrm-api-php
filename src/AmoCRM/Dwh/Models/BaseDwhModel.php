<?php

namespace AmoCRM\Dwh\Models;

use AmoCRM\Support\Str;

use function is_callable;

abstract class BaseDwhModel
{
    abstract public function toArray(): array;

    abstract public static function getTableName(): string;

    public static function fromArray(array $data): self
    {
        $model = new static();
        foreach ($data as $key => $value) {
            $model->$key = $value;
        }

        return $model;
    }

    public function __get($name)
    {
        $methodName = 'get' . Str::camel(Str::ucfirst($name));
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        return null;
    }

    public function __set($name, $value)
    {
        $methodName = 'set' . Str::camel(Str::ucfirst($name));
        if (method_exists($this, $methodName) && is_callable([$this, $methodName])) {
            $this->$methodName($value);
        }
    }

    public function toInsertArray(): array
    {
        $data = $this->toArray();
        unset($data['id']);

        return $data;
    }
}
