<?php

declare(strict_types=1);

namespace AmoCRM\Collections\Sources;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\Sources\WebsiteButtonModel;

class WebsiteButtonsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = WebsiteButtonModel::class;
}
