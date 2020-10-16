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
 * @method BaseCustomFieldValuesModel current() : ?BaseApiModel
 * @method BaseCustomFieldValuesModel last() : ?BaseApiModel
 * @method BaseCustomFieldValuesModel first() : ?BaseApiModel
 * @method BaseCustomFieldValuesModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, BaseCustomFieldValuesModel $value) : BaseApiCollection
 * @method self prepend(BaseCustomFieldValuesModel $value) : BaseApiCollection
 * @method self add(BaseCustomFieldValuesModel $value) : BaseApiCollection
 * @method BaseCustomFieldValuesModel getBy($key, $value) : ?BaseApiModel
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
