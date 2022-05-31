<?php

namespace AmoCRM\Collections\Widgets;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\Widgets\WidgetModel;

/**
 * Class WidgetsCollection
 *
 * @package AmoCRM\Collections\Widgets
 *
 * @method null|WidgetModel current()
 * @method null|WidgetModel last()
 * @method null|WidgetModel first()
 * @method null|WidgetModel offsetGet($offset)
 * @method void offsetSet($offset, WidgetModel $value) : BaseApiCollection
 * @method WidgetsCollection prepend(WidgetModel $value) : BaseApiCollection
 * @method WidgetsCollection add(WidgetModel $value) : BaseApiCollection
 * @method null|WidgetModel getBy($key, $value)
 */
class WidgetsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = WidgetModel::class;
}
