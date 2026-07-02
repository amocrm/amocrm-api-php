<?php

namespace AmoCRM\Dwh\Models;

class CallDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $CallId;
    protected ?string $EntityType = null;
    protected ?int $EntityId = null;
    protected ?string $Phone = null;
    protected ?string $Direction = null;
    protected ?string $Status = null;
    protected ?string $Result = null;
    protected ?int $Duration = null;
    protected ?int $CallResponsibleUserId = null;
    protected ?string $Source = null;
    protected ?string $Note = null;
    protected ?string $CreatedAt = null;
    protected ?string $UpdatedAt = null;

    public static function getTableName(): string { return 'amocrm_calls'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'call_id' => $this->CallId,
            'entity_type' => $this->EntityType,
            'entity_id' => $this->EntityId,
            'phone' => $this->Phone,
            'direction' => $this->Direction,
            'status' => $this->Status,
            'result' => $this->Result,
            'duration' => $this->Duration,
            'call_responsible_user_id' => $this->CallResponsibleUserId,
            'source' => $this->Source,
            'note' => $this->Note,
            'created_at' => $this->CreatedAt,
            'updated_at' => $this->UpdatedAt,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getCallId(): int { return $this->CallId; }
    public function setCallId(int $v): self { $this->CallId = $v; return $this; }
    public function getEntityType(): ?string { return $this->EntityType; }
    public function setEntityType(?string $v): self { $this->EntityType = $v; return $this; }
    public function getEntityId(): ?int { return $this->EntityId; }
    public function setEntityId(?int $v): self { $this->EntityId = $v; return $this; }
    public function getPhone(): ?string { return $this->Phone; }
    public function setPhone(?string $v): self { $this->Phone = $v; return $this; }
    public function getDirection(): ?string { return $this->Direction; }
    public function setDirection(?string $v): self { $this->Direction = $v; return $this; }
    public function getStatus(): ?string { return $this->Status; }
    public function setStatus(?string $v): self { $this->Status = $v; return $this; }
    public function getResult(): ?string { return $this->Result; }
    public function setResult(?string $v): self { $this->Result = $v; return $this; }
    public function getDuration(): ?int { return $this->Duration; }
    public function setDuration(?int $v): self { $this->Duration = $v; return $this; }
    public function getCallResponsibleUserId(): ?int { return $this->CallResponsibleUserId; }
    public function setCallResponsibleUserId(?int $v): self { $this->CallResponsibleUserId = $v; return $this; }
    public function getSource(): ?string { return $this->Source; }
    public function setSource(?string $v): self { $this->Source = $v; return $this; }
    public function getNote(): ?string { return $this->Note; }
    public function setNote(?string $v): self { $this->Note = $v; return $this; }
    public function getCreatedAt(): ?string { return $this->CreatedAt; }
    public function setCreatedAt(?string $v): self { $this->CreatedAt = $v; return $this; }
    public function getUpdatedAt(): ?string { return $this->UpdatedAt; }
    public function setUpdatedAt(?string $v): self { $this->UpdatedAt = $v; return $this; }
}
