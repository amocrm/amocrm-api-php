<?php

namespace AmoCRM\Dwh\Models;

class ContactFactDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $ContactId;
    protected ?int $DateId = null;
    protected ?int $CreatedUserId = null;
    protected ?int $ResponsibleUserId = null;
    protected ?int $Score = null;

    public static function getTableName(): string { return 'amocrm_contacts_facts'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'contact_id' => $this->ContactId,
            'date_id' => $this->DateId,
            'created_user_id' => $this->CreatedUserId,
            'responsible_user_id' => $this->ResponsibleUserId,
            'score' => $this->Score,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getContactId(): int { return $this->ContactId; }
    public function setContactId(int $v): self { $this->ContactId = $v; return $this; }
    public function getDateId(): ?int { return $this->DateId; }
    public function setDateId(?int $v): self { $this->DateId = $v; return $this; }
    public function getCreatedUserId(): ?int { return $this->CreatedUserId; }
    public function setCreatedUserId(?int $v): self { $this->CreatedUserId = $v; return $this; }
    public function getResponsibleUserId(): ?int { return $this->ResponsibleUserId; }
    public function setResponsibleUserId(?int $v): self { $this->ResponsibleUserId = $v; return $this; }
    public function getScore(): ?int { return $this->Score; }
    public function setScore(?int $v): self { $this->Score = $v; return $this; }
}
