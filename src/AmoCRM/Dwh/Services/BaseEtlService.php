<?php

namespace AmoCRM\Dwh\Services;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Dwh\DwhDbAdapter;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Dwh\Models\BaseDwhModel;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Helpers\EntityTypesInterface;

abstract class BaseEtlService
{
    protected AmoCRMApiClient $apiClient;
    protected DwhDbAdapter $db;
    protected int $accountId;

    public function __construct(AmoCRMApiClient $apiClient, DwhDbAdapter $db, int $accountId)
    {
        $this->apiClient = $apiClient;
        $this->db = $db;
        $this->accountId = $accountId;
    }

    abstract public function sync(array $options = []): array;

    /**
     * Extract custom fields values into attributes array for bulk insert.
     */
    protected function extractAttributes(?BaseApiCollection $customFieldsValues, int $entityId): array
    {
        if ($customFieldsValues === null || $customFieldsValues->isEmpty()) {
            return [];
        }

        $rows = [];
        foreach ($customFieldsValues as $fieldValues) {
            $fieldId = $fieldValues->getFieldId();
            $fieldName = $fieldValues->getFieldName();
            $fieldType = $fieldValues->getFieldType();
            $fieldCode = $fieldValues->getFieldCode();

            $values = $fieldValues->getValues();
            if ($values === null || $values->isEmpty()) {
                continue;
            }

            foreach ($values as $valueModel) {
                $rawValue = method_exists($valueModel, 'getValue') ? $valueModel->getValue() : '';
                $enumId = method_exists($valueModel, 'getEnumId') ? $valueModel->getEnumId() : null;
                $enumCode = method_exists($valueModel, 'getEnumCode') ? $valueModel->getEnumCode() : null;

                $rows[] = [
                    'account_id' => $this->accountId,
                    'field_id' => $fieldId,
                    'field_name' => $fieldName,
                    'field_type' => $fieldType,
                    'field_code' => $fieldCode,
                    'value' => $rawValue !== null ? (string)$rawValue : null,
                    'enum_id' => $enumId,
                    'enum_code' => $enumCode,
                ];
            }
        }

        return $rows;
    }

    /**
     * Extract tags into array for bulk insert.
     */
    protected function extractTags(?BaseApiCollection $tagsCollection, int $entityId): array
    {
        if ($tagsCollection === null || $tagsCollection->isEmpty()) {
            return [];
        }

        $rows = [];
        foreach ($tagsCollection as $tag) {
            $rows[] = [
                'account_id' => $this->accountId,
                'name' => $tag->getName(),
                'tag_id' => method_exists($tag, 'getId') ? $tag->getId() : null,
                'color' => method_exists($tag, 'getColor') ? $tag->getColor() : null,
            ];
        }

        return $rows;
    }

    /**
     * Transform datetime string from API format.
     */
    protected function toDateTimeString($value): ?string
    {
        if ($value === null) {
            return null;
        }
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        return (string)$value;
    }
}
