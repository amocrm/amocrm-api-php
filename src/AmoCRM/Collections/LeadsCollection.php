<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\LeadModel;

class LeadsCollection extends BaseApiCollection
{
    protected $itemClass = LeadModel::class;
}
