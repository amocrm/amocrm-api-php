<?php

declare(strict_types=1);

namespace AmoCRM\AmoCRM\Collections\Sources;

use AmoCRM\AmoCRM\Models\Sources\WebsiteButtonModel;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;

class WebsiteButtonsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = WebsiteButtonModel::class;
}
