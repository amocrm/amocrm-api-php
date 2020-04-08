<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\CatalogElementModel;

class CatalogElementsCollection extends BaseApiCollection
{
    protected $itemClass = CatalogElementModel::class;
}
