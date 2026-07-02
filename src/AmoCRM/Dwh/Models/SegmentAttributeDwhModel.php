<?php

namespace AmoCRM\Dwh\Models;

class SegmentAttributeDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $SegmentId;
    protected int $FieldId;
    protected ?string $FieldName = null;
    protected ?string $FieldType = null;
    protected ?string $FieldCode = null;
    protected ?string $Value = null;

    public static function getTableName(): string { return 'amocrm_segments_attributes'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'segment_id' => $this->SegmentId,
            'field_id' => $this->FieldId,
            'field_name' => $this->FieldName,
            'field_type' => $this->FieldType,
            'field_code' => $this->FieldCode,
            'value' => $this->Value,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getSegmentId(): int { return $this->SegmentId; }
    public function setSegmentId(int $v): self { $this->SegmentId = $v; return $this; }
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
}
