<?php

namespace AmoCRM\Dwh\Models;

class UnsortedDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $UnsortedId;
    protected ?string $Uid = null;
    protected ?string $SourceUid = null;
    protected ?string $SourceName = null;
    protected ?string $Category = null;
    protected ?int $PipelineId = null;
    protected ?int $StatusId = null;
    protected ?string $FormId = null;
    protected ?string $FormName = null;
    protected ?string $FormPage = null;
    protected ?string $FormSentAt = null;
    protected ?int $ResponsibleUserId = null;
    protected ?int $GroupId = null;
    protected ?int $LeadId = null;
    protected ?int $ContactId = null;
    protected ?int $CompanyId = null;
    protected int $IsProcessed;
    protected ?string $DataJson = null;
    protected ?string $MetadataJson = null;
    protected ?string $CreatedAt = null;
    protected ?string $UpdatedAt = null;

    public static function getTableName(): string { return 'amocrm_unsorted'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'unsorted_id' => $this->UnsortedId,
            'uid' => $this->Uid,
            'source_uid' => $this->SourceUid,
            'source_name' => $this->SourceName,
            'category' => $this->Category,
            'pipeline_id' => $this->PipelineId,
            'status_id' => $this->StatusId,
            'form_id' => $this->FormId,
            'form_name' => $this->FormName,
            'form_page' => $this->FormPage,
            'form_sent_at' => $this->FormSentAt,
            'responsible_user_id' => $this->ResponsibleUserId,
            'group_id' => $this->GroupId,
            'lead_id' => $this->LeadId,
            'contact_id' => $this->ContactId,
            'company_id' => $this->CompanyId,
            'is_processed' => $this->IsProcessed,
            'data_json' => $this->DataJson,
            'metadata_json' => $this->MetadataJson,
            'created_at' => $this->CreatedAt,
            'updated_at' => $this->UpdatedAt,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getUnsortedId(): int { return $this->UnsortedId; }
    public function setUnsortedId(int $v): self { $this->UnsortedId = $v; return $this; }
    public function getUid(): ?string { return $this->Uid; }
    public function setUid(?string $v): self { $this->Uid = $v; return $this; }
    public function getSourceUid(): ?string { return $this->SourceUid; }
    public function setSourceUid(?string $v): self { $this->SourceUid = $v; return $this; }
    public function getSourceName(): ?string { return $this->SourceName; }
    public function setSourceName(?string $v): self { $this->SourceName = $v; return $this; }
    public function getCategory(): ?string { return $this->Category; }
    public function setCategory(?string $v): self { $this->Category = $v; return $this; }
    public function getPipelineId(): ?int { return $this->PipelineId; }
    public function setPipelineId(?int $v): self { $this->PipelineId = $v; return $this; }
    public function getStatusId(): ?int { return $this->StatusId; }
    public function setStatusId(?int $v): self { $this->StatusId = $v; return $this; }
    public function getFormId(): ?string { return $this->FormId; }
    public function setFormId(?string $v): self { $this->FormId = $v; return $this; }
    public function getFormName(): ?string { return $this->FormName; }
    public function setFormName(?string $v): self { $this->FormName = $v; return $this; }
    public function getFormPage(): ?string { return $this->FormPage; }
    public function setFormPage(?string $v): self { $this->FormPage = $v; return $this; }
    public function getFormSentAt(): ?string { return $this->FormSentAt; }
    public function setFormSentAt(?string $v): self { $this->FormSentAt = $v; return $this; }
    public function getResponsibleUserId(): ?int { return $this->ResponsibleUserId; }
    public function setResponsibleUserId(?int $v): self { $this->ResponsibleUserId = $v; return $this; }
    public function getGroupId(): ?int { return $this->GroupId; }
    public function setGroupId(?int $v): self { $this->GroupId = $v; return $this; }
    public function getLeadId(): ?int { return $this->LeadId; }
    public function setLeadId(?int $v): self { $this->LeadId = $v; return $this; }
    public function getContactId(): ?int { return $this->ContactId; }
    public function setContactId(?int $v): self { $this->ContactId = $v; return $this; }
    public function getCompanyId(): ?int { return $this->CompanyId; }
    public function setCompanyId(?int $v): self { $this->CompanyId = $v; return $this; }
    public function getIsProcessed(): int { return $this->IsProcessed; }
    public function setIsProcessed(int $v): self { $this->IsProcessed = $v; return $this; }
    public function getDataJson(): ?string { return $this->DataJson; }
    public function setDataJson(?string $v): self { $this->DataJson = $v; return $this; }
    public function getMetadataJson(): ?string { return $this->MetadataJson; }
    public function setMetadataJson(?string $v): self { $this->MetadataJson = $v; return $this; }
    public function getCreatedAt(): ?string { return $this->CreatedAt; }
    public function setCreatedAt(?string $v): self { $this->CreatedAt = $v; return $this; }
    public function getUpdatedAt(): ?string { return $this->UpdatedAt; }
    public function setUpdatedAt(?string $v): self { $this->UpdatedAt = $v; return $this; }
}
