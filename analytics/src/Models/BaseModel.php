<?php

declare(strict_types=1);

namespace Analytics\Models;

use JsonSerializable;

/**
 * Base model for all analytics entities
 */
abstract class BaseModel implements JsonSerializable
{
    /**
     * Convert model to array for internal use
     */
    abstract public function toArray(): array;

    /**
     * Convert model to API response format
     */
    public function toApi(?string $requestId = null): array
    {
        return $this->toArray();
    }

    /**
     * Create model from array
     */
    abstract public static function fromArray(array $data): static;

    /**
     * Get entity ID
     */
    abstract public function getId(): ?int;

    /**
     * Get available "with" parameters for API queries
     */
    public static function getAvailableWith(): array
    {
        return [];
    }

    /**
     * JSON serialization
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Magic getter for properties
     */
    public function __get(string $name): mixed
    {
        $method = 'get' . str_replace('_', '', ucwords($name, '_'));

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return null;
    }

    /**
     * Magic setter for properties
     */
    public function __set(string $name, mixed $value): void
    {
        $method = 'set' . str_replace('_', '', ucwords($name, '_'));

        if (method_exists($this, $method)) {
            $this->$method($value);
        }
    }
}
