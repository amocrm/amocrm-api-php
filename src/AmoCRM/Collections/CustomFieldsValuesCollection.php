<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel;

class CustomFieldsValuesCollection extends BaseApiCollection
{
    protected $itemClass = BaseCustomFieldValuesModel::class;
}
