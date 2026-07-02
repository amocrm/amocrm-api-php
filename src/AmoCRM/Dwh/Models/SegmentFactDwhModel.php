<?php

namespace AmoCRM\Dwh\Models;

class SegmentFactDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $SegmentId;
    protected ?int $CreatedUserId = null;
    protected ?int $ModifiedUserId = null;
    protected ?int $CustomersCount = null;
    protected ?string $ConversionRate = null;
    protected ?string $MaxDiscount = null;
    protected ?string $CreatedAt = null;
    protected ?string $UpdatedAt = null;

    public static function getTableName(): string { return 'amocrm_segments_facts'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'segment_id' => $this->SegmentId,
            'created_user_id' => $this->CreatedUserId,
            'modified_user_id' => $this->ModifiedUserId,
            'customers_count' => $this->CustomersCount,
            'conversion_rate' => $this->ConversionRate,
            'max_discount' => $this->MaxDiscount,
            'created_at' => $this->CreatedAt,
            'updated_at' => $this->UpdatedAt,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getSegmentId(): int { return $this->SegmentId; }
    public function setSegmentId(int $v): self { $this->SegmentId = $v; return $this; }
    public function getCreatedUserId(): ?int { return $this->CreatedUserId; }
    public function setCreatedUserId(?int $v): self { $this->CreatedUserId = $v; return $this; }
    public function getModifiedUserId(): ?int { return $this->ModifiedUserId; }
    public function setModifiedUserId(?int $v): self { $this->ModifiedUserId = $v; return $this; }
    public function getCustomersCount(): ?int { return $this->CustomersCount; }
    public function setCustomersCount(?int $v): self { $this->CustomersCount = $v; return $this; }
    public function getConversionRate(): ?string { return $this->ConversionRate; }
    public function setConversionRate(?string $v): self { $this->ConversionRate = $v; return $this; }
    public function getMaxDiscount(): ?string { return $this->MaxDiscount; }
    public function setMaxDiscount(?string $v): self { $this->MaxDiscount = $v; return $this; }
    public function getCreatedAt(): ?string { return $this->CreatedAt; }
    public function setCreatedAt(?string $v): self { $this->CreatedAt = $v; return $this; }
    public function getUpdatedAt(): ?string { return $this->UpdatedAt; }
    public function setUpdatedAt(?string $v): self { $this->UpdatedAt = $v; return $this; }
}
