<?php

namespace AmoCRM\Collections\Leads\LossReasons;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Leads\LossReasons\LossReasonModel;

/**
 * Class LossReasonsCollection
 *
 * @package AmoCRM\Collections\Leads\LossReasons
 *
 * @method null|LossReasonModel current()
 * @method null|LossReasonModel last()
 * @method null|LossReasonModel first()
 * @method null|LossReasonModel offsetGet($offset)
 * @method void offsetSet($offset, LossReasonModel $value)
 * @method LossReasonsCollection prepend(LossReasonModel $value)
 * @method LossReasonsCollection add(LossReasonModel $value)
 * @method null|LossReasonModel getBy($key, $value)
 */
class LossReasonsCollection extends BaseApiCollection
{
    public const ITEM_CLASS = LossReasonModel::class;
}
