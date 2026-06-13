<?php

declare(strict_types=1);

namespace Analytics\ETL;

use AmoCRM\Models\CustomFieldsValues\CustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\SelectCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\MultiselectCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValueModel;

/**
 * Extracts and normalizes custom field values
 */
class CustomFieldExtractor
{
    /**
     * Known UTM field mappings
     */
    private const UTM_FIELDS = [
        'utm_source' => 'source',
        'utm_medium' => 'medium',
        'utm_campaign' => 'campaign',
        'utm_term' => 'term',
        'utm_content' => 'content',
    ];

    /**
     * Extract all custom fields from amoCRM model
     */
    public function extractFromLeadModel(\AmoCRM\Models\LeadModel $lead): array
    {
        $fields = [];
        $cfValues = $lead->getCustomFieldsValues();

        if ($cfValues === null) {
            return $fields;
        }

        foreach ($cfValues as $customField) {
            $fieldId = $customField->getFieldId();
            $fieldName = $this->getFieldName($customField);
            $values = $this->extractValues($customField);

            $fields[$fieldId] = [
                'name' => $fieldName,
                'values' => $values,
            ];

            // Also store by UTM key if applicable
            $utmKey = $this->getUtmKey($fieldName);
            if ($utmKey !== null) {
                $fields['utm_' . $utmKey] = [
                    'name' => $fieldName,
                    'values' => $values,
                    'is_utm' => true,
                ];
            }
        }

        return $fields;
    }

    /**
     * Extract field name from custom field
     */
    private function getFieldName(CustomFieldValueModel $field): string
    {
        // The name is typically stored in the field metadata
        // For now, return field ID as string
        return 'field_' . $field->getFieldId();
    }

    /**
     * Extract values from custom field
     */
    private function extractValues(CustomFieldValueModel $field): array
    {
        $values = [];

        foreach ($field->getValues() as $value) {
            $values[] = $value->getValue();
        }

        return $values;
    }

    /**
     * Check if field name is a UTM field
     */
    private function getUtmKey(string $fieldName): ?string
    {
        $normalized = strtolower(trim($fieldName));
        
        foreach (self::UTM_FIELDS as $utmKey => $suffix) {
            if (str_contains($normalized, $utmKey)) {
                return $suffix;
            }
        }

        return null;
    }

    /**
     * Extract UTM parameters from custom fields
     */
    public function extractUtmParams(array $customFields): array
    {
        $utm = [
            'utm_source' => null,
            'utm_medium' => null,
            'utm_campaign' => null,
            'utm_term' => null,
            'utm_content' => null,
        ];

        foreach ($customFields as $fieldId => $field) {
            if (is_array($field) && isset($field['is_utm']) && $field['is_utm']) {
                $values = $field['values'] ?? [];
                if (!empty($values)) {
                    $key = 'utm_' . $field['name'];
                    if (array_key_exists($key, $utm)) {
                        $utm[$key] = $values[0];
                    }
                }
            }
        }

        return $utm;
    }

    /**
     * Normalize field value based on type
     */
    public function normalizeValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'numeric', 'price' => is_numeric($value) ? (float)$value : null,
            'select', 'multiselect' => is_string($value) ? trim($value) : null,
            'date' => $this->normalizeDate($value),
            'checkbox' => (bool)$value,
            'text', 'textarea' => is_string($value) ? trim($value) : null,
            default => is_string($value) ? trim($value) : $value,
        };
    }

    /**
     * Normalize date value
     */
    private function normalizeDate(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_numeric($value)) {
            return date('Y-m-d', (int)$value);
        }

        if (is_string($value)) {
            try {
                $date = new \DateTime($value);
                return $date->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * Build filter condition for custom field
     */
    public function buildFieldFilter(int $fieldId, string $operator, mixed $value): array
    {
        $condition = match ($operator) {
            '=', 'eq' => ['eq' => $value],
            '!=', 'ne' => ['ne' => $value],
            '>', 'gt' => ['gt' => $value],
            '>=', 'gte' => ['gte' => $value],
            '<', 'lt' => ['lt' => $value],
            '<=', 'lte' => ['lte' => $value],
            'contains' => ['contains' => $value],
            'like' => ['like' => '%' . $value . '%'],
            'in' => ['in' => (array)$value],
            default => ['eq' => $value],
        };

        return [
            'custom_fields_values' => [
                $fieldId => $condition,
            ],
        ];
    }

    /**
     * Map custom field to database column
     */
    public function mapToDatabaseColumn(array $customFields, array $mapping): array
    {
        $result = [];

        foreach ($mapping as $dbColumn => $fieldSpec) {
            if (is_array($fieldSpec)) {
                $fieldId = $fieldSpec['field_id'] ?? null;
                $type = $fieldSpec['type'] ?? 'text';
                $default = $fieldSpec['default'] ?? null;
            } else {
                $fieldId = $fieldSpec;
                $type = 'text';
                $default = null;
            }

            if ($fieldId === null) {
                $result[$dbColumn] = $default;
                continue;
            }

            $value = $customFields[$fieldId]['values'][0] ?? $default;
            $result[$dbColumn] = $this->normalizeValue($value, $type);
        }

        return $result;
    }
}