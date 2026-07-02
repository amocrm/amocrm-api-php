<?php

namespace AmoCRM\Dwh\Models;

class CallFactDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $CallId;
    protected ?int $DateId = null;
    protected ?string $EntityType = null;
    protected ?int $EntityId = null;
    protected ?int $UserId = null;
    protected ?int $Duration = null;
    protected ?string $Status = null;

    public static function getTableName(): string { return 'amocrm_calls_facts'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'call_id' => $this->CallId,
            'date_id' => $this->DateId,
            'entity_type' => $this->EntityType,
            'entity_id' => $this->EntityId,
            'user_id' => $this->UserId,
            'duration' => $this->Duration,
            'status' => $this->Status,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getCallId(): int { return $this->CallId; }
    public function setCallId(int $v): self { $this->CallId = $v; return $this; }
    public function getDateId(): ?int { return $this->DateId; }
    public function setDateId(?int $v): self { $this->DateId = $v; return $this; }
    public function getEntityType(): ?string { return $this->EntityType; }
    public function setEntityType(?string $v): self { $this->EntityType = $v; return $this; }
    public function getEntityId(): ?int { return $this->EntityId; }
    public function setEntityId(?int $v): self { $this->EntityId = $v; return $this; }
    public function getUserId(): ?int { return $this->UserId; }
    public function setUserId(?int $v): self { $this->UserId = $v; return $this; }
    public function getDuration(): ?int { return $this->Duration; }
    public function setDuration(?int $v): self { $this->Duration = $v; return $this; }
    public function getStatus(): ?string { return $this->Status; }
    public function setStatus(?string $v): self { $this->Status = $v; return $this; }
}
