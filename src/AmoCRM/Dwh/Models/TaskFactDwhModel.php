<?php

namespace AmoCRM\Dwh\Models;

class TaskFactDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $TaskId;
    protected ?int $DateCreateId = null;
    protected ?int $DateCompleteId = null;
    protected ?string $EntityType = null;
    protected ?int $EntityId = null;
    protected ?int $ResponsibleUserId = null;
    protected ?int $Duration = null;
    protected int $IsCompleted;

    public static function getTableName(): string { return 'amocrm_tasks_facts'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'task_id' => $this->TaskId,
            'date_create_id' => $this->DateCreateId,
            'date_complete_id' => $this->DateCompleteId,
            'entity_type' => $this->EntityType,
            'entity_id' => $this->EntityId,
            'responsible_user_id' => $this->ResponsibleUserId,
            'duration' => $this->Duration,
            'is_completed' => $this->IsCompleted,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getTaskId(): int { return $this->TaskId; }
    public function setTaskId(int $v): self { $this->TaskId = $v; return $this; }
    public function getDateCreateId(): ?int { return $this->DateCreateId; }
    public function setDateCreateId(?int $v): self { $this->DateCreateId = $v; return $this; }
    public function getDateCompleteId(): ?int { return $this->DateCompleteId; }
    public function setDateCompleteId(?int $v): self { $this->DateCompleteId = $v; return $this; }
    public function getEntityType(): ?string { return $this->EntityType; }
    public function setEntityType(?string $v): self { $this->EntityType = $v; return $this; }
    public function getEntityId(): ?int { return $this->EntityId; }
    public function setEntityId(?int $v): self { $this->EntityId = $v; return $this; }
    public function getResponsibleUserId(): ?int { return $this->ResponsibleUserId; }
    public function setResponsibleUserId(?int $v): self { $this->ResponsibleUserId = $v; return $this; }
    public function getDuration(): ?int { return $this->Duration; }
    public function setDuration(?int $v): self { $this->Duration = $v; return $this; }
    public function getIsCompleted(): int { return $this->IsCompleted; }
    public function setIsCompleted(int $v): self { $this->IsCompleted = $v; return $this; }
}
