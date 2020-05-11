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
     *
     * @return string
     * @throws InvalidArgumentException
     */
    abstract protected function validateEntityType(string $entityType): string;

    /**
     * @param string $entityType
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setEntityType(string $entityType): self
    {
        $entityType = $this->validateEntityType($entityType);
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
