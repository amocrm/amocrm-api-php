<?php

namespace AmoCRM\Dwh\Models;

class TrafficDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected string $TrafficId;
    protected ?string $Name = null;
    protected ?string $Source = null;
    protected ?string $Medium = null;
    protected ?string $Campaign = null;
    protected ?string $Term = null;
    protected ?string $Content = null;
    protected ?string $CreatedAt = null;

    public static function getTableName(): string { return 'general_traffic'; }

    public function toArray(): array
    {
        return [
            'id' => \$this->id,
            'account_id' => \$this->accountId,
            'traffic_id' => \$this->TrafficId,
            'name' => \$this->Name,
            'source' => \$this->Source,
            'medium' => \$this->Medium,
            'campaign' => \$this->Campaign,
            'term' => \$this->Term,
            'content' => \$this->Content,
            'created_at' => \$this->CreatedAt,
        ];
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getTrafficId(): string { return $this->TrafficId; }
    public function setTrafficId(string $v): self { $this->TrafficId = $v; return $this; }
    public function getName(): ?string { return $this->Name; }
    public function setName(?string $v): self { $this->Name = $v; return $this; }
    public function getSource(): ?string { return $this->Source; }
    public function setSource(?string $v): self { $this->Source = $v; return $this; }
    public function getMedium(): ?string { return $this->Medium; }
    public function setMedium(?string $v): self { $this->Medium = $v; return $this; }
    public function getCampaign(): ?string { return $this->Campaign; }
    public function setCampaign(?string $v): self { $this->Campaign = $v; return $this; }
    public function getTerm(): ?string { return $this->Term; }
    public function setTerm(?string $v): self { $this->Term = $v; return $this; }
    public function getContent(): ?string { return $this->Content; }
    public function setContent(?string $v): self { $this->Content = $v; return $this; }
    public function getCreatedAt(): ?string { return $this->CreatedAt; }
    public function setCreatedAt(?string $v): self { $this->CreatedAt = $v; return $this; }
}
