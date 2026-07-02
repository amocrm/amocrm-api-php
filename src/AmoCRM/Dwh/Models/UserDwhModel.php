<?php

namespace AmoCRM\Dwh\Models;

class UserDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $UserId;
    protected string $Name;
    protected ?string $Email = null;
    protected ?string $Lang = null;
    protected ?string $PhoneNumber = null;
    protected ?string $Rights = null;
    protected ?int $RoleId = null;
    protected ?int $GroupId = null;
    protected int $IsAdmin;
    protected int $IsFree;
    protected int $IsActive;
    protected ?string $CreatedAt = null;
    protected ?string $UpdatedAt = null;

    public static function getTableName(): string { return 'amocrm_users'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'user_id' => $this->UserId,
            'name' => $this->Name,
            'email' => $this->Email,
            'lang' => $this->Lang,
            'phone_number' => $this->PhoneNumber,
            'rights' => $this->Rights,
            'role_id' => $this->RoleId,
            'group_id' => $this->GroupId,
            'is_admin' => $this->IsAdmin,
            'is_free' => $this->IsFree,
            'is_active' => $this->IsActive,
            'created_at' => $this->CreatedAt,
            'updated_at' => $this->UpdatedAt,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getUserId(): int { return $this->UserId; }
    public function setUserId(int $v): self { $this->UserId = $v; return $this; }
    public function getName(): string { return $this->Name; }
    public function setName(string $v): self { $this->Name = $v; return $this; }
    public function getEmail(): ?string { return $this->Email; }
    public function setEmail(?string $v): self { $this->Email = $v; return $this; }
    public function getLang(): ?string { return $this->Lang; }
    public function setLang(?string $v): self { $this->Lang = $v; return $this; }
    public function getPhoneNumber(): ?string { return $this->PhoneNumber; }
    public function setPhoneNumber(?string $v): self { $this->PhoneNumber = $v; return $this; }
    public function getRights(): ?string { return $this->Rights; }
    public function setRights(?string $v): self { $this->Rights = $v; return $this; }
    public function getRoleId(): ?int { return $this->RoleId; }
    public function setRoleId(?int $v): self { $this->RoleId = $v; return $this; }
    public function getGroupId(): ?int { return $this->GroupId; }
    public function setGroupId(?int $v): self { $this->GroupId = $v; return $this; }
    public function getIsAdmin(): int { return $this->IsAdmin; }
    public function setIsAdmin(int $v): self { $this->IsAdmin = $v; return $this; }
    public function getIsFree(): int { return $this->IsFree; }
    public function setIsFree(int $v): self { $this->IsFree = $v; return $this; }
    public function getIsActive(): int { return $this->IsActive; }
    public function setIsActive(int $v): self { $this->IsActive = $v; return $this; }
    public function getCreatedAt(): ?string { return $this->CreatedAt; }
    public function setCreatedAt(?string $v): self { $this->CreatedAt = $v; return $this; }
    public function getUpdatedAt(): ?string { return $this->UpdatedAt; }
    public function setUpdatedAt(?string $v): self { $this->UpdatedAt = $v; return $this; }
}
