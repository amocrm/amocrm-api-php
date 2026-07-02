<?php

namespace AmoCRM\Dwh\Models;

class ClientIdDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected string $ClientId;
    protected ?string $Name = null;
    protected ?string $FirstSeenAt = null;
    protected ?string $LastSeenAt = null;
    protected ?string $CreatedAt = null;

    public static function getTableName(): string { return 'general_clientids'; }

    public function toArray(): array
    {
        return [
            'id' => \$this->id,
            'account_id' => \$this->accountId,
            'client_id' => \$this->ClientId,
            'name' => \$this->Name,
            'first_seen_at' => \$this->FirstSeenAt,
            'last_seen_at' => \$this->LastSeenAt,
            'created_at' => \$this->CreatedAt,
        ];
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getClientId(): string { return $this->ClientId; }
    public function setClientId(string $v): self { $this->ClientId = $v; return $this; }
    public function getName(): ?string { return $this->Name; }
    public function setName(?string $v): self { $this->Name = $v; return $this; }
    public function getFirstSeenAt(): ?string { return $this->FirstSeenAt; }
    public function setFirstSeenAt(?string $v): self { $this->FirstSeenAt = $v; return $this; }
    public function getLastSeenAt(): ?string { return $this->LastSeenAt; }
    public function setLastSeenAt(?string $v): self { $this->LastSeenAt = $v; return $this; }
    public function getCreatedAt(): ?string { return $this->CreatedAt; }
    public function setCreatedAt(?string $v): self { $this->CreatedAt = $v; return $this; }
}
