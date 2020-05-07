<?php

namespace AmoCRM\Collections\Leads\Pipelines\Statuses;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Leads\Pipelines\Statuses\StatusModel;

class StatusesCollection extends BaseApiCollection
{
    protected $itemClass = StatusModel::class;
}
