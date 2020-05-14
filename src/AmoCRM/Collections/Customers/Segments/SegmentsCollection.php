<?php

namespace AmoCRM\Collections\Customers\Segments;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\Customers\Segments\SegmentModel;

/**
 * Class SegmentsCollection
 *
 * @package AmoCRM\Collections\Customers\Segments
 *
 * @method SegmentModel current() : ?BaseApiModel
 * @method SegmentModel last() : ?BaseApiModel
 * @method SegmentModel first() : ?BaseApiModel
 * @method SegmentModel offsetGet($offset) : ?BaseApiModel
 * @method SegmentsCollection offsetSet($offset, SegmentModel $value) : BaseApiCollection
 * @method SegmentsCollection prepend(SegmentModel $value) : BaseApiCollection
 * @method SegmentsCollection add(SegmentModel $value) : BaseApiCollection
 * @method SegmentModel getBy($key, $value) : ?BaseApiModel
 */
class SegmentsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = SegmentModel::class;
}
