<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\CustomFieldGroupModel;

class CustomFieldGroupsCollection extends BaseApiCollection
{
    protected $itemClass = CustomFieldGroupModel::class;
}
