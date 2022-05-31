<?php

namespace AmoCRM\Collections;

use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\EventModel;

/**
 * Class EventsCollections
 *
 * @package AmoCRM\Collections
 *
 * @method null|EventModel current()
 * @method null|EventModel last()
 * @method null|EventModel first()
 * @method null|EventModel offsetGet($offset)
 * @method void offsetSet($offset, EventModel $value)
 * @method EventsCollections prepend(EventModel $value)
 * @method EventsCollections add(EventModel $value)
 * @method null|EventModel getBy($key, $value)
 */
class EventsCollections extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = EventModel::class;
}
