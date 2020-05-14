<?php

namespace AmoCRM\Collections\Customers\Transactions;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Customers\Transactions\TransactionModel;

/**
 * Class TransactionsCollection
 *
 * @package AmoCRM\Collections\Customers\Transactions
 *
 * @method TransactionModel current() : ?BaseApiModel
 * @method TransactionModel last() : ?BaseApiModel
 * @method TransactionModel first() : ?BaseApiModel
 * @method TransactionModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, TransactionModel $value) : BaseApiCollection
 * @method self prepend(TransactionModel $value) : BaseApiCollection
 * @method self add(TransactionModel $value) : BaseApiCollection
 * @method TransactionModel getBy($key, $value) : ?BaseApiModel
 */
class TransactionsCollection extends BaseApiCollection
{
    public const ITEM_CLASS = TransactionModel::class;
}
