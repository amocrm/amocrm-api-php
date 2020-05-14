<?php

namespace AmoCRM\Collections;

use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\CompanyModel;

/**
 * Class CompaniesCollection
 *
 * @package AmoCRM\Collections
 *
 * @method CompanyModel current() : ?BaseApiModel
 * @method CompanyModel last() : ?BaseApiModel
 * @method CompanyModel first() : ?BaseApiModel
 * @method CompanyModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, CompanyModel $value) : BaseApiCollection
 * @method self prepend(CompanyModel $value) : BaseApiCollection
 * @method self add(CompanyModel $value) : BaseApiCollection
 * @method CompanyModel getBy($key, $value) : ?BaseApiModel
 */
class CompaniesCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = CompanyModel::class;
}
