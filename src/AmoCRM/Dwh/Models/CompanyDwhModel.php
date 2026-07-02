<?php

namespace AmoCRM\Dwh\Models;

class CompanyDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $CompanyId;
    protected ?string $Name = null;
    protected ?int $ResponsibleUserId = null;
    protected ?int $GroupId = null;
    protected ?int $CreatedUserId = null;
    protected ?int $ModifiedUserId = null;
    protected ?string $ClosestTaskAt = null;
    protected ?int $Score = null;
    protected int $IsDeleted;
    protected ?string $CreatedAt = null;
    protected ?string $UpdatedAt = null;

    public static function getTableName(): string { return 'amocrm_companies'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'company_id' => $this->CompanyId,
            'name' => $this->Name,
            'responsible_user_id' => $this->ResponsibleUserId,
            'group_id' => $this->GroupId,
            'created_user_id' => $this->CreatedUserId,
            'modified_user_id' => $this->ModifiedUserId,
            'closest_task_at' => $this->ClosestTaskAt,
            'score' => $this->Score,
            'is_deleted' => $this->IsDeleted,
            'created_at' => $this->CreatedAt,
            'updated_at' => $this->UpdatedAt,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getCompanyId(): int { return $this->CompanyId; }
    public function setCompanyId(int $v): self { $this->CompanyId = $v; return $this; }
    public function getName(): ?string { return $this->Name; }
    public function setName(?string $v): self { $this->Name = $v; return $this; }
    public function getResponsibleUserId(): ?int { return $this->ResponsibleUserId; }
    public function setResponsibleUserId(?int $v): self { $this->ResponsibleUserId = $v; return $this; }
    public function getGroupId(): ?int { return $this->GroupId; }
    public function setGroupId(?int $v): self { $this->GroupId = $v; return $this; }
    public function getCreatedUserId(): ?int { return $this->CreatedUserId; }
    public function setCreatedUserId(?int $v): self { $this->CreatedUserId = $v; return $this; }
    public function getModifiedUserId(): ?int { return $this->ModifiedUserId; }
    public function setModifiedUserId(?int $v): self { $this->ModifiedUserId = $v; return $this; }
    public function getClosestTaskAt(): ?string { return $this->ClosestTaskAt; }
    public function setClosestTaskAt(?string $v): self { $this->ClosestTaskAt = $v; return $this; }
    public function getScore(): ?int { return $this->Score; }
    public function setScore(?int $v): self { $this->Score = $v; return $this; }
    public function getIsDeleted(): int { return $this->IsDeleted; }
    public function setIsDeleted(int $v): self { $this->IsDeleted = $v; return $this; }
    public function getCreatedAt(): ?string { return $this->CreatedAt; }
    public function setCreatedAt(?string $v): self { $this->CreatedAt = $v; return $this; }
    public function getUpdatedAt(): ?string { return $this->UpdatedAt; }
    public function setUpdatedAt(?string $v): self { $this->UpdatedAt = $v; return $this; }
}
