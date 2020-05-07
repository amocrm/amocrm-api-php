<?php

namespace AmoCRM\Collections;

use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\Unsorted\BaseUnsortedModel;

class UnsortedCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    protected $itemClass = BaseUnsortedModel::class;

    /**
     * @return null|string
     */
    public function getCategory(): ?string
    {
        return null;
    }
}
