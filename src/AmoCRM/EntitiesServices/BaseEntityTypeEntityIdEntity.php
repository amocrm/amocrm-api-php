<?php

declare(strict_types=1);

namespace AmoCRM\EntitiesServices;

use AmoCRM\Exceptions\InvalidArgumentException;

/**
 * Class BaseEntityTypeEntityIdEntity
 *
 * @package AmoCRM\EntitiesServices
 */
abstract class BaseEntityTypeEntityIdEntity extends BaseEntityIdEntity
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
        return sprintf($this->method, $this->getEntityType(), $this->getEntityId());
    }
}
