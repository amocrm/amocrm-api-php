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
 * @method null|CustomFieldModel current()
 * @method null|CustomFieldModel last()
 * @method null|CustomFieldModel first()
 * @method null|CustomFieldModel offsetGet($offset)
 * @method CustomFieldsCollection offsetSet($offset, CustomFieldModel $value)
 * @method CustomFieldsCollection prepend(CustomFieldModel $value)
 * @method CustomFieldsCollection add(CustomFieldModel $value)
 * @method null|CustomFieldModel|WithEnumCustomFieldModel getBy($key, $value)
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
