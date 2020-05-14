<?php

namespace AmoCRM\Collections\Leads\LossReasons;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Leads\LossReasons\LossReasonModel;

/**
 * Class LossReasonsCollection
 *
 * @package AmoCRM\Collections\Leads\LossReasons
 *
 * @method LossReasonModel current() : ?BaseApiModel
 * @method LossReasonModel last() : ?BaseApiModel
 * @method LossReasonModel first() : ?BaseApiModel
 * @method LossReasonModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, LossReasonModel $value) : BaseApiCollection
 * @method self prepend(LossReasonModel $value) : BaseApiCollection
 * @method self add(LossReasonModel $value) : BaseApiCollection
 * @method LossReasonModel getBy($key, $value) : ?BaseApiModel
 */
class LossReasonsCollection extends BaseApiCollection
{
    public const ITEM_CLASS = LossReasonModel::class;
}
