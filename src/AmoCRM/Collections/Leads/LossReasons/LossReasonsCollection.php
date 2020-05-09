<?php

namespace AmoCRM\Collections\Leads\LossReasons;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Leads\LossReasons\LossReasonModel;

class LossReasonsCollection extends BaseApiCollection
{
    protected $itemClass = LossReasonModel::class;
}
