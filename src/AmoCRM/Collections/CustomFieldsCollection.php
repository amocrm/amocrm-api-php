<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\CustomFieldModel;

class CustomFieldsCollection extends BaseApiCollection
{
    protected $itemClass = CustomFieldModel::class;
}
