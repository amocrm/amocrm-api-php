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
 * @method WidgetModel current() : ?BaseApiModel
 * @method WidgetModel last() : ?BaseApiModel
 * @method WidgetModel first() : ?BaseApiModel
 * @method WidgetModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, WidgetModel $value) : BaseApiCollection
 * @method self prepend(WidgetModel $value) : BaseApiCollection
 * @method self add(WidgetModel $value) : BaseApiCollection
 * @method WidgetModel getBy($key, $value) : ?BaseApiModel
 */
class WidgetsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = WidgetModel::class;
}
