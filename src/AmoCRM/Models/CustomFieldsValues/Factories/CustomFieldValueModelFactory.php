<?php

namespace AmoCRM\Models\CustomFieldsValues\Factories;

use AmoCRM\Models\CustomFieldsValues\ValueModels\ChainedListCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\FileCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\LinkedEntityCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MonetaryCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TrackingDataCustomFieldValueModel;
use AmoCRM\Helpers\CustomFieldHelper;
use AmoCRM\Models\CustomFields\CustomFieldModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\BaseCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\BirthdayCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\CategoryCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\CheckboxCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\DateCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\DateTimeCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\ItemsCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\LegalEntityCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultiselectCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\OrgLegalNameCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\PriceCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\RadiobuttonCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\SelectCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\SmartAddressCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\StreetAdressCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextareaCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\UrlCustomFieldValueModel;

use function trigger_error;

use const E_USER_NOTICE;

/**
 * Class CustomFieldValueModelFactory
 *
 * @package AmoCRM\Models\CustomFieldsValues\Factories
 */
class CustomFieldValueModelFactory
{
    /**
     * @param array $field
     *
     * @return BaseCustomFieldValueModel
     */
    public static function createModel(array $field): BaseCustomFieldValueModel
    {
        $fieldType = $field['field_type'] ?? null;

        switch ($fieldType) {
            case CustomFieldHelper::FIELD_TYPE_CODE_BIRTHDAY:
                $model = new BirthdayCustomFieldValueModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_CHECKBOX:
                $model = new CheckboxCustomFieldValueModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_DATE:
                $model = new DateCustomFieldValueModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_DATE_TIME:
                $model = new DateTimeCustomFieldValueModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_LEGAL_ENTITY:
                $model = new LegalEntityCustomFieldValueModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_MULTISELECT:
                $model = new MultiselectCustomFieldValueModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_MULTITEXT:
                $model = new MultitextCustomFieldValueModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_NUMERIC:
                $model = new NumericCustomFieldValueModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_RADIOBUTTON:
                $model = new RadiobuttonCustomFieldValueModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_SELECT:
                $model = new SelectCustomFieldValueModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_SMART_ADDRESS:
                $model = new SmartAddressCustomFieldValueModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_STREETADDRESS:
                $model = new StreetAdressCustomFieldValueModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_TEXTAREA:
                $model = new TextareaCustomFieldValueModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_TEXT:
                $model = new TextCustomFieldValueModel();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_URL:
                $model = new UrlCustomFieldValueModel();
                break;
            case CustomFieldModel::TYPE_ITEMS:
                $model = new ItemsCustomFieldValueModel();
                break;
            case CustomFieldModel::TYPE_CATEGORY:
                $model = new CategoryCustomFieldValueModel();
                break;
            case CustomFieldModel::TYPE_PRICE:
                $model = new PriceCustomFieldValueModel();
                break;
            case CustomFieldModel::TYPE_ORG_LEGAL_NAME:
                $model = new OrgLegalNameCustomFieldValueModel();
                break;
            case CustomFieldModel::TYPE_TRACKING_DATA:
                $model = new TrackingDataCustomFieldValueModel();
                break;
            case CustomFieldModel::TYPE_LINKED_ENTITY:
                $model = new LinkedEntityCustomFieldValueModel();
                break;
            case CustomFieldModel::TYPE_MONETARY:
                $model = new MonetaryCustomFieldValueModel();
                break;
            case CustomFieldModel::TYPE_CHAINED_LIST:
                $model = new ChainedListCustomFieldValueModel();
                break;
            case CustomFieldModel::TYPE_FILE:
                $model = new FileCustomFieldValueModel();
                break;
            default:
                trigger_error(
                    "Unprocessable field type '{$fieldType}'. Please upgrade amoCRM library.",
                    E_USER_NOTICE
                );
                $model = new BaseCustomFieldValueModel();
                break;
        }

        return $model;
    }
}
