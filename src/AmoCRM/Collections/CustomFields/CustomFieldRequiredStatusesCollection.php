<?php

namespace AmoCRM\Collections\CustomFields;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\CustomFields\RequiredStatusModel;

/**
 * Class CustomFieldRequiredStatusesCollection
 *
 * @package AmoCRM\Collections
 *
 * @method RequiredStatusModel current() : ?BaseApiModel
 * @method RequiredStatusModel last() : ?BaseApiModel
 * @method RequiredStatusModel first() : ?BaseApiModel
 * @method RequiredStatusModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, RequiredStatusModel $value) : BaseApiCollection
 * @method self prepend(RequiredStatusModel $value) : BaseApiCollection
 * @method self add(RequiredStatusModel $value) : BaseApiCollection
 * @method RequiredStatusModel getBy($key, $value) : ?BaseApiModel
 */
class CustomFieldRequiredStatusesCollection extends BaseApiCollection
{
    public const ITEM_CLASS = RequiredStatusModel::class;
}
