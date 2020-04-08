<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\ContactModel;

class ContactsCollection extends BaseApiCollection
{
    protected $itemClass = ContactModel::class;
}
