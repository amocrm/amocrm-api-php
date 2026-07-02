<?php

namespace AmoCRM\Dwh\Models;

class CustomerElementFactDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $CustomerId;
    protected int $ElementId;
    protected ?string $Quantity = null;
    protected ?string $Price = null;

    public static function getTableName(): string { return 'amocrm_customers_elements_facts'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'customer_id' => $this->CustomerId,
            'element_id' => $this->ElementId,
            'quantity' => $this->Quantity,
            'price' => $this->Price,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getCustomerId(): int { return $this->CustomerId; }
    public function setCustomerId(int $v): self { $this->CustomerId = $v; return $this; }
    public function getElementId(): int { return $this->ElementId; }
    public function setElementId(int $v): self { $this->ElementId = $v; return $this; }
    public function getQuantity(): ?string { return $this->Quantity; }
    public function setQuantity(?string $v): self { $this->Quantity = $v; return $this; }
    public function getPrice(): ?string { return $this->Price; }
    public function setPrice(?string $v): self { $this->Price = $v; return $this; }
}
