<?php

namespace AmoCRM\Collections;

use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\CallModel;

/**
 * Class CallsCollection
 *
 * @package AmoCRM\Collections
 *
 * @method CallModel current() : ?BaseApiModel
 * @method CallModel last() : ?BaseApiModel
 * @method CallModel first() : ?BaseApiModel
 * @method CallModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, CallModel $value) : BaseApiCollection
 * @method self prepend(CallModel $value) : BaseApiCollection
 * @method self add(CallModel $value) : BaseApiCollection
 * @method CallModel getBy($key, $value) : ?BaseApiModel
 */
class CallsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = CallModel::class;
}
