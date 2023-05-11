<?php

namespace AmoCRM\Collections\Leads\Pipelines\Statuses;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Leads\Pipelines\Statuses\StatusDescriptionModel;

class StatusesDescriptionsCollection extends BaseApiCollection
{
    public const ITEM_CLASS = StatusDescriptionModel::class;
}
