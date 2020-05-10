<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Exceptions\NotAvailableForActionException;

/**
 * Class BaseEntityIdEntity
 *
 * @package AmoCRM\EntitiesServices
 */
abstract class BaseEntityIdEntity extends BaseEntity
{
    /**
     * @var int
     */
    protected $entityId = 0;

    /**
     * @param int $entityId
     *
     * @throws NotAvailableForActionException
     */
    protected function validateEntityId(int $entityId): void
    {
        if ($entityId < 0) {
            throw new NotAvailableForActionException('Invalid entity id');
        }
    }

    /**
     * @param int $entityId
     * @return $this
     */
    public function setEntityId(int $entityId): self
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->entityId;
    }

    /**
     * @return string
     */
    protected function getMethod(): string
    {
        $method = parent::getMethod();

        return sprintf($method, $this->getEntityId());
    }
}
