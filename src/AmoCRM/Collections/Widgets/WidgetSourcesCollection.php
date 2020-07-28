<?php

namespace AmoCRM\Collections\Widgets;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Widgets\WidgetSourceModel;

class WidgetSourcesCollection extends BaseApiCollection
{
    public const ITEM_CLASS = WidgetSourceModel::class;
}