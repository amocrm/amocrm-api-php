<?php

declare(strict_types=1);

namespace AmoCRM\Collections;

use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\EventTypeModel;

/**
 * Class EventTypesCollections
 *
 * @package AmoCRM\Collections
 *
 * @method null|EventTypeModel current()
 * @method null|EventTypeModel last()
 * @method null|EventTypeModel first()
 * @method null|EventTypeModel offsetGet($offset)
 * @method void offsetSet($offset, EventTypeModel $value)
 * @method EventsCollections prepend(EventTypeModel $value)
 * @method EventsCollections add(EventTypeModel $value)
 * @method null|EventTypeModel getBy($key, $value)
 */
class EventTypesCollections extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = EventTypeModel::class;
}
