<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueCollections;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\BaseCustomFieldValueModel;

/**
 * Class BaseCustomFieldValueCollection
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueCollections
 */
class BaseCustomFieldValueCollection extends BaseApiCollection
{
    protected $itemClass = BaseCustomFieldValueModel::class;
}
