<?php

namespace AmoCRM\Dwh\Models;

class CustomerMemberLinkDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $CustomerId;
    protected int $MemberId;
    protected string $MemberType;

    public static function getTableName(): string { return 'amocrm_customers_members'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'customer_id' => $this->CustomerId,
            'member_id' => $this->MemberId,
            'member_type' => $this->MemberType,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getCustomerId(): int { return $this->CustomerId; }
    public function setCustomerId(int $v): self { $this->CustomerId = $v; return $this; }
    public function getMemberId(): int { return $this->MemberId; }
    public function setMemberId(int $v): self { $this->MemberId = $v; return $this; }
    public function getMemberType(): string { return $this->MemberType; }
    public function setMemberType(string $v): self { $this->MemberType = $v; return $this; }
}
