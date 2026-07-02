<?php

namespace AmoCRM\Dwh\Models;

class LeadFactDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $LeadId;
    protected ?int $DateCreateId = null;
    protected ?int $DateCloseId = null;
    protected ?string $ClientId = null;
    protected ?string $TrafficId = null;
    protected ?int $PipelineId = null;
    protected ?int $StatusId = null;
    protected ?int $LossReasonId = null;
    protected ?int $ResponsibleUserId = null;
    protected ?int $CompanyId = null;
    protected ?int $ContactId = null;
    protected ?string $Price = null;
    protected ?string $LaborCost = null;
    protected ?int $Score = null;

    public static function getTableName(): string { return 'amocrm_leads_facts'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'lead_id' => $this->LeadId,
            'date_create_id' => $this->DateCreateId,
            'date_close_id' => $this->DateCloseId,
            'client_id' => $this->ClientId,
            'traffic_id' => $this->TrafficId,
            'pipeline_id' => $this->PipelineId,
            'status_id' => $this->StatusId,
            'loss_reason_id' => $this->LossReasonId,
            'responsible_user_id' => $this->ResponsibleUserId,
            'company_id' => $this->CompanyId,
            'contact_id' => $this->ContactId,
            'price' => $this->Price,
            'labor_cost' => $this->LaborCost,
            'score' => $this->Score,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getLeadId(): int { return $this->LeadId; }
    public function setLeadId(int $v): self { $this->LeadId = $v; return $this; }
    public function getDateCreateId(): ?int { return $this->DateCreateId; }
    public function setDateCreateId(?int $v): self { $this->DateCreateId = $v; return $this; }
    public function getDateCloseId(): ?int { return $this->DateCloseId; }
    public function setDateCloseId(?int $v): self { $this->DateCloseId = $v; return $this; }
    public function getClientId(): ?string { return $this->ClientId; }
    public function setClientId(?string $v): self { $this->ClientId = $v; return $this; }
    public function getTrafficId(): ?string { return $this->TrafficId; }
    public function setTrafficId(?string $v): self { $this->TrafficId = $v; return $this; }
    public function getPipelineId(): ?int { return $this->PipelineId; }
    public function setPipelineId(?int $v): self { $this->PipelineId = $v; return $this; }
    public function getStatusId(): ?int { return $this->StatusId; }
    public function setStatusId(?int $v): self { $this->StatusId = $v; return $this; }
    public function getLossReasonId(): ?int { return $this->LossReasonId; }
    public function setLossReasonId(?int $v): self { $this->LossReasonId = $v; return $this; }
    public function getResponsibleUserId(): ?int { return $this->ResponsibleUserId; }
    public function setResponsibleUserId(?int $v): self { $this->ResponsibleUserId = $v; return $this; }
    public function getCompanyId(): ?int { return $this->CompanyId; }
    public function setCompanyId(?int $v): self { $this->CompanyId = $v; return $this; }
    public function getContactId(): ?int { return $this->ContactId; }
    public function setContactId(?int $v): self { $this->ContactId = $v; return $this; }
    public function getPrice(): ?string { return $this->Price; }
    public function setPrice(?string $v): self { $this->Price = $v; return $this; }
    public function getLaborCost(): ?string { return $this->LaborCost; }
    public function setLaborCost(?string $v): self { $this->LaborCost = $v; return $this; }
    public function getScore(): ?int { return $this->Score; }
    public function setScore(?int $v): self { $this->Score = $v; return $this; }
}
