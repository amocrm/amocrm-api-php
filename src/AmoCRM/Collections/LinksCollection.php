<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\BaseApiModel;
use Exception;
use InvalidArgumentException;

class LinksCollection extends BaseApiCollection
{
    /**
     * Класс модели
     * @var string
     */
    protected $itemClass = BaseApiModel::class;

    /**
     * @param mixed $item
     * @return BaseApiModel
     */
    protected function checkItem($item): BaseApiModel
    {
        if (!is_object($item) || !($item instanceof $this->itemClass)) {
            throw new InvalidArgumentException('Item must be an instance of ' . ($this->itemClass));
        }

        return $item;
    }

    /**
     * @param array $array
     *
     * @return self
     * @throws Exception
     */
    public function fromArray(array $array): BaseApiCollection
    {
        //todo
        throw new Exception('Not implemented');
    }
}
