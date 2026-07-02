<?php

namespace AmoCRM\Dwh\Models;

class PeriodicityDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $PeriodicityId;
    protected string $Name;
    protected int $Sort;
    protected ?string $Color = null;
    protected ?string $CreatedAt = null;
    protected ?string $UpdatedAt = null;

    public static function getTableName(): string { return 'amocrm_periodicity'; }

    public function toArray(): array
    {
        return [
            'id' => \$this->id,
            'account_id' => \$this->accountId,
            'periodicity_id' => \$this->PeriodicityId,
            'name' => \$this->Name,
            'sort' => \$this->Sort,
            'color' => \$this->Color,
            'created_at' => \$this->CreatedAt,
            'updated_at' => \$this->UpdatedAt,
        ];
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getPeriodicityId(): int { return $this->PeriodicityId; }
    public function setPeriodicityId(int $v): self { $this->PeriodicityId = $v; return $this; }
    public function getName(): string { return $this->Name; }
    public function setName(string $v): self { $this->Name = $v; return $this; }
    public function getSort(): int { return $this->Sort; }
    public function setSort(int $v): self { $this->Sort = $v; return $this; }
    public function getColor(): ?string { return $this->Color; }
    public function setColor(?string $v): self { $this->Color = $v; return $this; }
    public function getCreatedAt(): ?string { return $this->CreatedAt; }
    public function setCreatedAt(?string $v): self { $this->CreatedAt = $v; return $this; }
    public function getUpdatedAt(): ?string { return $this->UpdatedAt; }
    public function setUpdatedAt(?string $v): self { $this->UpdatedAt = $v; return $this; }
}
