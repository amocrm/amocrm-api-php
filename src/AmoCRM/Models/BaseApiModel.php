<?php

namespace AmoCRM\Models;

use AmoCRM\Support\Str;

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
    abstract public function toApi(?string $requestId = null): array;

    public function __get($name)
    {
        $methodName = 'get' . Str::camel(Str::ucfirst($name));
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        } else {
            return null;
        }
    }

    public function __set($name, $value): void
    {
        $methodName = 'set' . Str::camel(Str::ucfirst($name));
        
        if (method_exists($this, $methodName) && is_callable([$this, $methodName])) 
        {
            $method = new \ReflectionMethod($this, $methodName);
            $params = $method->getParameters();
            $param  = isset($params[0]) ? $params[0] : null;

            $type = null;
            if ($param !== null && method_exists($param, 'getType')) 
            {
                $type = $param->getType();
            }
            
            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) 
            {
                $class = $type->getName();

                if (is_array($value) && method_exists($class, 'fromArray')) 
                {
                    $value = $class::fromArray($value);
                }
            }

            $this->$methodName($value);
        }
    }
}
