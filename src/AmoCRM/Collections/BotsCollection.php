<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\AccountSettings\Bot;

class BotsCollection extends BaseApiCollection
{
    protected $itemClass = Bot::class;
}
