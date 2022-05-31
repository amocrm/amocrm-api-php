<?php

namespace AmoCRM\Collections;

use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\CatalogModel;

/**
 * Class CatalogsCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|CatalogModel current()
 * @method null|CatalogModel last()
 * @method null|CatalogModel first()
 * @method null|CatalogModel offsetGet($offset)
 * @method void offsetSet($offset, CatalogModel $value)
 * @method CatalogsCollection prepend(CatalogModel $value)
 * @method CatalogsCollection add(CatalogModel $value)
 * @method null|CatalogModel getBy($key, $value)
 */
class CatalogsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = CatalogModel::class;
}
