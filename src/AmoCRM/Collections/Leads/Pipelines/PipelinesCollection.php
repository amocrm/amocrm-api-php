<?php

namespace AmoCRM\Collections\Leads\Pipelines;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Leads\Pipelines\PipelineModel;

class PipelinesCollection extends BaseApiCollection
{
    public const ITEM_CLASS = PipelineModel::class;
}
