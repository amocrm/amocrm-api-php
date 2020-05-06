<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\AccountSettings\Bot;

class WebhooksCollection extends BaseApiCollection
{
    protected $itemClass = Bot::class;
}
