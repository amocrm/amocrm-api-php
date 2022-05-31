<?php

namespace AmoCRM\Collections\Customers\Segments;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Customers\Segments\SegmentModel;

/**
 * Class SegmentsCollection
 *
 * @package AmoCRM\Collections\Customers\Segments
 *
 * @method null|SegmentModel current()
 * @method null|SegmentModel last()
 * @method null|SegmentModel first()
 * @method null|SegmentModel offsetGet($offset)
 * @method void offsetSet($offset, SegmentModel $value)
 * @method SegmentsCollection prepend(SegmentModel $value)
 * @method SegmentsCollection add(SegmentModel $value)
 * @method null|SegmentModel getBy($key, $value)
 */
class SegmentsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = SegmentModel::class;
}
