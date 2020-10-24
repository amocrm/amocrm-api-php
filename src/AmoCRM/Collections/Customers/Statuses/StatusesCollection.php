<?php

namespace AmoCRM\Collections\Customers\Statuses;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Customers\Statuses\StatusModel;

class StatusesCollection extends BaseApiCollection
{
    public const ITEM_CLASS = StatusModel::class;
}
