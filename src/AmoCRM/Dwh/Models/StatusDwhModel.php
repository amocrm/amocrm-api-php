<?php

namespace AmoCRM\Dwh\Models;

class StatusDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $statusId;
    protected int $pipelineId;
    protected string $name;
    protected int $sort = 0;
    protected ?string $color = null;
    protected int $type = 0;
    protected int $editable = 1;
    protected ?string $createdAt = null;
    protected ?string $updatedAt = null;

    public static function getTableName(): string { return 'amocrm_statuses'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id, 'account_id' => $this->accountId,
            'status_id' => $this->statusId, 'pipeline_id' => $this->pipelineId,
            'name' => $this->name, 'sort' => $this->sort, 'color' => $this->color,
            'type' => $this->type, 'editable' => $this->editable,
            'created_at' => $this->createdAt, 'updated_at' => $this->updatedAt,
        ];
    }

    public function getId(): ?int { return $this->id; }
    public function getAccountId(): int { return $this->accountId; }
    public function getStatusId(): int { return $this->statusId; }
    public function getPipelineId(): int { return $this->pipelineId; }
    public function getName(): string { return $this->name; }
    public function getSort(): int { return $this->sort; }
    public function getColor(): ?string { return $this->color; }
    public function getType(): int { return $this->type; }
    public function getEditable(): int { return $this->editable; }
    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function getUpdatedAt(): ?string { return $this->updatedAt; }

    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function setStatusId(int $v): self { $this->statusId = $v; return $this; }
    public function setPipelineId(int $v): self { $this->pipelineId = $v; return $this; }
    public function setName(string $v): self { $this->name = $v; return $this; }
    public function setSort(int $v): self { $this->sort = $v; return $this; }
    public function setColor(?string $v): self { $this->color = $v; return $this; }
    public function setType(int $v): self { $this->type = $v; return $this; }
    public function setEditable(int $v): self { $this->editable = $v; return $this; }
    public function setCreatedAt(?string $v): self { $this->createdAt = $v; return $this; }
    public function setUpdatedAt(?string $v): self { $this->updatedAt = $v; return $this; }
}
