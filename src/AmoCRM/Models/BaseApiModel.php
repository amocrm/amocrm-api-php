<?php

namespace AmoCRM\Models;

use Illuminate\Support\Str;

use function is_callable;

abstract class BaseApiModel
{
    /**
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * @return array
     */
    public static function getAvailableWith(): array
    {
        return [];
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    abstract public function toApi(string $requestId = null): array;

    public function __get($name)
    {
        $methodName = 'get' . Str::camel(Str::ucfirst($name));
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        } else {
            return null;
        }
    }

    public function __set($name, $value)
    {
        $methodName = 'set' . Str::camel(Str::ucfirst($name));
        if (method_exists($this, $methodName) && is_callable([$this, $methodName])) {
            $this->$methodName($value);
        }
    }
}
