<?php

namespace AmoCRM\Models;

use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\Factories\EntityFactory;

/**
 * Class EventModel
 *
 * @package AmoCRM\Models
 */
class EventModel extends BaseApiModel
{
    public const CONTACT_NAME = 'contact_name';
    public const LEAD_NAME = 'lead_name';
    public const COMPANY_NAME = 'company_name';
    public const CATALOG_ELEMENT_NAME = 'catalog_element_name';
    public const CUSTOMER_NAME = 'customer_name';
    public const CATALOG_NAME = 'catalog_name';
    public const NOTE = 'note';

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $entityId;

    /**
     * @var string
     */
    protected $entityType;

    /**
     * @var int
     */
    protected $createdBy;

    /**
     * @var int
     */
    protected $createdAt;

    /**
     * @var array
     */
    protected $valueAfter;

    /**
     * @var array
     */
    protected $valueBefore;

    /**
     * @var int
     */
    protected $accountId;

    /**
     * @var BaseApiModel|null
     */
    protected $entity;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return EventModel
     */
    public function setId(string $id): EventModel
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return EventModel
     */
    public function setType(string $type): EventModel
    {
        $this->type = $type;

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
     * @param int $entityId
     * @return EventModel
     */
    public function setEntityId(int $entityId): EventModel
    {
        $this->entityId = $entityId;

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
     * @param string $entityType
     * @return EventModel
     */
    public function setEntityType(string $entityType): EventModel
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * @return int
     */
    public function getCreatedBy(): int
    {
        return $this->createdBy;
    }

    /**
     * @param int $createdBy
     * @return EventModel
     */
    public function setCreatedBy(int $createdBy): EventModel
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @param int $createdAt
     * @return EventModel
     */
    public function setCreatedAt(int $createdAt): EventModel
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return array
     */
    public function getValueAfter(): array
    {
        return $this->valueAfter;
    }

    /**
     * @param array $valueAfter
     * @return EventModel
     */
    public function setValueAfter(array $valueAfter): EventModel
    {
        $this->valueAfter = $valueAfter;

        return $this;
    }

    /**
     * @return array
     */
    public function getValueBefore(): array
    {
        return $this->valueBefore;
    }

    /**
     * @param array $valueBefore
     * @return EventModel
     */
    public function setValueBefore(array $valueBefore): EventModel
    {
        $this->valueBefore = $valueBefore;

        return $this;
    }

    /**
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     * @return EventModel
     */
    public function setAccountId(int $accountId): EventModel
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return null|BaseApiModel
     */
    public function getEntity(): ?BaseApiModel
    {
        return $this->entity;
    }

    /**
     * @param BaseApiModel|null $entity
     *
     * @return EventModel
     */
    public function setEntity(?BaseApiModel $entity): EventModel
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @param array $event
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $event): self
    {
        if (empty($event['id'])) {
            throw new InvalidArgumentException('Event id is empty in ' . json_encode($event));
        }

        $eventModel = new self();

        $eventModel->setId($event['id'])
            ->setType($event['type'])
            ->setEntityId($event['entity_id'])
            ->setEntityType($event['entity_type'])
            ->setCreatedBy((int)$event['created_by'])
            ->setCreatedAt((int)$event['created_at'])
            ->setAccountId($event['account_id'])
            ->setValueBefore($event['value_before'])
            ->setValueAfter($event['value_after']);

        $entityModel = null;
        if (isset($event[AmoCRMApiRequest::EMBEDDED]['entity'])) {
            $entityModel = EntityFactory::createForType($event['entity_type'], $event[AmoCRMApiRequest::EMBEDDED]['entity']);
        }

        $eventModel->setEntity($entityModel);

        return $eventModel;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType(),
            'entity_id' => $this->getEntityId(),
            'entity_type' => $this->getEntityType(),
            'created_by' => $this->getCreatedBy(),
            'created_at' => $this->getCreatedAt(),
            'value_after' => $this->getValueAfter(),
            'value_before' => $this->getValueBefore(),
            'account_id' => $this->getAccountId(),
        ];
    }

    public function toApi(?string $requestId = "0"): array
    {
        return [];
    }

    /**
     * @return array
     */
    public static function getAvailableWith(): array
    {
        return [
            self::CONTACT_NAME,
            self::LEAD_NAME,
            self::COMPANY_NAME,
            self::CATALOG_ELEMENT_NAME,
            self::CUSTOMER_NAME,
            self::CATALOG_NAME,
            self::NOTE,
        ];
    }
}
