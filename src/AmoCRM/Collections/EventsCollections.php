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
 * @method EventModel current() : ?BaseApiModel
 * @method EventModel last() : ?BaseApiModel
 * @method EventModel first() : ?BaseApiModel
 * @method EventModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, EventModel $value) : BaseApiCollection
 * @method self prepend(EventModel $value) : BaseApiCollection
 * @method self add(EventModel $value) : BaseApiCollection
 * @method EventModel getBy($key, $value) : ?BaseApiModel
 */
class EventsCollections extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = EventModel::class;
}
