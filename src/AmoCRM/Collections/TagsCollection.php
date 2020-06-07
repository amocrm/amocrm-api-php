<?php

namespace AmoCRM\Collections;

use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\EntityApiTrait;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\TagModel;

/**
 * Class TagsCollection
 *
 * @package AmoCRM\Collections
 *
 * @method TagModel current() : ?BaseApiModel
 * @method TagModel last() : ?BaseApiModel
 * @method TagModel first() : ?BaseApiModel
 * @method TagModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, TagModel $value) : BaseApiCollection
 * @method self prepend(TagModel $value) : BaseApiCollection
 * @method self add(TagModel $value) : BaseApiCollection
 * @method TagModel getBy($key, $value) : ?BaseApiModel
 */
class TagsCollection extends BaseApiCollection implements HasPagesInterface
{
    use EntityApiTrait;
    use PagesTrait;

    public const ITEM_CLASS = TagModel::class;
}
