<?php

namespace AmoCRM\Collections\Customers\Transactions;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Customers\Transactions\TransactionModel;

/**
 * Class TransactionsCollection
 *
 * @package AmoCRM\Collections\Customers\Transactions
 *
 * @method null|TransactionModel current()
 * @method null|TransactionModel last()
 * @method null|TransactionModel first()
 * @method null|TransactionModel offsetGet($offset)
 * @method TransactionsCollection offsetSet($offset, TransactionModel $value)
 * @method TransactionsCollection prepend(TransactionModel $value)
 * @method TransactionsCollection add(TransactionModel $value)
 * @method null|TransactionModel getBy($key, $value)
 */
class TransactionsCollection extends BaseApiCollection
{
    public const ITEM_CLASS = TransactionModel::class;
}
