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
 * @method null|CallModel current()
 * @method null|CallModel last()
 * @method null|CallModel first()
 * @method null|CallModel offsetGet($offset)
 * @method CallsCollection offsetSet($offset, CallModel $value)
 * @method CallsCollection prepend(CallModel $value)
 * @method CallsCollection add(CallModel $value)
 * @method null|CallModel getBy($key, $value)
 */
class CallsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = CallModel::class;
}
