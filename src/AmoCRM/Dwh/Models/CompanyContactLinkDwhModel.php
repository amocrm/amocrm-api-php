<?php

namespace AmoCRM\Dwh\Models;

class CompanyContactLinkDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $CompanyId;
    protected int $ContactId;
    protected int $IsMain;

    public static function getTableName(): string { return 'amocrm_companies_contacts'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'company_id' => $this->CompanyId,
            'contact_id' => $this->ContactId,
            'is_main' => $this->IsMain,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getCompanyId(): int { return $this->CompanyId; }
    public function setCompanyId(int $v): self { $this->CompanyId = $v; return $this; }
    public function getContactId(): int { return $this->ContactId; }
    public function setContactId(int $v): self { $this->ContactId = $v; return $this; }
    public function getIsMain(): int { return $this->IsMain; }
    public function setIsMain(int $v): self { $this->IsMain = $v; return $this; }
}
