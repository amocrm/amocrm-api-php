<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\CanBeLinkedInterface;
use AmoCRM\Models\LinkModel;

class LinksCollection extends BaseApiCollection
{
    /**
     * Класс модели
     * @var string
     */
    protected $itemClass = LinkModel::class;

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
