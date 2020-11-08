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
 * @method null|LinkModel current()
 * @method null|LinkModel last()
 * @method null|LinkModel first()
 * @method null|LinkModel offsetGet($offset)
 * @method LinksCollection offsetSet($offset, LinkModel $value)
 * @method LinksCollection prepend(LinkModel $value)
 * @method LinksCollection add(CanBeLinkedInterface|LinkModel $value)
 * @method null|LinkModel getBy($key, $value)
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
