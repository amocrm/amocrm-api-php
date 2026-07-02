<?php

namespace AmoCRM\Dwh\Models;

class PipelineDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $pipelineId;
    protected string $name;
    protected int $sort = 0;
    protected int $isMain = 0;
    protected int $isUnsortedOn = 0;
    protected int $isArchive = 0;
    protected ?string $createdAt = null;
    protected ?string $updatedAt = null;

    public static function getTableName(): string
    {
        return 'amocrm_pipelines';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'pipeline_id' => $this->pipelineId,
            'name' => $this->name,
            'sort' => $this->sort,
            'is_main' => $this->isMain,
            'is_unsorted_on' => $this->isUnsortedOn,
            'is_archive' => $this->isArchive,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }

    // getters/setters
    public function getId(): ?int { return $this->id; }
    public function getAccountId(): int { return $this->accountId; }
    public function getPipelineId(): int { return $this->pipelineId; }
    public function getName(): string { return $this->name; }
    public function getSort(): int { return $this->sort; }
    public function getIsMain(): int { return $this->isMain; }
    public function getIsUnsortedOn(): int { return $this->isUnsortedOn; }
    public function getIsArchive(): int { return $this->isArchive; }
    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function getUpdatedAt(): ?string { return $this->updatedAt; }

    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function setAccountId(int $accountId): self { $this->accountId = $accountId; return $this; }
    public function setPipelineId(int $pipelineId): self { $this->pipelineId = $pipelineId; return $this; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function setSort(int $sort): self { $this->sort = $sort; return $this; }
    public function setIsMain(int $isMain): self { $this->isMain = $isMain; return $this; }
    public function setIsUnsortedOn(int $isUnsortedOn): self { $this->isUnsortedOn = $isUnsortedOn; return $this; }
    public function setIsArchive(int $isArchive): self { $this->isArchive = $isArchive; return $this; }
    public function setCreatedAt(?string $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function setUpdatedAt(?string $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }
}
