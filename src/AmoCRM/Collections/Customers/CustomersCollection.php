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
 * @method null|CustomerModel current()
 * @method null|CustomerModel last()
 * @method null|CustomerModel first()
 * @method null|CustomerModel offsetGet($offset)
 * @method void offsetSet($offset, CustomerModel $value)
 * @method CustomersCollection prepend(CustomerModel $value)
 * @method CustomersCollection add(CustomerModel $value)
 * @method null|CustomerModel getBy($key, $value)
 */
class CustomersCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = CustomerModel::class;
}
