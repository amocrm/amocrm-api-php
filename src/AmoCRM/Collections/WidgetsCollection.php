<?php

namespace AmoCRM\Collections;

use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\WidgetModel;

class WidgetsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    protected $itemClass = WidgetModel::class;
}
