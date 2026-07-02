<?php

namespace AmoCRM\Dwh\Collections;

use AmoCRM\Dwh\Models\ContactDwhModel;

class ContactDwhCollection extends BaseDwhCollection
{
    public const ITEM_CLASS = ContactDwhModel::class;
}
