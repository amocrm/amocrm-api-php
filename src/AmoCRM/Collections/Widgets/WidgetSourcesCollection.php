<?php

namespace AmoCRM\Collections\Widgets;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Widgets\WidgetSourceModel;

/**
 * Class WidgetSourcesCollection
 * @package AmoCRM\Collections\Widgets
 */
class WidgetSourcesCollection extends BaseApiCollection
{
    public const ITEM_CLASS = WidgetSourceModel::class;
}