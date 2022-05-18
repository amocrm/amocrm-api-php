<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFieldsValues\ValueCollections;

use AmoCRM\Models\CustomFieldsValues\ValueModels\ChainedListCustomFieldValueModel;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Exceptions\InvalidArgumentException;

use function array_map;

/**
 * @since Release Spring 2022
 */
class ChainedListCustomFieldValueCollection extends BaseCustomFieldValueCollection
{
    public const ITEM_CLASS = ChainedListCustomFieldValueModel::class;

    /**
     * @param array $array
     *
     * @return ChainedListCustomFieldValueCollection
     */
    public static function fromArray(array $array): BaseApiCollection
    {
        $items = array_map(
            static function (array $item) {
                try {
                    return ChainedListCustomFieldValueModel::fromArray($item);
                } catch (InvalidArgumentException $e) {
                    return null;
                }
            },
            $array
        );

        return self::make($items);
    }
}
