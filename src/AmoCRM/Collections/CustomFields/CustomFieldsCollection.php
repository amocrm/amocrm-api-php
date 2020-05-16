<?php

namespace AmoCRM\Collections\CustomFields;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Exceptions\BadTypeException;
use AmoCRM\Models\CustomFields\CustomFieldModel;
use AmoCRM\Models\CustomFields\Factories\CustomFieldModelFactory;
use AmoCRM\Models\CustomFields\WithEnumCustomFieldModel;

/**
 * Class CustomFieldsCollection
 *
 * @package AmoCRM\Collections
 *
 * @method CustomFieldModel current() : ?BaseApiModel
 * @method CustomFieldModel last() : ?BaseApiModel
 * @method CustomFieldModel first() : ?BaseApiModel
 * @method CustomFieldModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, CustomFieldModel $value) : BaseApiCollection
 * @method self prepend(CustomFieldModel $value) : BaseApiCollection
 * @method self add(CustomFieldModel $value) : BaseApiCollection
 * @method CustomFieldModel|WithEnumCustomFieldModel getBy($key, $value) : ?BaseApiModel
 */
class CustomFieldsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = CustomFieldModel::class;

    /**
     * @param array $array
     *
     * @return self
     */
    public static function fromArray(array $array): BaseApiCollection
    {
        $items = array_map(
            function (array $item) {
                try {
                    return CustomFieldModelFactory::createModel($item);
                } catch (BadTypeException $e) {
                    return null;
                }
            },
            $array
        );

        $items = array_filter($items, function ($item) {
            return $item instanceof CustomFieldModel;
        });

        return self::make($items);
    }
}
