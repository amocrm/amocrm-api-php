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
 * @method BaseUnsortedModel current() : ?BaseApiModel
 * @method BaseUnsortedModel last() : ?BaseApiModel
 * @method BaseUnsortedModel first() : ?BaseApiModel
 * @method BaseUnsortedModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, BaseUnsortedModel $value) : BaseApiCollection
 * @method self prepend(BaseUnsortedModel $value) : BaseApiCollection
 * @method self add(BaseUnsortedModel $value) : BaseApiCollection
 * @method BaseUnsortedModel getBy($key, $value) : ?BaseApiModel
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
