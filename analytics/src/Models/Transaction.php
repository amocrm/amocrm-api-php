<?php

declare(strict_types=1);

namespace Analytics\Models;

use Carbon\Carbon;

/**
 * Transaction model
 */
class Transaction extends BaseModel
{
    private ?int $id = null;
    private int $transactionId;
    private int $customerId;
    private float $price;
    private ?int $catalogElementId = null;
    private ?string $catalogElementName = null;
    private int $quantity = 1;
    private ?float $unitPrice = null;
    private ?Carbon $createdAt = null;
    private ?Carbon $completedAt = null;
    private bool $isCompleted = false;
    private array $customFields = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransactionId(): int
    {
        return $this->transactionId;
    }

    public function setTransactionId(int $transactionId): self
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): self
    {
        $this->customerId = $customerId;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getCatalogElementId(): ?int
    {
        return $this->catalogElementId;
    }

    public function setCatalogElementId(?int $catalogElementId): self
    {
        $this->catalogElementId = $catalogElementId;
        return $this;
    }

    public function getCatalogElementName(): ?string
    {
        return $this->catalogElementName;
    }

    public function setCatalogElementName(?string $catalogElementName): self
    {
        $this->catalogElementName = $catalogElementName;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(?float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?Carbon $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCompletedAt(): ?Carbon
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?Carbon $completedAt): self
    {
        $this->completedAt = $completedAt;
        return $this;
    }

    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted): self
    {
        $this->isCompleted = $isCompleted;
        return $this;
    }

    public function getCustomFields(): array
    {
        return $this->customFields;
    }

    public function setCustomFields(array $customFields): self
    {
        $this->customFields = $customFields;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'transaction_id' => $this->transactionId,
            'customer_id' => $this->customerId,
            'price' => $this->price,
            'catalog_element_id' => $this->catalogElementId,
            'catalog_element_name' => $this->catalogElementName,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice,
            'created_at' => $this->createdAt?->toIso8601String(),
            'completed_at' => $this->completedAt?->toIso8601String(),
            'is_completed' => $this->isCompleted,
            'custom_fields' => $this->customFields,
        ];
    }

    public static function fromArray(array $data): static
    {
        $tx = new static();

        $tx->id = $data['id'] ?? null;
        $tx->transactionId = (int)$data['transaction_id'];
        $tx->customerId = (int)$data['customer_id'];
        $tx->price = (float)$data['price'];

        if (isset($data['catalog_element_id'])) {
            $tx->catalogElementId = (int)$data['catalog_element_id'];
        }

        $tx->catalogElementName = $data['catalog_element_name'] ?? null;
        $tx->quantity = (int)($data['quantity'] ?? 1);
        $tx->unitPrice = isset($data['unit_price']) ? (float)$data['unit_price'] : null;
        $tx->isCompleted = (bool)($data['is_completed'] ?? false);

        if (isset($data['created_at'])) {
            $tx->createdAt = $data['created_at'] instanceof Carbon
                ? $data['created_at']
                : Carbon::parse($data['created_at']);
        }

        if (isset($data['completed_at'])) {
            $tx->completedAt = $data['completed_at'] instanceof Carbon
                ? $data['completed_at']
                : Carbon::parse($data['completed_at']);
        }

        if (isset($data['custom_fields'])) {
            $tx->customFields = (array)$data['custom_fields'];
        }

        return $tx;
    }
}
