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
 * @method CatalogModel current() : ?BaseApiModel
 * @method CatalogModel last() : ?BaseApiModel
 * @method CatalogModel first() : ?BaseApiModel
 * @method CatalogModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, CatalogModel $value) : BaseApiCollection
 * @method self prepend(CatalogModel $value) : BaseApiCollection
 * @method self add(CatalogModel $value) : BaseApiCollection
 * @method CatalogModel getBy($key, $value) : ?BaseApiModel
 */
class CatalogsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = CatalogModel::class;
}
