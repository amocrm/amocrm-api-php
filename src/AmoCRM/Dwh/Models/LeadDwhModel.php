<?php

namespace AmoCRM\Dwh\Models;

class LeadDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $LeadId;
    protected ?string $Name = null;
    protected ?string $Price = null;
    protected ?int $PipelineId = null;
    protected ?int $StatusId = null;
    protected ?int $OldStatusId = null;
    protected ?int $LossReasonId = null;
    protected ?int $ResponsibleUserId = null;
    protected ?int $GroupId = null;
    protected ?int $CreatedUserId = null;
    protected ?int $ModifiedUserId = null;
    protected ?int $CompanyId = null;
    protected ?int $ContactId = null;
    protected ?int $ClosedUserId = null;
    protected ?string $ClosedAt = null;
    protected ?string $ClosestTaskAt = null;
    protected ?int $Score = null;
    protected int $IsPriceModifiedByRobot;
    protected int $IsDeleted;
    protected ?string $SourceExternalId = null;
    protected ?string $VisitorUid = null;
    protected ?string $CreatedAt = null;
    protected ?string $UpdatedAt = null;

    public static function getTableName(): string { return 'amocrm_leads'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'lead_id' => $this->LeadId,
            'name' => $this->Name,
            'price' => $this->Price,
            'pipeline_id' => $this->PipelineId,
            'status_id' => $this->StatusId,
            'old_status_id' => $this->OldStatusId,
            'loss_reason_id' => $this->LossReasonId,
            'responsible_user_id' => $this->ResponsibleUserId,
            'group_id' => $this->GroupId,
            'created_user_id' => $this->CreatedUserId,
            'modified_user_id' => $this->ModifiedUserId,
            'company_id' => $this->CompanyId,
            'contact_id' => $this->ContactId,
            'closed_user_id' => $this->ClosedUserId,
            'closed_at' => $this->ClosedAt,
            'closest_task_at' => $this->ClosestTaskAt,
            'score' => $this->Score,
            'is_price_modified_by_robot' => $this->IsPriceModifiedByRobot,
            'is_deleted' => $this->IsDeleted,
            'source_external_id' => $this->SourceExternalId,
            'visitor_uid' => $this->VisitorUid,
            'created_at' => $this->CreatedAt,
            'updated_at' => $this->UpdatedAt,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getLeadId(): int { return $this->LeadId; }
    public function setLeadId(int $v): self { $this->LeadId = $v; return $this; }
    public function getName(): ?string { return $this->Name; }
    public function setName(?string $v): self { $this->Name = $v; return $this; }
    public function getPrice(): ?string { return $this->Price; }
    public function setPrice(?string $v): self { $this->Price = $v; return $this; }
    public function getPipelineId(): ?int { return $this->PipelineId; }
    public function setPipelineId(?int $v): self { $this->PipelineId = $v; return $this; }
    public function getStatusId(): ?int { return $this->StatusId; }
    public function setStatusId(?int $v): self { $this->StatusId = $v; return $this; }
    public function getOldStatusId(): ?int { return $this->OldStatusId; }
    public function setOldStatusId(?int $v): self { $this->OldStatusId = $v; return $this; }
    public function getLossReasonId(): ?int { return $this->LossReasonId; }
    public function setLossReasonId(?int $v): self { $this->LossReasonId = $v; return $this; }
    public function getResponsibleUserId(): ?int { return $this->ResponsibleUserId; }
    public function setResponsibleUserId(?int $v): self { $this->ResponsibleUserId = $v; return $this; }
    public function getGroupId(): ?int { return $this->GroupId; }
    public function setGroupId(?int $v): self { $this->GroupId = $v; return $this; }
    public function getCreatedUserId(): ?int { return $this->CreatedUserId; }
    public function setCreatedUserId(?int $v): self { $this->CreatedUserId = $v; return $this; }
    public function getModifiedUserId(): ?int { return $this->ModifiedUserId; }
    public function setModifiedUserId(?int $v): self { $this->ModifiedUserId = $v; return $this; }
    public function getCompanyId(): ?int { return $this->CompanyId; }
    public function setCompanyId(?int $v): self { $this->CompanyId = $v; return $this; }
    public function getContactId(): ?int { return $this->ContactId; }
    public function setContactId(?int $v): self { $this->ContactId = $v; return $this; }
    public function getClosedUserId(): ?int { return $this->ClosedUserId; }
    public function setClosedUserId(?int $v): self { $this->ClosedUserId = $v; return $this; }
    public function getClosedAt(): ?string { return $this->ClosedAt; }
    public function setClosedAt(?string $v): self { $this->ClosedAt = $v; return $this; }
    public function getClosestTaskAt(): ?string { return $this->ClosestTaskAt; }
    public function setClosestTaskAt(?string $v): self { $this->ClosestTaskAt = $v; return $this; }
    public function getScore(): ?int { return $this->Score; }
    public function setScore(?int $v): self { $this->Score = $v; return $this; }
    public function getIsPriceModifiedByRobot(): int { return $this->IsPriceModifiedByRobot; }
    public function setIsPriceModifiedByRobot(int $v): self { $this->IsPriceModifiedByRobot = $v; return $this; }
    public function getIsDeleted(): int { return $this->IsDeleted; }
    public function setIsDeleted(int $v): self { $this->IsDeleted = $v; return $this; }
    public function getSourceExternalId(): ?string { return $this->SourceExternalId; }
    public function setSourceExternalId(?string $v): self { $this->SourceExternalId = $v; return $this; }
    public function getVisitorUid(): ?string { return $this->VisitorUid; }
    public function setVisitorUid(?string $v): self { $this->VisitorUid = $v; return $this; }
    public function getCreatedAt(): ?string { return $this->CreatedAt; }
    public function setCreatedAt(?string $v): self { $this->CreatedAt = $v; return $this; }
    public function getUpdatedAt(): ?string { return $this->UpdatedAt; }
    public function setUpdatedAt(?string $v): self { $this->UpdatedAt = $v; return $this; }
}
