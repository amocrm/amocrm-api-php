<?php

declare(strict_types=1);

namespace Analytics\Models;

use Carbon\Carbon;

/**
 * Funnel stage metrics
 */
class FunnelStage extends BaseModel
{
    private ?int $id = null;
    private int $pipelineId;
    private ?string $pipelineName = null;
    private int $statusId;
    private string $statusName;
    private int $sortOrder = 0;
    private bool $isFinal = false;
    private ?string $finalType = null;
    private int $leadCount = 0;
    private float $totalRevenue = 0.0;
    private float $avgDealValue = 0.0;
    private float $conversionRate = 1.0;
    private float $avgTimeSeconds = 0.0;
    private ?float $previousConversionRate = null;
    private int $convertedCount = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPipelineId(): int
    {
        return $this->pipelineId;
    }

    public function setPipelineId(int $pipelineId): self
    {
        $this->pipelineId = $pipelineId;
        return $this;
    }

    public function getPipelineName(): ?string
    {
        return $this->pipelineName;
    }

    public function setPipelineName(?string $pipelineName): self
    {
        $this->pipelineName = $pipelineName;
        return $this;
    }

    public function getStatusId(): int
    {
        return $this->statusId;
    }

    public function setStatusId(int $statusId): self
    {
        $this->statusId = $statusId;
        return $this;
    }

    public function getStatusName(): string
    {
        return $this->statusName;
    }

    public function setStatusName(string $statusName): self
    {
        $this->statusName = $statusName;
        return $this;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    public function isFinal(): bool
    {
        return $this->isFinal;
    }

    public function setIsFinal(bool $isFinal): self
    {
        $this->isFinal = $isFinal;
        return $this;
    }

    public function getFinalType(): ?string
    {
        return $this->finalType;
    }

    public function setFinalType(?string $finalType): self
    {
        $this->finalType = $finalType;
        return $this;
    }

    public function getLeadCount(): int
    {
        return $this->leadCount;
    }

    public function setLeadCount(int $leadCount): self
    {
        $this->leadCount = $leadCount;
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

    public function getAvgDealValue(): float
    {
        return $this->avgDealValue;
    }

    public function setAvgDealValue(float $avgDealValue): self
    {
        $this->avgDealValue = $avgDealValue;
        return $this;
    }

    public function getConversionRate(): float
    {
        return $this->conversionRate;
    }

    public function setConversionRate(float $conversionRate): self
    {
        $this->conversionRate = $conversionRate;
        return $this;
    }

    public function getAvgTimeSeconds(): float
    {
        return $this->avgTimeSeconds;
    }

    public function setAvgTimeSeconds(float $avgTimeSeconds): self
    {
        $this->avgTimeSeconds = $avgTimeSeconds;
        return $this;
    }

    public function getAvgTimeDays(): float
    {
        return $this->avgTimeSeconds / 86400;
    }

    public function getPreviousConversionRate(): ?float
    {
        return $this->previousConversionRate;
    }

    public function setPreviousConversionRate(?float $previousConversionRate): self
    {
        $this->previousConversionRate = $previousConversionRate;
        return $this;
    }

    public function getConvertedCount(): int
    {
        return $this->convertedCount;
    }

    public function setConvertedCount(int $convertedCount): self
    {
        $this->convertedCount = $convertedCount;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'pipeline_id' => $this->pipelineId,
            'pipeline_name' => $this->pipelineName,
            'status_id' => $this->statusId,
            'status_name' => $this->statusName,
            'sort_order' => $this->sortOrder,
            'is_final' => $this->isFinal,
            'final_type' => $this->finalType,
            'metrics' => [
                'lead_count' => $this->leadCount,
                'total_revenue' => $this->totalRevenue,
                'avg_deal_value' => $this->avgDealValue,
                'conversion_rate' => $this->conversionRate,
                'avg_time_days' => round($this->getAvgTimeDays(), 2),
            ],
            'next_stage_conversion' => $this->previousConversionRate !== null ? [
                'converted_count' => $this->convertedCount,
                'conversion_rate' => $this->previousConversionRate,
            ] : null,
        ];
    }

    public static function fromArray(array $data): static
    {
        $stage = new static();

        $stage->id = $data['id'] ?? null;
        $stage->pipelineId = (int)$data['pipeline_id'];
        $stage->pipelineName = $data['pipeline_name'] ?? null;
        $stage->statusId = (int)$data['status_id'];
        $stage->statusName = $data['status_name'] ?? '';
        $stage->sortOrder = (int)($data['sort_order'] ?? 0);
        $stage->isFinal = (bool)($data['is_final'] ?? false);
        $stage->finalType = $data['final_type'] ?? null;
        $stage->leadCount = (int)($data['lead_count'] ?? 0);
        $stage->totalRevenue = (float)($data['total_revenue'] ?? 0);
        $stage->avgDealValue = (float)($data['avg_deal_value'] ?? 0);
        $stage->conversionRate = (float)($data['conversion_rate'] ?? 1.0);
        $stage->avgTimeSeconds = (float)($data['avg_time_seconds'] ?? 0);
        $stage->convertedCount = (int)($data['converted_count'] ?? 0);

        return $stage;
    }

    public static function getAvailableWith(): array
    {
        return [];
    }
}