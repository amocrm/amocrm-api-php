<?php

namespace AmoCRM\Dwh\Models;

class ElementProductDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $ElementId;
    protected int $ProductId;
    protected ?string $Name = null;
    protected ?string $Quantity = null;
    protected ?string $Price = null;

    public static function getTableName(): string { return 'amocrm_elements_products'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'element_id' => $this->ElementId,
            'product_id' => $this->ProductId,
            'name' => $this->Name,
            'quantity' => $this->Quantity,
            'price' => $this->Price,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getElementId(): int { return $this->ElementId; }
    public function setElementId(int $v): self { $this->ElementId = $v; return $this; }
    public function getProductId(): int { return $this->ProductId; }
    public function setProductId(int $v): self { $this->ProductId = $v; return $this; }
    public function getName(): ?string { return $this->Name; }
    public function setName(?string $v): self { $this->Name = $v; return $this; }
    public function getQuantity(): ?string { return $this->Quantity; }
    public function setQuantity(?string $v): self { $this->Quantity = $v; return $this; }
    public function getPrice(): ?string { return $this->Price; }
    public function setPrice(?string $v): self { $this->Price = $v; return $this; }
}
