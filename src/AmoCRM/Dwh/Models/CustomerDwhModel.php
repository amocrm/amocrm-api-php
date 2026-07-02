<?php

namespace AmoCRM\Dwh\Models;

class CustomerDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $CustomerId;
    protected ?string $Name = null;
    protected ?string $ContactName = null;
    protected ?string $CompanyName = null;
    protected ?int $ContactId = null;
    protected ?int $CompanyId = null;
    protected ?int $ResponsibleUserId = null;
    protected ?int $StatusId = null;
    protected ?int $PeriodicityId = null;
    protected ?int $PeriodId = null;
    protected ?string $NextPrice = null;
    protected ?string $Ltv = null;
    protected ?int $Purchases = null;
    protected ?string $AverageCheck = null;
    protected ?string $NextDate = null;
    protected int $IsDeleted;
    protected ?int $CreatedUserId = null;
    protected ?int $ModifiedUserId = null;
    protected ?string $CreatedAt = null;
    protected ?string $UpdatedAt = null;

    public static function getTableName(): string { return 'amocrm_customers'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'customer_id' => $this->CustomerId,
            'name' => $this->Name,
            'contact_name' => $this->ContactName,
            'company_name' => $this->CompanyName,
            'contact_id' => $this->ContactId,
            'company_id' => $this->CompanyId,
            'responsible_user_id' => $this->ResponsibleUserId,
            'status_id' => $this->StatusId,
            'periodicity_id' => $this->PeriodicityId,
            'period_id' => $this->PeriodId,
            'next_price' => $this->NextPrice,
            'ltv' => $this->Ltv,
            'purchases' => $this->Purchases,
            'average_check' => $this->AverageCheck,
            'next_date' => $this->NextDate,
            'is_deleted' => $this->IsDeleted,
            'created_user_id' => $this->CreatedUserId,
            'modified_user_id' => $this->ModifiedUserId,
            'created_at' => $this->CreatedAt,
            'updated_at' => $this->UpdatedAt,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getCustomerId(): int { return $this->CustomerId; }
    public function setCustomerId(int $v): self { $this->CustomerId = $v; return $this; }
    public function getName(): ?string { return $this->Name; }
    public function setName(?string $v): self { $this->Name = $v; return $this; }
    public function getContactName(): ?string { return $this->ContactName; }
    public function setContactName(?string $v): self { $this->ContactName = $v; return $this; }
    public function getCompanyName(): ?string { return $this->CompanyName; }
    public function setCompanyName(?string $v): self { $this->CompanyName = $v; return $this; }
    public function getContactId(): ?int { return $this->ContactId; }
    public function setContactId(?int $v): self { $this->ContactId = $v; return $this; }
    public function getCompanyId(): ?int { return $this->CompanyId; }
    public function setCompanyId(?int $v): self { $this->CompanyId = $v; return $this; }
    public function getResponsibleUserId(): ?int { return $this->ResponsibleUserId; }
    public function setResponsibleUserId(?int $v): self { $this->ResponsibleUserId = $v; return $this; }
    public function getStatusId(): ?int { return $this->StatusId; }
    public function setStatusId(?int $v): self { $this->StatusId = $v; return $this; }
    public function getPeriodicityId(): ?int { return $this->PeriodicityId; }
    public function setPeriodicityId(?int $v): self { $this->PeriodicityId = $v; return $this; }
    public function getPeriodId(): ?int { return $this->PeriodId; }
    public function setPeriodId(?int $v): self { $this->PeriodId = $v; return $this; }
    public function getNextPrice(): ?string { return $this->NextPrice; }
    public function setNextPrice(?string $v): self { $this->NextPrice = $v; return $this; }
    public function getLtv(): ?string { return $this->Ltv; }
    public function setLtv(?string $v): self { $this->Ltv = $v; return $this; }
    public function getPurchases(): ?int { return $this->Purchases; }
    public function setPurchases(?int $v): self { $this->Purchases = $v; return $this; }
    public function getAverageCheck(): ?string { return $this->AverageCheck; }
    public function setAverageCheck(?string $v): self { $this->AverageCheck = $v; return $this; }
    public function getNextDate(): ?string { return $this->NextDate; }
    public function setNextDate(?string $v): self { $this->NextDate = $v; return $this; }
    public function getIsDeleted(): int { return $this->IsDeleted; }
    public function setIsDeleted(int $v): self { $this->IsDeleted = $v; return $this; }
    public function getCreatedUserId(): ?int { return $this->CreatedUserId; }
    public function setCreatedUserId(?int $v): self { $this->CreatedUserId = $v; return $this; }
    public function getModifiedUserId(): ?int { return $this->ModifiedUserId; }
    public function setModifiedUserId(?int $v): self { $this->ModifiedUserId = $v; return $this; }
    public function getCreatedAt(): ?string { return $this->CreatedAt; }
    public function setCreatedAt(?string $v): self { $this->CreatedAt = $v; return $this; }
    public function getUpdatedAt(): ?string { return $this->UpdatedAt; }
    public function setUpdatedAt(?string $v): self { $this->UpdatedAt = $v; return $this; }
}
