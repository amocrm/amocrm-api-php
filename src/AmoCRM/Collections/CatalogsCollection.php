<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\CatalogModel;

class CatalogsCollection extends BaseApiCollection
{
    protected $itemClass = CatalogModel::class;
}
