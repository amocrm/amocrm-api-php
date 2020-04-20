<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\CustomerModel;

class CustomersCollection extends BaseApiCollection
{
    //TODO
    protected $itemClass = CustomerModel::class;
}
