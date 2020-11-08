<?php

namespace AmoCRM\Collections;

use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\CatalogElementModel;

/**
 * Class CatalogElementsCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|CatalogElementModel current()
 * @method null|CatalogElementModel last()
 * @method null|CatalogElementModel first()
 * @method null|CatalogElementModel offsetGet($offset)
 * @method CatalogElementsCollection offsetSet($offset, CatalogElementModel $value)
 * @method CatalogElementsCollection prepend(CatalogElementModel $value)
 * @method CatalogElementsCollection add(CatalogElementModel $value)
 * @method null|CatalogElementModel getBy($key, $value)
 */
class CatalogElementsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = CatalogElementModel::class;
}
