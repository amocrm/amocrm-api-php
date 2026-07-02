<?php

namespace AmoCRM\Dwh\Models;

class TransactionDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $TransactionId;
    protected ?int $CustomerId = null;
    protected ?int $ContactId = null;
    protected ?int $CompanyId = null;
    protected ?string $CompletedAt = null;
    protected ?string $Price = null;
    protected ?string $Comment = null;
    protected ?int $CreatedUserId = null;
    protected ?int $ModifiedUserId = null;
    protected int $IsDeleted;
    protected ?string $CreatedAt = null;
    protected ?string $UpdatedAt = null;

    public static function getTableName(): string { return 'amocrm_transactions'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'transaction_id' => $this->TransactionId,
            'customer_id' => $this->CustomerId,
            'contact_id' => $this->ContactId,
            'company_id' => $this->CompanyId,
            'completed_at' => $this->CompletedAt,
            'price' => $this->Price,
            'comment' => $this->Comment,
            'created_user_id' => $this->CreatedUserId,
            'modified_user_id' => $this->ModifiedUserId,
            'is_deleted' => $this->IsDeleted,
            'created_at' => $this->CreatedAt,
            'updated_at' => $this->UpdatedAt,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getTransactionId(): int { return $this->TransactionId; }
    public function setTransactionId(int $v): self { $this->TransactionId = $v; return $this; }
    public function getCustomerId(): ?int { return $this->CustomerId; }
    public function setCustomerId(?int $v): self { $this->CustomerId = $v; return $this; }
    public function getContactId(): ?int { return $this->ContactId; }
    public function setContactId(?int $v): self { $this->ContactId = $v; return $this; }
    public function getCompanyId(): ?int { return $this->CompanyId; }
    public function setCompanyId(?int $v): self { $this->CompanyId = $v; return $this; }
    public function getCompletedAt(): ?string { return $this->CompletedAt; }
    public function setCompletedAt(?string $v): self { $this->CompletedAt = $v; return $this; }
    public function getPrice(): ?string { return $this->Price; }
    public function setPrice(?string $v): self { $this->Price = $v; return $this; }
    public function getComment(): ?string { return $this->Comment; }
    public function setComment(?string $v): self { $this->Comment = $v; return $this; }
    public function getCreatedUserId(): ?int { return $this->CreatedUserId; }
    public function setCreatedUserId(?int $v): self { $this->CreatedUserId = $v; return $this; }
    public function getModifiedUserId(): ?int { return $this->ModifiedUserId; }
    public function setModifiedUserId(?int $v): self { $this->ModifiedUserId = $v; return $this; }
    public function getIsDeleted(): int { return $this->IsDeleted; }
    public function setIsDeleted(int $v): self { $this->IsDeleted = $v; return $this; }
    public function getCreatedAt(): ?string { return $this->CreatedAt; }
    public function setCreatedAt(?string $v): self { $this->CreatedAt = $v; return $this; }
    public function getUpdatedAt(): ?string { return $this->UpdatedAt; }
    public function setUpdatedAt(?string $v): self { $this->UpdatedAt = $v; return $this; }
}
