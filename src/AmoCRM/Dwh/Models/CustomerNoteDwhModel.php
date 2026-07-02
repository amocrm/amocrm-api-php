<?php

namespace AmoCRM\Dwh\Models;

class CustomerNoteDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $CustomerId;
    protected int $NoteId;
    protected ?string $NoteType = null;
    protected ?int $ElementId = null;
    protected ?string $ElementType = null;
    protected ?int $ResponsibleUserId = null;
    protected ?string $Text = null;
    protected ?string $Params = null;
    protected ?int $GroupId = null;
    protected ?int $CreatedUserId = null;
    protected ?int $ModifiedUserId = null;
    protected ?string $CreatedAt = null;
    protected ?string $UpdatedAt = null;

    public static function getTableName(): string { return 'amocrm_customers_notes'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'customer_id' => $this->CustomerId,
            'note_id' => $this->NoteId,
            'note_type' => $this->NoteType,
            'element_id' => $this->ElementId,
            'element_type' => $this->ElementType,
            'responsible_user_id' => $this->ResponsibleUserId,
            'text' => $this->Text,
            'params' => $this->Params,
            'group_id' => $this->GroupId,
            'created_user_id' => $this->CreatedUserId,
            'modified_user_id' => $this->ModifiedUserId,
            'created_at' => $this->CreatedAt,
            'updated_at' => $this->UpdatedAt,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getCustomerId(): int { return $this->CustomerId; }
    public function setCustomerId(int $v): self { $this->CustomerId = $v; return $this; }
    public function getNoteId(): int { return $this->NoteId; }
    public function setNoteId(int $v): self { $this->NoteId = $v; return $this; }
    public function getNoteType(): ?string { return $this->NoteType; }
    public function setNoteType(?string $v): self { $this->NoteType = $v; return $this; }
    public function getElementId(): ?int { return $this->ElementId; }
    public function setElementId(?int $v): self { $this->ElementId = $v; return $this; }
    public function getElementType(): ?string { return $this->ElementType; }
    public function setElementType(?string $v): self { $this->ElementType = $v; return $this; }
    public function getResponsibleUserId(): ?int { return $this->ResponsibleUserId; }
    public function setResponsibleUserId(?int $v): self { $this->ResponsibleUserId = $v; return $this; }
    public function getText(): ?string { return $this->Text; }
    public function setText(?string $v): self { $this->Text = $v; return $this; }
    public function getParams(): ?string { return $this->Params; }
    public function setParams(?string $v): self { $this->Params = $v; return $this; }
    public function getGroupId(): ?int { return $this->GroupId; }
    public function setGroupId(?int $v): self { $this->GroupId = $v; return $this; }
    public function getCreatedUserId(): ?int { return $this->CreatedUserId; }
    public function setCreatedUserId(?int $v): self { $this->CreatedUserId = $v; return $this; }
    public function getModifiedUserId(): ?int { return $this->ModifiedUserId; }
    public function setModifiedUserId(?int $v): self { $this->ModifiedUserId = $v; return $this; }
    public function getCreatedAt(): ?string { return $this->CreatedAt; }
    public function setCreatedAt(?string $v): self { $this->CreatedAt = $v; return $this; }
    public function getUpdatedAt(): ?string { return $this->UpdatedAt; }
    public function setUpdatedAt(?string $v): self { $this->UpdatedAt = $v; return $this; }
}
