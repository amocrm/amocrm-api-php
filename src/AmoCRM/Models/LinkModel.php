<?php

namespace AmoCRM\Models;

class LinkModel extends BaseApiModel
{
    /** @var int|null */
    protected $entityId;

    /** @var string|null */
    protected $entityType;

    /**
     * @var int|null
     */
    protected $toEntityId;

    /**
     * @var null|string
     */
    protected $toEntityType;

    /**
     * @var array|null
     */
    protected $metadata;

    /**
     * @return int|null
     */
    public function getToEntityId(): ?int
    {
        return $this->toEntityId;
    }

    /**
     * @param int|null $toEntityId
     *
     * @return LinkModel
     */
    public function setToEntityId(?int $toEntityId): LinkModel
    {
        $this->toEntityId = $toEntityId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getToEntityType(): ?string
    {
        return $this->toEntityType;
    }

    /**
     * @param string|null $toEntityType
     *
     * @return LinkModel
     */
    public function setToEntityType(?string $toEntityType): LinkModel
    {
        $this->toEntityType = $toEntityType;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    /**
     * @param array|null $metadata
     *
     * @return LinkModel
     */
    public function setMetadata(?array $metadata): LinkModel
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    public function setEntityId(?int $entityId): self
    {
        $this->entityId = $entityId;

        return $this;
    }

    public function getEntityType(): ?string
    {
        return $this->entityType;
    }

    public function setEntityType(?string $entityType): self
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * @param array $link
     *
     * @return self
     */
    public static function fromArray(array $link): self
    {
        $model = new self();

        $model
            ->setEntityId($link['entity_id'] ?? null)
            ->setEntityType($link['entity_type'] ?? null)
            ->setToEntityType($link['to_entity_type'] ?? null)
            ->setToEntityId($link['to_entity_id'] ?? null)
            ->setMetadata($link['metadata'] ?? null);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'entity_type' => $this->getEntityType(),
            'entity_id' => $this->getEntityId(),
            'to_entity_type' => $this->getToEntityType(),
            'to_entity_id' => $this->getToEntityId(),
            'metadata' => $this->getMetadata(),
        ];
    }

    public function toApi(?string $requestId = "0"): array
    {
        return $this->toArray();
    }
}
