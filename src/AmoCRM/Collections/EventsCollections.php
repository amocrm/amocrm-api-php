<?php

namespace AmoCRM\Collections;

use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\EventModel;

class EventsCollections extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    protected $itemClass = EventModel::class;
}
