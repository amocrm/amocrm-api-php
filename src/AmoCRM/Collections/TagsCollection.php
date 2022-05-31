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
 * @method null|TagModel current()
 * @method null|TagModel last()
 * @method null|TagModel first()
 * @method null|TagModel offsetGet($offset)
 * @method void offsetSet($offset, TagModel $value)
 * @method TagsCollection prepend(TagModel $value)
 * @method TagsCollection add(TagModel $value)
 * @method null|TagModel getBy($key, $value)
 */
class TagsCollection extends BaseApiCollection implements HasPagesInterface
{
    use EntityApiTrait;
    use PagesTrait;

    public const ITEM_CLASS = TagModel::class;
}
