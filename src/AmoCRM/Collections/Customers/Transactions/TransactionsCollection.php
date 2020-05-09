<?php

namespace AmoCRM\Collections\Customers\Transactions;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Customers\Transactions\TransactionModel;

class TransactionsCollection extends BaseApiCollection
{
    protected $itemClass = TransactionModel::class;
}
