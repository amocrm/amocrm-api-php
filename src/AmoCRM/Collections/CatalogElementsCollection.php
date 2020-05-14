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
 * @method CatalogElementModel current() : ?BaseApiModel
 * @method CatalogElementModel last() : ?BaseApiModel
 * @method CatalogElementModel first() : ?BaseApiModel
 * @method CatalogElementModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, CatalogElementModel $value) : BaseApiCollection
 * @method self prepend(CatalogElementModel $value) : BaseApiCollection
 * @method self add(CatalogElementModel $value) : BaseApiCollection
 * @method CatalogElementModel getBy($key, $value) : ?BaseApiModel
 */
class CatalogElementsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = CatalogElementModel::class;
}
