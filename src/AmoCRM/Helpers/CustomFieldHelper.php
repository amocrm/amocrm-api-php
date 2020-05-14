<?php

namespace AmoCRM\Helpers;

use AmoCRM\Models\CustomFields\CustomFieldModel;

/**
 * Class CustomFieldHelper
 *
 * @package AmoCRM\Helpers
 * @deprecated
 */
class CustomFieldHelper
{
    const FIELD_TYPE_CODE_TEXT = CustomFieldModel::TYPE_TEXT;
    const FIELD_TYPE_CODE_NUMERIC = CustomFieldModel::TYPE_NUMERIC;
    const FIELD_TYPE_CODE_CHECKBOX = CustomFieldModel::TYPE_CHECKBOX;
    const FIELD_TYPE_CODE_SELECT = CustomFieldModel::TYPE_SELECT;
    const FIELD_TYPE_CODE_MULTISELECT = CustomFieldModel::TYPE_MULTISELECT;
    const FIELD_TYPE_CODE_DATE = CustomFieldModel::TYPE_DATE;
    const FIELD_TYPE_CODE_DATE_TIME = CustomFieldModel::TYPE_DATE_TIME;
    const FIELD_TYPE_CODE_URL = CustomFieldModel::TYPE_URL;
    const FIELD_TYPE_CODE_MULTITEXT = CustomFieldModel::TYPE_MULTITEXT;
    const FIELD_TYPE_CODE_TEXTAREA = CustomFieldModel::TYPE_TEXTAREA;
    const FIELD_TYPE_CODE_RADIOBUTTON = CustomFieldModel::TYPE_RADIOBUTTON;
    const FIELD_TYPE_CODE_STREETADDRESS = CustomFieldModel::TYPE_STREET_ADDRESS;
    const FIELD_TYPE_CODE_SMART_ADDRESS = CustomFieldModel::TYPE_SMART_ADDRESS;
    const FIELD_TYPE_CODE_BIRTHDAY = CustomFieldModel::TYPE_BIRTHDAY;
    const FIELD_TYPE_CODE_ITEMS = CustomFieldModel::TYPE_ITEMS;
    const FIELD_TYPE_CODE_CATEGORY = CustomFieldModel::TYPE_CATEGORY;
    const FIELD_TYPE_CODE_LEGAL_ENTITY = CustomFieldModel::TYPE_LEGAL_ENTITY;
}
