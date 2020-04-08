<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\CompanyModel;

class CompaniesCollection extends BaseApiCollection
{
    protected $itemClass = CompanyModel::class;
}
