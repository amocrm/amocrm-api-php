<?php

namespace AmoCRM\Factories;

use AmoCRM\Helpers\CustomFieldHelper;
use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\BaseEnumCodeCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\BaseEnumCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\BirthdayCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\CategoryCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\CheckboxCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\DateCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\DateTimeCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\ItemsCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\LegalEntityCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\MultiSelectCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\MultiTextCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\NumberCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\OrgLegalEntityCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\RadiobuttonCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\SelectCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\SmartAddressCustomFieldsValue;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldsValue;
use Illuminate\Support\Str;

class CustomFieldValueValuesFactory
{
    /**
     * @param array $value
     *
     * @param string $code
     * @return BaseCustomFieldsValue
     */
    public static function createValue(array $value, string $code): BaseCustomFieldsValue
    {
        $valueModel = null;
        $code = Str::upper($code);
        switch ($code) {
            case CustomFieldHelper::FIELD_TYPE_CODE_TEXT:
            case CustomFieldHelper::FIELD_TYPE_CODE_URL:
            case CustomFieldHelper::FIELD_TYPE_CODE_TEXTAREA:
            default:
                $valueModel = new TextCustomFieldsValue();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_NUMERIC:
                $valueModel = new NumberCustomFieldsValue();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_CHECKBOX:
                $valueModel = new CheckboxCustomFieldsValue();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_SELECT:
                $valueModel = new SelectCustomFieldsValue();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_MULTISELECT:
                $valueModel = new MultiSelectCustomFieldsValue();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_DATE:
                $valueModel = new DateCustomFieldsValue();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_MULTITEXT:
                $valueModel = new MultiTextCustomFieldsValue();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_RADIOBUTTON:
                $valueModel = new RadiobuttonCustomFieldsValue();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_SMART_ADDRESS:
                //todo fill enum for codes
                $valueModel = new SmartAddressCustomFieldsValue();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_BIRTHDAY:
                $valueModel = new BirthdayCustomFieldsValue();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_LEGAL_ENTITY:
                $valueModel = new LegalEntityCustomFieldsValue();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_ITEMS:
                //что-то для каталогов, вероятно не нужно в апи, вернем массивом, как есть
                $valueModel = new ItemsCustomFieldsValue();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_ORG_LEGAL_NAME:
                //что-то для америки, которое не используется, но в интерфейсе его можно выбрать
                $valueModel = new OrgLegalEntityCustomFieldsValue();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_CATEGORY:
                $valueModel = new CategoryCustomFieldsValue();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_DATE_TIME:
                $valueModel = new DateTimeCustomFieldsValue();
                break;
        }

        if ($valueModel instanceof BaseCustomFieldsValue) {
            $valueModel->setValue($value['value']);
        }

        if ($valueModel instanceof BaseEnumCustomFieldsValue) {
            //todo fix me
            if (empty($value['enum'])) {
                $value['enum'] = 1;
            }
            $valueModel->setEnumId($value['enum']);
        }

        if ($valueModel instanceof BaseEnumCodeCustomFieldsValue) {
            $valueModel->setEnumCode($value['subtype']);
        }

        return $valueModel;
    }
}
