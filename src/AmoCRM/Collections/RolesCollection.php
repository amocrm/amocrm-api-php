<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\RoleModel;

class RolesCollection extends BaseApiCollection
{
    protected $itemClass = RoleModel::class;
}
