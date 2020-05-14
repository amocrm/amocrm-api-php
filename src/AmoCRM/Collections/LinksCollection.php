<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\CanBeLinkedInterface;
use AmoCRM\Models\LinkModel;

/**
 * Class LinksCollection
 *
 * @package AmoCRM\Collections
 *
 * @method LinkModel current() : ?BaseApiModel
 * @method LinkModel last() : ?BaseApiModel
 * @method LinkModel first() : ?BaseApiModel
 * @method LinkModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, LinkModel $value) : BaseApiCollection
 * @method self prepend(LinkModel $value) : BaseApiCollection
 * @method self add(CanBeLinkedInterface|LinkModel $value) : BaseApiCollection
 * @method LinkModel getBy($key, $value) : ?BaseApiModel
 */
class LinksCollection extends BaseApiCollection
{
    /**
     * Класс модели
     * @var string
     */
    public const ITEM_CLASS = LinkModel::class;

    /**
     * @param mixed $item
     *
     * @return BaseApiModel
     */
    protected function checkItem($item): BaseApiModel
    {
        if ($item instanceof CanBeLinkedInterface) {
            $item = $item->getLink();
        }

        return parent::checkItem($item);
    }
}
