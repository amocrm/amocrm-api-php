<?php

declare(strict_types=1);

namespace Analytics\Models;

use Carbon\Carbon;

/**
 * Customer Lifetime Value model
 */
class CustomerLTV extends BaseModel
{
    private ?int $id = null;
    private int $customerId;
    private array $contactIds = [];
    private float $totalRevenue = 0.0;
    private int $transactionCount = 0;
    private ?Carbon $firstPurchaseAt = null;
    private ?Carbon $lastPurchaseAt = null;
    private float $ltv90Days = 0.0;
    private float $ltv180Days = 0.0;
    private float $ltv365Days = 0.0;
    private ?Carbon $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContactIds(): array
    {
        return $this->contactIds;
    }

    public function setContactIds(array $contactIds): self
    {
        $this->contactIds = $contactIds;
        return $this;
    }

    public function getTotalRevenue(): float
    {
        return $this->totalRevenue;
    }

    public function setTotalRevenue(float $totalRevenue): self
    {
        $this->totalRevenue = $totalRevenue;
        return $this;
    }

    public function getTransactionCount(): int
    {
        return $this->transactionCount;
    }

    public function setTransactionCount(int $transactionCount): self
    {
        $this->transactionCount = $transactionCount;
        return $this;
    }

    public function getFirstPurchaseAt(): ?Carbon
    {
        return $this->firstPurchaseAt;
    }

    public function setFirstPurchaseAt(?Carbon $firstPurchaseAt): self
    {
        $this->firstPurchaseAt = $firstPurchaseAt;
        return $this;
    }

    public function getLastPurchaseAt(): ?Carbon
    {
        return $this->lastPurchaseAt;
    }

    public function setLastPurchaseAt(?Carbon $lastPurchaseAt): self
    {
        $this->lastPurchaseAt = $lastPurchaseAt;
        return $this;
    }

    public function getLtv90Days(): float
    {
        return $this->ltv90Days;
    }

    public function setLtv90Days(float $ltv90Days): self
    {
        $this->ltv90Days = $ltv90Days;
        return $this;
    }

    public function getLtv180Days(): float
    {
        return $this->ltv180Days;
    }

    public function setLtv180Days(float $ltv180Days): self
    {
        $this->ltv180Days = $ltv180Days;
        return $this;
    }

    public function getLtv365Days(): float
    {
        return $this->ltv365Days;
    }

    public function setLtv365Days(float $ltv365Days): self
    {
        $this->ltv365Days = $ltv365Days;
        return $this;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?Carbon $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Calculate average transaction value
     */
    public function getAvgTransactionValue(): float
    {
        return $this->transactionCount > 0 
            ? $this->totalRevenue / $this->transactionCount 
            : 0.0;
    }

    /**
     * Get LTV (current total revenue)
     */
    public function getLTV(): float
    {
        return $this->totalRevenue;
    }

    /**
     * Get days since first purchase
     */
    public function getDaysSinceFirstPurchase(): ?int
    {
        return $this->firstPurchaseAt?->diffInDays(Carbon::now());
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customerId,
            'contact_ids' => $this->contactIds,
            'total_revenue' => $this->totalRevenue,
            'transaction_count' => $this->transactionCount,
            'avg_transaction_value' => round($this->getAvgTransactionValue(), 2),
            'first_purchase_at' => $this->firstPurchaseAt?->toDateString(),
            'last_purchase_at' => $this->lastPurchaseAt?->toDateString(),
            'days_since_first_purchase' => $this->getDaysSinceFirstPurchase(),
            'ltv' => round($this->getLTV(), 2),
            'ltv_90_days' => $this->ltv90Days,
            'ltv_180_days' => $this->ltv180Days,
            'ltv_365_days' => $this->ltv365Days,
            'updated_at' => $this->updatedAt?->toIso8601String(),
        ];
    }

    public static function fromArray(array $data): static
    {
        $ltv = new static();

        $ltv->id = $data['id'] ?? null;
        $ltv->customerId = (int)$data['customer_id'];
        $ltv->contactIds = (array)($data['contact_ids'] ?? []);
        $ltv->totalRevenue = (float)($data['total_revenue'] ?? 0);
        $ltv->transactionCount = (int)($data['transaction_count'] ?? 0);
        $ltv->ltv90Days = (float)($data['ltv_90_days'] ?? 0);
        $ltv->ltv180Days = (float)($data['ltv_180_days'] ?? 0);
        $ltv->ltv365Days = (float)($data['ltv_365_days'] ?? 0);

        if (isset($data['first_purchase_at'])) {
            $ltv->firstPurchaseAt = $data['first_purchase_at'] instanceof Carbon
                ? $data['first_purchase_at']
                : Carbon::parse($data['first_purchase_at']);
        }

        if (isset($data['last_purchase_at'])) {
            $ltv->lastPurchaseAt = $data['last_purchase_at'] instanceof Carbon
                ? $data['last_purchase_at']
                : Carbon::parse($data['last_purchase_at']);
        }

        if (isset($data['updated_at'])) {
            $ltv->updatedAt = $data['updated_at'] instanceof Carbon
                ? $data['updated_at']
                : Carbon::parse($data['updated_at']);
        }

        return $ltv;
    }

    public static function getAvailableWith(): array
    {
        return [];
    }
}