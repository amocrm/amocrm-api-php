<?php

namespace AmoCRM\Dwh\Models;

class TaskDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $TaskId;
    protected ?string $Name = null;
    protected ?int $ResponsibleUserId = null;
    protected ?int $CreatedUserId = null;
    protected ?int $ModifiedUserId = null;
    protected ?int $TaskTypeId = null;
    protected ?string $EntityType = null;
    protected ?int $EntityId = null;
    protected ?int $Duration = null;
    protected ?string $CompleteTillAt = null;
    protected ?string $CompletedAt = null;
    protected ?string $Result = null;
    protected int $IsCompleted;
    protected ?int $GroupId = null;
    protected int $IsDeleted;
    protected ?string $CreatedAt = null;
    protected ?string $UpdatedAt = null;

    public static function getTableName(): string { return 'amocrm_tasks'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'task_id' => $this->TaskId,
            'name' => $this->Name,
            'responsible_user_id' => $this->ResponsibleUserId,
            'created_user_id' => $this->CreatedUserId,
            'modified_user_id' => $this->ModifiedUserId,
            'task_type_id' => $this->TaskTypeId,
            'entity_type' => $this->EntityType,
            'entity_id' => $this->EntityId,
            'duration' => $this->Duration,
            'complete_till_at' => $this->CompleteTillAt,
            'completed_at' => $this->CompletedAt,
            'result' => $this->Result,
            'is_completed' => $this->IsCompleted,
            'group_id' => $this->GroupId,
            'is_deleted' => $this->IsDeleted,
            'created_at' => $this->CreatedAt,
            'updated_at' => $this->UpdatedAt,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getTaskId(): int { return $this->TaskId; }
    public function setTaskId(int $v): self { $this->TaskId = $v; return $this; }
    public function getName(): ?string { return $this->Name; }
    public function setName(?string $v): self { $this->Name = $v; return $this; }
    public function getResponsibleUserId(): ?int { return $this->ResponsibleUserId; }
    public function setResponsibleUserId(?int $v): self { $this->ResponsibleUserId = $v; return $this; }
    public function getCreatedUserId(): ?int { return $this->CreatedUserId; }
    public function setCreatedUserId(?int $v): self { $this->CreatedUserId = $v; return $this; }
    public function getModifiedUserId(): ?int { return $this->ModifiedUserId; }
    public function setModifiedUserId(?int $v): self { $this->ModifiedUserId = $v; return $this; }
    public function getTaskTypeId(): ?int { return $this->TaskTypeId; }
    public function setTaskTypeId(?int $v): self { $this->TaskTypeId = $v; return $this; }
    public function getEntityType(): ?string { return $this->EntityType; }
    public function setEntityType(?string $v): self { $this->EntityType = $v; return $this; }
    public function getEntityId(): ?int { return $this->EntityId; }
    public function setEntityId(?int $v): self { $this->EntityId = $v; return $this; }
    public function getDuration(): ?int { return $this->Duration; }
    public function setDuration(?int $v): self { $this->Duration = $v; return $this; }
    public function getCompleteTillAt(): ?string { return $this->CompleteTillAt; }
    public function setCompleteTillAt(?string $v): self { $this->CompleteTillAt = $v; return $this; }
    public function getCompletedAt(): ?string { return $this->CompletedAt; }
    public function setCompletedAt(?string $v): self { $this->CompletedAt = $v; return $this; }
    public function getResult(): ?string { return $this->Result; }
    public function setResult(?string $v): self { $this->Result = $v; return $this; }
    public function getIsCompleted(): int { return $this->IsCompleted; }
    public function setIsCompleted(int $v): self { $this->IsCompleted = $v; return $this; }
    public function getGroupId(): ?int { return $this->GroupId; }
    public function setGroupId(?int $v): self { $this->GroupId = $v; return $this; }
    public function getIsDeleted(): int { return $this->IsDeleted; }
    public function setIsDeleted(int $v): self { $this->IsDeleted = $v; return $this; }
    public function getCreatedAt(): ?string { return $this->CreatedAt; }
    public function setCreatedAt(?string $v): self { $this->CreatedAt = $v; return $this; }
    public function getUpdatedAt(): ?string { return $this->UpdatedAt; }
    public function setUpdatedAt(?string $v): self { $this->UpdatedAt = $v; return $this; }
}
