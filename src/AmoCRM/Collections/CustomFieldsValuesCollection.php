<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\CustomFields\CustomFieldModel;
use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel;

use function in_array;

/**
 * Class CustomFieldsValuesCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|BaseCustomFieldValuesModel current()
 * @method null|BaseCustomFieldValuesModel last()
 * @method null|BaseCustomFieldValuesModel first()
 * @method null|BaseCustomFieldValuesModel offsetGet($offset)
 * @method CustomFieldsValuesCollection offsetSet($offset, BaseCustomFieldValuesModel $value)
 * @method CustomFieldsValuesCollection prepend(BaseCustomFieldValuesModel $value)
 * @method CustomFieldsValuesCollection add(BaseCustomFieldValuesModel $value)
 * @method null|BaseCustomFieldValuesModel getBy($key, $value)
 */
class CustomFieldsValuesCollection extends BaseApiCollection
{
    private $typesToSkip = [
        CustomFieldModel::TYPE_ORG_LEGAL_NAME,
    ];

    public const ITEM_CLASS = BaseCustomFieldValuesModel::class;

    /**
     * @return null|array
     */
    public function toApi(): ?array
    {
        $result = [];
        /** @var BaseCustomFieldValuesModel $item */
        foreach ($this->data as $key => $item) {
            if (in_array($item->getFieldType(), $this->typesToSkip, true)) {
                continue;
            }
            $result[$key] = $item->toApi($key);
        }

        return $result;
    }
}
