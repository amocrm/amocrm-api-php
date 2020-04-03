<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\CustomFieldValueModel;

class CustomFieldsValuesCollection extends BaseApiCollection
{
    protected $itemClass = CustomFieldValueModel::class;
}
