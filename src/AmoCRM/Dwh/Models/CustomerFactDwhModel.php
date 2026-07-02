<?php

namespace AmoCRM\Dwh\Models;

class CustomerFactDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $CustomerId;
    protected ?int $ContactId = null;
    protected ?int $CompanyId = null;
    protected ?int $PeriodicityId = null;
    protected ?int $ResponsibleUserId = null;
    protected ?string $NextDate = null;
    protected ?string $NextPrice = null;
    protected ?int $Purchases = null;
    protected ?string $AverageCheck = null;
    protected ?string $Ltv = null;
    protected ?string $LaborCost = null;

    public static function getTableName(): string { return 'amocrm_customers_facts'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'customer_id' => $this->CustomerId,
            'contact_id' => $this->ContactId,
            'company_id' => $this->CompanyId,
            'periodicity_id' => $this->PeriodicityId,
            'responsible_user_id' => $this->ResponsibleUserId,
            'next_date' => $this->NextDate,
            'next_price' => $this->NextPrice,
            'purchases' => $this->Purchases,
            'average_check' => $this->AverageCheck,
            'ltv' => $this->Ltv,
            'labor_cost' => $this->LaborCost,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getCustomerId(): int { return $this->CustomerId; }
    public function setCustomerId(int $v): self { $this->CustomerId = $v; return $this; }
    public function getContactId(): ?int { return $this->ContactId; }
    public function setContactId(?int $v): self { $this->ContactId = $v; return $this; }
    public function getCompanyId(): ?int { return $this->CompanyId; }
    public function setCompanyId(?int $v): self { $this->CompanyId = $v; return $this; }
    public function getPeriodicityId(): ?int { return $this->PeriodicityId; }
    public function setPeriodicityId(?int $v): self { $this->PeriodicityId = $v; return $this; }
    public function getResponsibleUserId(): ?int { return $this->ResponsibleUserId; }
    public function setResponsibleUserId(?int $v): self { $this->ResponsibleUserId = $v; return $this; }
    public function getNextDate(): ?string { return $this->NextDate; }
    public function setNextDate(?string $v): self { $this->NextDate = $v; return $this; }
    public function getNextPrice(): ?string { return $this->NextPrice; }
    public function setNextPrice(?string $v): self { $this->NextPrice = $v; return $this; }
    public function getPurchases(): ?int { return $this->Purchases; }
    public function setPurchases(?int $v): self { $this->Purchases = $v; return $this; }
    public function getAverageCheck(): ?string { return $this->AverageCheck; }
    public function setAverageCheck(?string $v): self { $this->AverageCheck = $v; return $this; }
    public function getLtv(): ?string { return $this->Ltv; }
    public function setLtv(?string $v): self { $this->Ltv = $v; return $this; }
    public function getLaborCost(): ?string { return $this->LaborCost; }
    public function setLaborCost(?string $v): self { $this->LaborCost = $v; return $this; }
}
