<?php

namespace AmoCRM\Models;

class LinkModel extends BaseApiModel
{
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

    /**
     * @param array $link
     *
     * @return self
     */
    public static function fromArray(array $link): self
    {
        $model = new self();

        $model->setToEntityType($link['to_entity_type'] ?? null)
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
