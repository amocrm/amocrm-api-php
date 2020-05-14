<?php

namespace AmoCRM\Collections\Customers;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\Customers\CustomerModel;

/**
 * Class CustomersCollection
 *
 * @package AmoCRM\Collections\Customers
 *
 * @method CustomerModel current() : ?BaseApiModel
 * @method CustomerModel last() : ?BaseApiModel
 * @method CustomerModel first() : ?BaseApiModel
 * @method CustomerModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, CustomerModel $value) : BaseApiCollection
 * @method self prepend(CustomerModel $value) : BaseApiCollection
 * @method self add(CustomerModel $value) : BaseApiCollection
 * @method CustomerModel getBy($key, $value) : ?BaseApiModel
 */
class CustomersCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = CustomerModel::class;
}
