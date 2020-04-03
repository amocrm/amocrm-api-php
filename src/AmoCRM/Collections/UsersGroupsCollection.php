<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\AccountSettings\UsersGroup;

class UsersGroupsCollection extends BaseApiCollection
{
    protected $itemClass = UsersGroup::class;
}
