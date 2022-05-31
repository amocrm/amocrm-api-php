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
 * @method null|CompanyModel current()
 * @method null|CompanyModel last()
 * @method null|CompanyModel first()
 * @method null|CompanyModel offsetGet($offset)
 * @method void offsetSet($offset, CompanyModel $value)
 * @method CompaniesCollection prepend(CompanyModel $value)
 * @method CompaniesCollection add(CompanyModel $value)
 * @method null|CompanyModel getBy($key, $value)
 */
class CompaniesCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = CompanyModel::class;
}
