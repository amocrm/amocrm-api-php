<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFieldsValues\ValueCollections;

use AmoCRM\Models\CustomFieldsValues\ValueModels\PayerCustomFieldValueModel;

/**
 * Class PayerCustomFieldValueCollection
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueCollections
 */
class PayerCustomFieldValueCollection extends BaseCustomFieldValueCollection
{
    public const ITEM_CLASS = PayerCustomFieldValueModel::class;
}
