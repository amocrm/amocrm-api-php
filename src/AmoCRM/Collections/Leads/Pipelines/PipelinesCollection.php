<?php

namespace AmoCRM\Collections\Leads\Pipelines;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Leads\Pipelines\PipelineModel;

class PipelinesCollection extends BaseApiCollection
{
    protected $itemClass = PipelineModel::class;
}
