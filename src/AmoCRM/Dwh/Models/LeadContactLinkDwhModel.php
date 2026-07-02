<?php

namespace AmoCRM\Dwh\Models;

class LeadContactLinkDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $LeadId;
    protected int $ContactId;
    protected int $IsMain;

    public static function getTableName(): string { return 'amocrm_leads_contacts'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'lead_id' => $this->LeadId,
            'contact_id' => $this->ContactId,
            'is_main' => $this->IsMain,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getLeadId(): int { return $this->LeadId; }
    public function setLeadId(int $v): self { $this->LeadId = $v; return $this; }
    public function getContactId(): int { return $this->ContactId; }
    public function setContactId(int $v): self { $this->ContactId = $v; return $this; }
    public function getIsMain(): int { return $this->IsMain; }
    public function setIsMain(int $v): self { $this->IsMain = $v; return $this; }
}
