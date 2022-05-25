<?php

namespace AmoCRM\Models\CustomFieldsValues\Factories;

use AmoCRM\Models\CustomFieldsValues\ChainedListCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\FileCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\MonetaryCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TrackingDataCustomFieldValuesModel;
use AmoCRM\Models\CustomFields\CustomFieldModel;
use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\BirthdayCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\CategoryCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\CheckboxCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\DateCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\DateTimeCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ItemsCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\LegalEntityCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\LinkedEntityCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\MultiselectCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\OrgLegalNameCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\PriceCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\RadiobuttonCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\SelectCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\SmartAddressCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\StreetAddressCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextareaCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\UrlCustomFieldValuesModel;
use AmoCRM\Exceptions\BadTypeException;
use AmoCRM\Helpers\CustomFieldHelper;

/**
 * Class CustomFieldValuesModelFactory
 *
 * @package AmoCRM\Models\CustomFieldsValues\Factories
 */
class CustomFieldValuesModelFactory
{
    /**
     * @param array $field
     *
     * @return BaseCustomFieldValuesModel
     * @throws BadTypeException
     */
    public static function createModel(array $field): BaseCustomFieldValuesModel
    {
        $fieldType = $field['field_type'] ?? null;

        switch ($fieldType) {
            case CustomFieldHelper::FIELD_TYPE_CODE_BIRTHDAY:
                $model = new BirthdayCustomFieldValuesModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_CHECKBOX:
                $model = new CheckboxCustomFieldValuesModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_DATE:
                $model = new DateCustomFieldValuesModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_DATE_TIME:
                $model = new DateTimeCustomFieldValuesModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_LEGAL_ENTITY:
                $model = new LegalEntityCustomFieldValuesModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_MULTISELECT:
                $model = new MultiselectCustomFieldValuesModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_MULTITEXT:
                $model = new MultitextCustomFieldValuesModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_NUMERIC:
                $model = new NumericCustomFieldValuesModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_RADIOBUTTON:
                $model = new RadiobuttonCustomFieldValuesModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_SELECT:
                $model = new SelectCustomFieldValuesModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_SMART_ADDRESS:
                $model = new SmartAddressCustomFieldValuesModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_STREETADDRESS:
                $model = new StreetAddressCustomFieldValuesModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_TEXTAREA:
                $model = new TextareaCustomFieldValuesModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_TEXT:
                $model = new TextCustomFieldValuesModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_URL:
                $model = new UrlCustomFieldValuesModel();
                break;
            case CustomFieldModel::TYPE_ITEMS:
                $model = new ItemsCustomFieldValuesModel();
                break;
            case CustomFieldModel::TYPE_CATEGORY:
                $model = new CategoryCustomFieldValuesModel();
                break;
            case CustomFieldModel::TYPE_PRICE:
                $model = new PriceCustomFieldValuesModel();
                break;
            case CustomFieldModel::TYPE_ORG_LEGAL_NAME:
                $model = new OrgLegalNameCustomFieldValuesModel();
                break;
            case CustomFieldModel::TYPE_TRACKING_DATA:
                $model = new TrackingDataCustomFieldValuesModel();
                break;
            case CustomFieldModel::TYPE_LINKED_ENTITY:
                $model = new LinkedEntityCustomFieldValuesModel();
                break;
            case CustomFieldModel::TYPE_MONETARY:
                $model = new MonetaryCustomFieldValuesModel();
                break;
            case CustomFieldModel::TYPE_CHAINED_LIST:
                $model = new ChainedListCustomFieldValuesModel();
                break;
            case CustomFieldModel::TYPE_FILE:
                $model = new FileCustomFieldValuesModel();
                break;
            default:
                trigger_error(
                    "Unprocessable field type '{$fieldType}'. Please upgrade amoCRM library.",
                    E_USER_NOTICE
                );
                $model = new BaseCustomFieldValuesModel();
                break;
        }

        $values = CustomFieldValueCollectionFactory::createCollection($field);

        $model
            ->setValues($values)
            ->setFieldCode($field['field_code'] ?? null)
            ->setFieldId($field['field_id'] ?? null)
            ->setFieldName($field['field_name'] ?? null);

        return $model;
    }
}
