<?php

namespace AmoCRM\Dwh\Models;

class TransactionElementFactDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $TransactionId;
    protected int $ElementId;
    protected ?string $Quantity = null;
    protected ?string $Price = null;

    public static function getTableName(): string { return 'amocrm_transactions_elements_facts'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'transaction_id' => $this->TransactionId,
            'element_id' => $this->ElementId,
            'quantity' => $this->Quantity,
            'price' => $this->Price,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getTransactionId(): int { return $this->TransactionId; }
    public function setTransactionId(int $v): self { $this->TransactionId = $v; return $this; }
    public function getElementId(): int { return $this->ElementId; }
    public function setElementId(int $v): self { $this->ElementId = $v; return $this; }
    public function getQuantity(): ?string { return $this->Quantity; }
    public function setQuantity(?string $v): self { $this->Quantity = $v; return $this; }
    public function getPrice(): ?string { return $this->Price; }
    public function setPrice(?string $v): self { $this->Price = $v; return $this; }
}
