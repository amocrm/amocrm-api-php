<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\EventModel;

class EventsCollections extends BaseApiCollection
{
    protected $itemClass = EventModel::class;
}
