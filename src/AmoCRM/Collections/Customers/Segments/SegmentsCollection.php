<?php

namespace AmoCRM\Collections\Customers\Segments;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\Customers\Segments\SegmentModel;

class SegmentsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    protected $itemClass = SegmentModel::class;
}
