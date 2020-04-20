<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\SegmentModel;

class SegmentsCollection extends BaseApiCollection
{
    protected $itemClass = SegmentModel::class;
}
