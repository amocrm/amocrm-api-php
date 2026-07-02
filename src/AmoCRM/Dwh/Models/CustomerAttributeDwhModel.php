<?php

namespace AmoCRM\Dwh\Models;

class CustomerAttributeDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $CustomerId;
    protected int $FieldId;
    protected ?string $FieldName = null;
    protected ?string $FieldType = null;
    protected ?string $FieldCode = null;
    protected ?string $Value = null;
    protected ?int $EnumId = null;
    protected ?string $EnumCode = null;

    public static function getTableName(): string { return 'amocrm_customers_attributes'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'customer_id' => $this->CustomerId,
            'field_id' => $this->FieldId,
            'field_name' => $this->FieldName,
            'field_type' => $this->FieldType,
            'field_code' => $this->FieldCode,
            'value' => $this->Value,
            'enum_id' => $this->EnumId,
            'enum_code' => $this->EnumCode,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getCustomerId(): int { return $this->CustomerId; }
    public function setCustomerId(int $v): self { $this->CustomerId = $v; return $this; }
    public function getFieldId(): int { return $this->FieldId; }
    public function setFieldId(int $v): self { $this->FieldId = $v; return $this; }
    public function getFieldName(): ?string { return $this->FieldName; }
    public function setFieldName(?string $v): self { $this->FieldName = $v; return $this; }
    public function getFieldType(): ?string { return $this->FieldType; }
    public function setFieldType(?string $v): self { $this->FieldType = $v; return $this; }
    public function getFieldCode(): ?string { return $this->FieldCode; }
    public function setFieldCode(?string $v): self { $this->FieldCode = $v; return $this; }
    public function getValue(): ?string { return $this->Value; }
    public function setValue(?string $v): self { $this->Value = $v; return $this; }
    public function getEnumId(): ?int { return $this->EnumId; }
    public function setEnumId(?int $v): self { $this->EnumId = $v; return $this; }
    public function getEnumCode(): ?string { return $this->EnumCode; }
    public function setEnumCode(?string $v): self { $this->EnumCode = $v; return $this; }
}
