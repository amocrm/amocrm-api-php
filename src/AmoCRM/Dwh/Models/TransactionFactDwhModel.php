<?php

namespace AmoCRM\Dwh\Models;

class TransactionFactDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $TransactionId;
    protected ?int $CustomerId = null;
    protected ?int $ContactId = null;
    protected ?int $CompanyId = null;
    protected ?int $DateId = null;
    protected ?string $CompletedAt = null;
    protected ?string $Price = null;

    public static function getTableName(): string { return 'amocrm_transactions_facts'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'transaction_id' => $this->TransactionId,
            'customer_id' => $this->CustomerId,
            'contact_id' => $this->ContactId,
            'company_id' => $this->CompanyId,
            'date_id' => $this->DateId,
            'completed_at' => $this->CompletedAt,
            'price' => $this->Price,
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
    public function getDateId(): ?int { return $this->DateId; }
    public function setDateId(?int $v): self { $this->DateId = $v; return $this; }
    public function getCompletedAt(): ?string { return $this->CompletedAt; }
    public function setCompletedAt(?string $v): self { $this->CompletedAt = $v; return $this; }
    public function getPrice(): ?string { return $this->Price; }
    public function setPrice(?string $v): self { $this->Price = $v; return $this; }
}
