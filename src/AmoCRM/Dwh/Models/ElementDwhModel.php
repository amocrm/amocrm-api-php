<?php

namespace AmoCRM\Dwh\Models;

class ElementDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $ElementId;
    protected int $CatalogId;
    protected ?string $Name = null;
    protected ?string $Article = null;
    protected ?string $Price = null;
    protected ?string $Quantity = null;
    protected ?string $ExternalId = null;
    protected int $IsDeleted;
    protected ?int $CreatedUserId = null;
    protected ?int $ModifiedUserId = null;
    protected ?string $CreatedAt = null;
    protected ?string $UpdatedAt = null;

    public static function getTableName(): string { return 'amocrm_elements'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'element_id' => $this->ElementId,
            'catalog_id' => $this->CatalogId,
            'name' => $this->Name,
            'article' => $this->Article,
            'price' => $this->Price,
            'quantity' => $this->Quantity,
            'external_id' => $this->ExternalId,
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
    public function getElementId(): int { return $this->ElementId; }
    public function setElementId(int $v): self { $this->ElementId = $v; return $this; }
    public function getCatalogId(): int { return $this->CatalogId; }
    public function setCatalogId(int $v): self { $this->CatalogId = $v; return $this; }
    public function getName(): ?string { return $this->Name; }
    public function setName(?string $v): self { $this->Name = $v; return $this; }
    public function getArticle(): ?string { return $this->Article; }
    public function setArticle(?string $v): self { $this->Article = $v; return $this; }
    public function getPrice(): ?string { return $this->Price; }
    public function setPrice(?string $v): self { $this->Price = $v; return $this; }
    public function getQuantity(): ?string { return $this->Quantity; }
    public function setQuantity(?string $v): self { $this->Quantity = $v; return $this; }
    public function getExternalId(): ?string { return $this->ExternalId; }
    public function setExternalId(?string $v): self { $this->ExternalId = $v; return $this; }
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
