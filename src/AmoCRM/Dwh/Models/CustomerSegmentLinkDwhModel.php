<?php

namespace AmoCRM\Dwh\Models;

class CustomerSegmentLinkDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $CustomerId;
    protected int $SegmentId;

    public static function getTableName(): string { return 'amocrm_customers_segments'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'customer_id' => $this->CustomerId,
            'segment_id' => $this->SegmentId,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getCustomerId(): int { return $this->CustomerId; }
    public function setCustomerId(int $v): self { $this->CustomerId = $v; return $this; }
    public function getSegmentId(): int { return $this->SegmentId; }
    public function setSegmentId(int $v): self { $this->SegmentId = $v; return $this; }
}
