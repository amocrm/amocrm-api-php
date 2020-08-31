<?php

namespace AmoCRM\Models\CustomFieldsValues;

use AmoCRM\Exceptions\BadTypeException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\CustomFieldsValues\Factories\CustomFieldValuesModelFactory;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\BaseCustomFieldValueCollection;

/**
 * Class BaseCustomFieldValuesModel
 *
 * @package AmoCRM\Models\CustomFieldsValues
 */
class BaseCustomFieldValuesModel extends BaseApiModel
{
    /**
     * @var int|null
     */
    protected $fieldId;

    /**
     * @var string|null
     */
    protected $fieldCode;

    /**
     * @var string|null
     */
    protected $fieldName;

    /**
     * @var BaseCustomFieldValueCollection
     */
    protected $values;

    /**
     * @param array $value
     *
     * @return BaseCustomFieldValuesModel
     * @throws BadTypeException
     */
    public static function fromArray(array $value): BaseCustomFieldValuesModel
    {
        return CustomFieldValuesModelFactory::createModel($value);
    }

    /**
     * @return int|null
     */
    public function getFieldId(): ?int
    {
        return $this->fieldId;
    }

    /**
     * @param int|null $fieldId
     *
     * @return BaseCustomFieldValuesModel
     */
    public function setFieldId(?int $fieldId): BaseCustomFieldValuesModel
    {
        $this->fieldId = $fieldId;

        return $this;
    }

    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return '';
    }

    /**
     * @return string|null
     */
    public function getFieldCode(): ?string
    {
        return $this->fieldCode;
    }

    /**
     * @param string|null $fieldCode
     *
     * @return BaseCustomFieldValuesModel
     */
    public function setFieldCode(?string $fieldCode): BaseCustomFieldValuesModel
    {
        $this->fieldCode = $fieldCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFieldName(): ?string
    {
        return $this->fieldName;
    }

    /**
     * @return BaseCustomFieldValueCollection
     */
    public function getValues(): ?BaseCustomFieldValueCollection
    {
        return $this->values;
    }

    /**
     * @param BaseCustomFieldValueCollection $values
     *
     * @return BaseCustomFieldValuesModel
     */
    public function setValues(BaseCustomFieldValueCollection $values): BaseCustomFieldValuesModel
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @param string|null $fieldName
     *
     * @return BaseCustomFieldValuesModel
     */
    public function setFieldName(?string $fieldName): BaseCustomFieldValuesModel
    {
        $this->fieldName = $fieldName;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'field_id' => $this->getFieldId(),
            'field_code' => $this->getFieldCode(),
            'field_name' => $this->getFieldName(),
            'field_type' => $this->getFieldType(),
            'values' => $this->getValues()->toArray(),
        ];
    }

    public function toApi(string $requestId = null): array
    {
        return [
            'field_id' => $this->getFieldId(),
            'field_code' => $this->getFieldCode(),
            'values' => $this->getValues()->toApi(),
        ];
    }
}
