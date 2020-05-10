<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Exceptions\InvalidArgumentException;

/**
 * Class BaseEntityTypeEntity
 *
 * @package AmoCRM\EntitiesServices
 */
abstract class BaseEntityTypeEntity extends BaseEntity
{
    protected $entityType = '';

    /**
     * @param string $entityType
     * @throws InvalidArgumentException
     */
    abstract protected function validateEntityType(string $entityType): void;

    /**
     * @param string $entityType
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setEntityType(string $entityType): self
    {
        $this->validateEntityType($entityType);
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityType(): string
    {
        return $this->entityType;
    }

    /**
     * @return string
     */
    protected function getMethod(): string
    {
        $method = parent::getMethod();

        return sprintf($method, $this->getEntityType());
    }
}
