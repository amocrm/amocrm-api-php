<?php

namespace AmoCRM\Models\CustomFieldsValues\Factories;

use AmoCRM\Models\CustomFieldsValues\ValueCollections\ChainedListCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\FileCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MonetaryCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\LinkedEntityCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TrackingDataCustomFieldValueCollection;
use AmoCRM\Helpers\CustomFieldHelper;
use AmoCRM\Models\CustomFields\CustomFieldModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\BaseCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\BirthdayCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\CategoryCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\CheckboxCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\DateCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\DateTimeCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\ItemsCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\LegalEntityCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultiselectCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NumericCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\OrgLegalNameCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\PriceCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\RadiobuttonCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\SelectCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\SmartAddressCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\StreetAddressCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextareaCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\UrlCustomFieldValueCollection;

use function trigger_error;

use const E_NOTICE;

/**
 * Class CustomFieldValueCollectionFactory
 *
 * @package AmoCRM\Models\CustomFieldsValues\Factories
 */
class CustomFieldValueCollectionFactory
{
    /**
     * @param array $field
     *
     * @return BaseCustomFieldValueCollection
     */
    public static function createCollection(array $field): BaseCustomFieldValueCollection
    {
        $fieldType = $field['field_type'] ?? null;

        switch ($fieldType) {
            case CustomFieldHelper::FIELD_TYPE_CODE_BIRTHDAY:
                $collection = new BirthdayCustomFieldValueCollection();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_CHECKBOX:
                $collection = new CheckboxCustomFieldValueCollection();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_DATE:
                $collection = new DateCustomFieldValueCollection();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_DATE_TIME:
                $collection = new DateTimeCustomFieldValueCollection();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_LEGAL_ENTITY:
                $collection = new LegalEntityCustomFieldValueCollection();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_MULTISELECT:
                $collection = new MultiselectCustomFieldValueCollection();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_MULTITEXT:
                $collection = new MultitextCustomFieldValueCollection();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_NUMERIC:
                $collection = new NumericCustomFieldValueCollection();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_RADIOBUTTON:
                $collection = new RadiobuttonCustomFieldValueCollection();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_SELECT:
                $collection = new SelectCustomFieldValueCollection();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_SMART_ADDRESS:
                $collection = new SmartAddressCustomFieldValueCollection();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_STREETADDRESS:
                $collection = new StreetAddressCustomFieldValueCollection();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_TEXTAREA:
                $collection = new TextareaCustomFieldValueCollection();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_TEXT:
                $collection = new TextCustomFieldValueCollection();
                break;
            case CustomFieldHelper::FIELD_TYPE_CODE_URL:
                $collection = new UrlCustomFieldValueCollection();
                break;
            case CustomFieldModel::TYPE_ITEMS:
                $collection = new ItemsCustomFieldValueCollection();
                break;
            case CustomFieldModel::TYPE_CATEGORY:
                $collection = new CategoryCustomFieldValueCollection();
                break;
            case CustomFieldModel::TYPE_PRICE:
                $collection = new PriceCustomFieldValueCollection();
                break;
            case CustomFieldModel::TYPE_ORG_LEGAL_NAME:
                $collection = new OrgLegalNameCustomFieldValueCollection();
                break;
            case CustomFieldModel::TYPE_TRACKING_DATA:
                $collection = new TrackingDataCustomFieldValueCollection();
                break;
            case CustomFieldModel::TYPE_LINKED_ENTITY:
                $collection = new LinkedEntityCustomFieldValueCollection();
                break;
            case CustomFieldModel::TYPE_MONETARY:
                $collection = new MonetaryCustomFieldValueCollection();
                break;
            case CustomFieldModel::TYPE_CHAINED_LIST:
                $collection = new ChainedListCustomFieldValueCollection();
                break;
            case CustomFieldModel::TYPE_FILE:
                $collection = new FileCustomFieldValueCollection();
                break;
            default:
                trigger_error(
                    "Unprocessable field type '{$fieldType}'. Please upgrade amoCRM library.",
                    E_USER_NOTICE
                );
                $collection = new BaseCustomFieldValueCollection();
                break;
        }

        foreach ($field['values'] as $value) {
            $valueModel = CustomFieldValueModelFactory::createModel($field);
            $valueModel = $valueModel::fromArray($value);
            $collection->add($valueModel);
        }

        return $collection;
    }
}
