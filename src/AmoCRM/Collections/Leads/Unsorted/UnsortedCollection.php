<?php

namespace AmoCRM\Collections\Leads\Unsorted;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\Unsorted\BaseUnsortedModel;

/**
 * Class UnsortedCollection
 *
 * @package AmoCRM\Collections\Leads\Unsorted
 *
 * @method null|BaseUnsortedModel current()
 * @method null|BaseUnsortedModel last()
 * @method null|BaseUnsortedModel first()
 * @method null|BaseUnsortedModel offsetGet($offset)
 * @method UnsortedCollection offsetSet($offset, BaseUnsortedModel $value)
 * @method UnsortedCollection prepend(BaseUnsortedModel $value)
 * @method UnsortedCollection add(BaseUnsortedModel $value)
 * @method null|BaseUnsortedModel getBy($key, $value)
 */
class UnsortedCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = BaseUnsortedModel::class;

    /**
     * @return null|string
     */
    public function getCategory(): ?string
    {
        return null;
    }
}
