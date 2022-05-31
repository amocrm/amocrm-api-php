<?php

namespace AmoCRM\Collections\CustomFields;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\CustomFields\RequiredStatusModel;

/**
 * Class CustomFieldRequiredStatusesCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|RequiredStatusModel current()
 * @method null|RequiredStatusModel last()
 * @method null|RequiredStatusModel first()
 * @method null|RequiredStatusModel offsetGet($offset)
 * @method void offsetSet($offset, RequiredStatusModel $value)
 * @method CustomFieldRequiredStatusesCollection prepend(RequiredStatusModel $value)
 * @method CustomFieldRequiredStatusesCollection add(RequiredStatusModel $value)
 * @method null|RequiredStatusModel getBy($key, $value)
 */
class CustomFieldRequiredStatusesCollection extends BaseApiCollection
{
    public const ITEM_CLASS = RequiredStatusModel::class;
}
