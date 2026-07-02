<?php

namespace AmoCRM\Dwh\Collections;

use AmoCRM\Dwh\Models\CustomerDwhModel;

class CustomerDwhCollection extends BaseDwhCollection
{
    public const ITEM_CLASS = CustomerDwhModel::class;
}
