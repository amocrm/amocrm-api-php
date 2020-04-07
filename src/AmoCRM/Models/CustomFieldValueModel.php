<?php

namespace AmoCRM\Models;

use AmoCRM\Factories\CustomFieldValueValuesFactory;
use InvalidArgumentException;

class CustomFieldValueModel extends BaseApiModel
{
    /**
     * @var int
     */
    protected $fieldId;

    /**
     * @var int
     */
    protected $fieldName;

    /**
     * @var int
     */
    protected $fieldType;

    /**
     * @var int
     */
    protected $fieldCode;

    /**
     * @var array
     */
    protected $values;

    /**
     * @param array $fieldsValue
     *
     * @return self
     */
    public static function fromArray(array $fieldsValue): self
    {
        if (empty($fieldsValue['field_id'])) {
            throw new InvalidArgumentException('Field id is empty in ' . json_encode($fieldsValue));
        }

        $fieldValue = new self();

        $values = [];
        foreach ($fieldsValue['values'] as $value) {
            $values[] = CustomFieldValueValuesFactory::createValue($value, $fieldsValue['field_type'])->toArray();
        }

        $fieldValue
            ->setFieldId((int)$fieldsValue['field_id'])
            ->setFieldName($fieldsValue['field_name'] ?? null)
            ->setFieldCode($fieldsValue['field_code'] ?? null)
            ->setFieldType($fieldsValue['field_type'])
            ->setValues($values); //todo collection?

        return $fieldValue;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = [
            'field_id' => $this->getFieldId(),
            'field_name' => $this->getFieldName(),
            'field_code' => $this->getFieldCode(),
            'field_type' => $this->getFieldType(),
            'values' => $this->getValues(),
        ];

        return $result;
    }

    /**
     * @return int
     */
    public function getFieldId(): int
    {
        return $this->fieldId;
    }

    /**
     * @param int $fieldId
     * @return CustomFieldValueModel
     */
    public function setFieldId(int $fieldId): self
    {
        $this->fieldId = $fieldId;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFieldName(): ?string
    {
        return $this->fieldName;
    }

    /**
     * @param string $fieldName
     * @return CustomFieldValueModel
     */
    public function setFieldName(string $fieldName): self
    {
        $this->fieldName = $fieldName;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFieldType(): ?string
    {
        return $this->fieldType;
    }

    /**
     * @param string $fieldType
     * @return CustomFieldValueModel
     */
    public function setFieldType(string $fieldType): self
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFieldCode(): ?string
    {
        return $this->fieldCode;
    }

    /**
     * @param null|string $fieldCode
     * @return CustomFieldValueModel
     */
    public function setFieldCode(?string $fieldCode): self
    {
        $this->fieldCode = $fieldCode;

        return $this;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param array $values
     * @return CustomFieldValueModel
     */
    public function setValues(array $values): self
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @param int|null $requestId
     * @return array
     */
    public function toApi(int $requestId = null): array
    {
        return $this->toArray();
    }
}
