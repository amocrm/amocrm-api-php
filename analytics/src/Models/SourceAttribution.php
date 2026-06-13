<?php

declare(strict_types=1);

namespace Analytics\Models;

use Carbon\Carbon;

/**
 * Source attribution model
 */
class SourceAttribution extends BaseModel
{
    private ?int $id = null;
    private int $leadId;
    private ?int $unsortedId = null;
    private ?string $unsortedCategory = null;
    private ?int $sourceId = null;
    private ?string $sourceName = null;
    private ?string $sourceExternalId = null;
    private ?Carbon $firstTouchAt = null;
    private ?Carbon $lastTouchAt = null;
    private ?float $firstTouchValue = null;
    private ?float $lastTouchValue = null;
    private ?Carbon $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLeadId(): int
    {
        return $this->leadId;
    }

    public function setLeadId(int $leadId): self
    {
        $this->leadId = $leadId;
        return $this;
    }

    public function getUnsortedId(): ?int
    {
        return $this->unsortedId;
    }

    public function setUnsortedId(?int $unsortedId): self
    {
        $this->unsortedId = $unsortedId;
        return $this;
    }

    public function getUnsortedCategory(): ?string
    {
        return $this->unsortedCategory;
    }

    public function setUnsortedCategory(?string $unsortedCategory): self
    {
        $this->unsortedCategory = $unsortedCategory;
        return $this;
    }

    public function getSourceId(): ?int
    {
        return $this->sourceId;
    }

    public function setSourceId(?int $sourceId): self
    {
        $this->sourceId = $sourceId;
        return $this;
    }

    public function getSourceName(): ?string
    {
        return $this->sourceName;
    }

    public function setSourceName(?string $sourceName): self
    {
        $this->sourceName = $sourceName;
        return $this;
    }

    public function getSourceExternalId(): ?string
    {
        return $this->sourceExternalId;
    }

    public function setSourceExternalId(?string $sourceExternalId): self
    {
        $this->sourceExternalId = $sourceExternalId;
        return $this;
    }

    public function getFirstTouchAt(): ?Carbon
    {
        return $this->firstTouchAt;
    }

    public function setFirstTouchAt(?Carbon $firstTouchAt): self
    {
        $this->firstTouchAt = $firstTouchAt;
        return $this;
    }

    public function getLastTouchAt(): ?Carbon
    {
        return $this->lastTouchAt;
    }

    public function setLastTouchAt(?Carbon $lastTouchAt): self
    {
        $this->lastTouchAt = $lastTouchAt;
        return $this;
    }

    public function getFirstTouchValue(): ?float
    {
        return $this->firstTouchValue;
    }

    public function setFirstTouchValue(?float $firstTouchValue): self
    {
        $this->firstTouchValue = $firstTouchValue;
        return $this;
    }

    public function getLastTouchValue(): ?float
    {
        return $this->lastTouchValue;
    }

    public function setLastTouchValue(?float $lastTouchValue): self
    {
        $this->lastTouchValue = $lastTouchValue;
        return $this;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?Carbon $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'lead_id' => $this->leadId,
            'unsorted_id' => $this->unsortedId,
            'unsorted_category' => $this->unsortedCategory,
            'source_id' => $this->sourceId,
            'source_name' => $this->sourceName,
            'source_external_id' => $this->sourceExternalId,
            'first_touch_at' => $this->firstTouchAt?->toIso8601String(),
            'last_touch_at' => $this->lastTouchAt?->toIso8601String(),
            'first_touch_value' => $this->firstTouchValue,
            'last_touch_value' => $this->lastTouchValue,
            'created_at' => $this->createdAt?->toIso8601String(),
        ];
    }

    public static function fromArray(array $data): static
    {
        $attr = new static();

        $attr->id = $data['id'] ?? null;
        $attr->leadId = (int)$data['lead_id'];

        if (isset($data['unsorted_id'])) {
            $attr->unsortedId = (int)$data['unsorted_id'];
        }

        $attr->unsortedCategory = $data['unsorted_category'] ?? null;
        $attr->sourceId = isset($data['source_id']) ? (int)$data['source_id'] : null;
        $attr->sourceName = $data['source_name'] ?? null;
        $attr->sourceExternalId = $data['source_external_id'] ?? null;
        $attr->firstTouchValue = isset($data['first_touch_value']) ? (float)$data['first_touch_value'] : null;
        $attr->lastTouchValue = isset($data['last_touch_value']) ? (float)$data['last_touch_value'] : null;

        if (isset($data['first_touch_at'])) {
            $attr->firstTouchAt = $data['first_touch_at'] instanceof Carbon
                ? $data['first_touch_at']
                : Carbon::parse($data['first_touch_at']);
        }

        if (isset($data['last_touch_at'])) {
            $attr->lastTouchAt = $data['last_touch_at'] instanceof Carbon
                ? $data['last_touch_at']
                : Carbon::parse($data['last_touch_at']);
        }

        if (isset($data['created_at'])) {
            $attr->createdAt = $data['created_at'] instanceof Carbon
                ? $data['created_at']
                : Carbon::parse($data['created_at']);
        }

        return $attr;
    }
}
