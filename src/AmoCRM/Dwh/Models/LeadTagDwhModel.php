<?php

namespace AmoCRM\Dwh\Models;

class LeadTagDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $LeadId;
    protected ?int $TagId = null;
    protected string $Name;
    protected ?string $Color = null;

    public static function getTableName(): string { return 'amocrm_leads_tags'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'lead_id' => $this->LeadId,
            'tag_id' => $this->TagId,
            'name' => $this->Name,
            'color' => $this->Color,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getLeadId(): int { return $this->LeadId; }
    public function setLeadId(int $v): self { $this->LeadId = $v; return $this; }
    public function getTagId(): ?int { return $this->TagId; }
    public function setTagId(?int $v): self { $this->TagId = $v; return $this; }
    public function getName(): string { return $this->Name; }
    public function setName(string $v): self { $this->Name = $v; return $this; }
    public function getColor(): ?string { return $this->Color; }
    public function setColor(?string $v): self { $this->Color = $v; return $this; }
}
