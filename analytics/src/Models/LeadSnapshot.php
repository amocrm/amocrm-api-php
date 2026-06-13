<?php

declare(strict_types=1);

namespace Analytics\Models;

use Carbon\Carbon;

/**
 * Lead snapshot for historical tracking
 */
class LeadSnapshot extends BaseModel
{
    private ?int $id = null;
    private int $leadId;
    private int $pipelineId;
    private int $statusId;
    private ?string $statusName = null;
    private ?float $price = null;
    private ?int $sourceId = null;
    private ?int $responsibleUserId = null;
    private array $contactIds = [];
    private ?int $companyId = null;
    private ?int $lossReasonId = null;
    private array $tags = [];
    private array $customFields = [];
    private ?Carbon $createdAt = null;
    private ?Carbon $updatedAt = null;
    private ?Carbon $closedAt = null;
    private ?Carbon $snapshotAt = null;
    private bool $isDeleted = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getLeadId(): int
    {
        return $this->leadId;
    }

    public function setLeadId(int $leadId): self
    {
        $this->leadId = $leadId;
        return $this;
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

    public function getStatusId(): int
    {
        return $this->statusId;
    }

    public function setStatusId(int $statusId): self
    {
        $this->statusId = $statusId;
        return $this;
    }

    public function getStatusName(): ?string
    {
        return $this->statusName;
    }

    public function setStatusName(?string $statusName): self
    {
        $this->statusName = $statusName;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getSourceId(): ?int
    {
        return $this->sourceId;
    }

    public function setSourceId(?int $sourceId): self
    {
        $this->sourceId = $sourceId;
        return $this;
    }

    public function getResponsibleUserId(): ?int
    {
        return $this->responsibleUserId;
    }

    public function setResponsibleUserId(?int $responsibleUserId): self
    {
        $this->responsibleUserId = $responsibleUserId;
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

    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    public function setCompanyId(?int $companyId): self
    {
        $this->companyId = $companyId;
        return $this;
    }

    public function getLossReasonId(): ?int
    {
        return $this->lossReasonId;
    }

    public function setLossReasonId(?int $lossReasonId): self
    {
        $this->lossReasonId = $lossReasonId;
        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): self
    {
        $this->tags = $tags;
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

    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?Carbon $createdAt): self
    {
        $this->createdAt = $createdAt;
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

    public function getClosedAt(): ?Carbon
    {
        return $this->closedAt;
    }

    public function setClosedAt(?Carbon $closedAt): self
    {
        $this->closedAt = $closedAt;
        return $this;
    }

    public function getSnapshotAt(): ?Carbon
    {
        return $this->snapshotAt ?? Carbon::now();
    }

    public function setSnapshotAt(?Carbon $snapshotAt): self
    {
        $this->snapshotAt = $snapshotAt;
        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'lead_id' => $this->leadId,
            'pipeline_id' => $this->pipelineId,
            'status_id' => $this->statusId,
            'status_name' => $this->statusName,
            'price' => $this->price,
            'source_id' => $this->sourceId,
            'responsible_user_id' => $this->responsibleUserId,
            'contact_ids' => $this->contactIds,
            'company_id' => $this->companyId,
            'loss_reason_id' => $this->lossReasonId,
            'tags' => $this->tags,
            'custom_fields' => $this->customFields,
            'created_at' => $this->createdAt?->toIso8601String(),
            'updated_at' => $this->updatedAt?->toIso8601String(),
            'closed_at' => $this->closedAt?->toIso8601String(),
            'snapshot_at' => $this->getSnapshotAt()->toIso8601String(),
            'is_deleted' => $this->isDeleted,
        ];
    }

    public static function fromArray(array $data): static
    {
        $snapshot = new static();

        $snapshot->setId($data['id'] ?? null);
        $snapshot->setLeadId((int)$data['lead_id']);
        $snapshot->setPipelineId((int)$data['pipeline_id']);
        $snapshot->setStatusId((int)$data['status_id']);

        if (isset($data['status_name'])) {
            $snapshot->setStatusName($data['status_name']);
        }

        if (isset($data['price'])) {
            $snapshot->setPrice((float)$data['price']);
        }

        if (isset($data['source_id'])) {
            $snapshot->setSourceId((int)$data['source_id']);
        }

        if (isset($data['responsible_user_id'])) {
            $snapshot->setResponsibleUserId((int)$data['responsible_user_id']);
        }

        if (isset($data['contact_ids'])) {
            $snapshot->setContactIds((array)$data['contact_ids']);
        }

        if (isset($data['company_id'])) {
            $snapshot->setCompanyId((int)$data['company_id']);
        }

        if (isset($data['loss_reason_id'])) {
            $snapshot->setLossReasonId((int)$data['loss_reason_id']);
        }

        if (isset($data['tags'])) {
            $snapshot->setTags((array)$data['tags']);
        }

        if (isset($data['custom_fields'])) {
            $snapshot->setCustomFields((array)$data['custom_fields']);
        }

        if (isset($data['created_at'])) {
            $snapshot->setCreatedAt(
                $data['created_at'] instanceof Carbon
                    ? $data['created_at']
                    : Carbon::parse($data['created_at'])
            );
        }

        if (isset($data['updated_at'])) {
            $snapshot->setUpdatedAt(
                $data['updated_at'] instanceof Carbon
                    ? $data['updated_at']
                    : Carbon::parse($data['updated_at'])
            );
        }

        if (isset($data['closed_at'])) {
            $snapshot->setClosedAt(
                $data['closed_at'] instanceof Carbon
                    ? $data['closed_at']
                    : Carbon::parse($data['closed_at'])
            );
        }

        if (isset($data['snapshot_at'])) {
            $snapshot->setSnapshotAt(
                $data['snapshot_at'] instanceof Carbon
                    ? $data['snapshot_at']
                    : Carbon::parse($data['snapshot_at'])
            );
        }

        if (isset($data['is_deleted'])) {
            $snapshot->setIsDeleted((bool)$data['is_deleted']);
        }

        return $snapshot;
    }

    /**
     * Create from amoCRM LeadModel
     */
    public static function fromLeadModel(\AmoCRM\Models\LeadModel $lead): static
    {
        $snapshot = new static();

        $snapshot->setLeadId($lead->getId() ?? 0);
        $snapshot->setPipelineId($lead->getPipelineId() ?? 0);
        $snapshot->setStatusId($lead->getStatusId() ?? 0);
        $snapshot->setPrice($lead->getPrice());
        $snapshot->setResponsibleUserId($lead->getResponsibleUserId());
        $snapshot->setCreatedAt($lead->getCreatedAt() ? Carbon::createFromTimestamp($lead->getCreatedAt()) : null);
        $snapshot->setUpdatedAt($lead->getUpdatedAt() ? Carbon::createFromTimestamp($lead->getUpdatedAt()) : null);
        $snapshot->setClosedAt($lead->getClosedAt() ? Carbon::createFromTimestamp($lead->getClosedAt()) : null);

        // Extract custom fields
        $customFields = [];
        $cfValues = $lead->getCustomFieldsValues();
        if ($cfValues !== null) {
            foreach ($cfValues as $cf) {
                $fieldId = $cf->getFieldId();
                $values = [];
                foreach ($cf->getValues() as $value) {
                    $values[] = $value->getValue();
                }
                $customFields[$fieldId] = $values;
            }
        }
        $snapshot->setCustomFields($customFields);

        // Extract contact IDs
        $contacts = $lead->getContacts();
        if ($contacts !== null) {
            $contactIds = [];
            foreach ($contacts as $contact) {
                $contactIds[] = $contact->getId();
            }
            $snapshot->setContactIds($contactIds);
        }

        // Extract company ID
        $company = $lead->getCompany();
        if ($company !== null) {
            $snapshot->setCompanyId($company->getId());
        }

        // Extract tags
        $tags = $lead->getTags();
        if ($tags !== null) {
            $tagNames = [];
            foreach ($tags as $tag) {
                $tagNames[] = $tag->getName();
            }
            $snapshot->setTags($tagNames);
        }

        return $snapshot;
    }

    public static function getAvailableWith(): array
    {
        return [
            'contacts',
            'loss_reason',
            'catalog_elements',
        ];
    }
}
