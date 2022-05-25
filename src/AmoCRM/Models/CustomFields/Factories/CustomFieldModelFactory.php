<?php

namespace AmoCRM\Models\CustomFields\Factories;

use AmoCRM\Models\CustomFields\ChainedListCustomFieldModel;
use AmoCRM\Models\CustomFields\FileCustomFieldModel;
use AmoCRM\Models\CustomFields\MonetaryCustomFieldModel;
use AmoCRM\Models\CustomFields\OrgLegalNameCustomFieldModel;
use AmoCRM\Models\CustomFields\TrackingDataCustomFieldModel;
use AmoCRM\Models\CustomFields\BirthdayCustomFieldModel;
use AmoCRM\Models\CustomFields\CategoryCustomFieldModel;
use AmoCRM\Models\CustomFields\CheckboxCustomFieldModel;
use AmoCRM\Models\CustomFields\CustomFieldModel;
use AmoCRM\Models\CustomFields\DateCustomFieldModel;
use AmoCRM\Models\CustomFields\DateTimeCustomFieldModel;
use AmoCRM\Models\CustomFields\ItemsCustomFieldModel;
use AmoCRM\Models\CustomFields\LegalEntityCustomFieldModel;
use AmoCRM\Models\CustomFields\LinkedEntityCustomFieldModel;
use AmoCRM\Models\CustomFields\MultiselectCustomFieldModel;
use AmoCRM\Models\CustomFields\MultitextCustomFieldModel;
use AmoCRM\Models\CustomFields\NumericCustomFieldModel;
use AmoCRM\Models\CustomFields\PriceCustomFieldModel;
use AmoCRM\Models\CustomFields\RadiobuttonCustomFieldModel;
use AmoCRM\Models\CustomFields\SelectCustomFieldModel;
use AmoCRM\Models\CustomFields\SmartAddressCustomFieldModel;
use AmoCRM\Models\CustomFields\StreetAddressCustomFieldModel;
use AmoCRM\Models\CustomFields\TextareaCustomFieldModel;
use AmoCRM\Models\CustomFields\TextCustomFieldModel;
use AmoCRM\Models\CustomFields\UrlCustomFieldModel;

use function trigger_error;

use const E_USER_NOTICE;

/**
 * Class CustomFieldModelFactory
 *
 * @package AmoCRM\Models\CustomFieldsValues\Factories
 */
class CustomFieldModelFactory
{
    /**
     * @param array $field
     *
     * @return CustomFieldModel
     */
    public static function createModel(array $field): CustomFieldModel
    {
        $fieldType = $field['type'] ?? null;

        switch ($fieldType) {
            case CustomFieldModel::TYPE_BIRTHDAY:
                $model = BirthdayCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_CHECKBOX:
                $model = CheckboxCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_DATE:
                $model = DateCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_DATE_TIME:
                $model = DateTimeCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_LEGAL_ENTITY:
                $model = LegalEntityCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_ORG_LEGAL_NAME:
                $model = OrgLegalNameCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_MULTISELECT:
                $model = MultiselectCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_MULTITEXT:
                $model = MultitextCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_NUMERIC:
                $model = NumericCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_RADIOBUTTON:
                $model = RadiobuttonCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_SELECT:
                $model = SelectCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_SMART_ADDRESS:
                $model = SmartAddressCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_STREET_ADDRESS:
                $model = StreetAddressCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_TEXTAREA:
                $model = TextareaCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_TEXT:
                $model = TextCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_URL:
                $model = UrlCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_ITEMS:
                $model = ItemsCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_CATEGORY:
                $model = CategoryCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_PRICE:
                $model = PriceCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_TRACKING_DATA:
                $model = TrackingDataCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_LINKED_ENTITY:
                $model = LinkedEntityCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_MONETARY:
                $model = MonetaryCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_CHAINED_LIST:
                $model = ChainedListCustomFieldModel::fromArray($field);
                break;
            case CustomFieldModel::TYPE_FILE:
                $model = FileCustomFieldModel::fromArray($field);
                break;
            default:
                trigger_error(
                    "Unprocessable field type '{$fieldType}'. Please upgrade amoCRM library.",
                    E_USER_NOTICE
                );
                $model = CustomFieldModel::fromArray($field);
                break;
        }

        return $model;
    }

    /**
     * @param string $fieldType
     *
     * @return CustomFieldModel
     */
    public static function createEmptyModel(string $fieldType): CustomFieldModel
    {
        switch ($fieldType) {
            case CustomFieldModel::TYPE_BIRTHDAY:
                $model = new BirthdayCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_CHECKBOX:
                $model = new CheckboxCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_DATE:
                $model = new DateCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_DATE_TIME:
                $model = new DateTimeCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_LEGAL_ENTITY:
                $model = new LegalEntityCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_MULTISELECT:
                $model = new MultiselectCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_MULTITEXT:
                $model = new MultitextCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_NUMERIC:
                $model = new NumericCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_RADIOBUTTON:
                $model = new RadiobuttonCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_SELECT:
                $model = new SelectCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_SMART_ADDRESS:
                $model = new SmartAddressCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_STREET_ADDRESS:
                $model = new StreetAddressCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_TEXTAREA:
                $model = new TextareaCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_TEXT:
                $model = new TextCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_URL:
                $model = new UrlCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_ITEMS:
                $model = new ItemsCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_CATEGORY:
                $model = new CategoryCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_PRICE:
                $model = new PriceCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_TRACKING_DATA:
                $model = new TrackingDataCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_LINKED_ENTITY:
                $model = new LinkedEntityCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_MONETARY:
                $model = new MonetaryCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_CHAINED_LIST:
                $model = new ChainedListCustomFieldModel();
                break;
            case CustomFieldModel::TYPE_FILE:
                $model = new FileCustomFieldModel();
                break;
            default:
                trigger_error(
                    "Unprocessable field type '{$fieldType}'. Please upgrade amoCRM library.",
                    E_USER_NOTICE
                );
                $model = new CustomFieldModel();
                break;
        }

        return $model;
    }
}
