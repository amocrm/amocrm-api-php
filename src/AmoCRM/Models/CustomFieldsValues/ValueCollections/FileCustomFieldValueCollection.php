<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFieldsValues\ValueCollections;

use AmoCRM\Models\CustomFieldsValues\ValueModels\FileCustomFieldValueModel;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Exceptions\InvalidArgumentException;

use function array_map;

/**
 * @since Release Spring 2022
 */
class FileCustomFieldValueCollection extends BaseCustomFieldValueCollection
{
    public const ITEM_CLASS = FileCustomFieldValueModel::class;

    /**
     * @param array $array
     *
     * @return FileCustomFieldValueCollection
     */
    public static function fromArray(array $array): BaseApiCollection
    {
        $items = array_map(
            static function (array $item) {
                try {
                    return FileCustomFieldValueModel::fromArray($item);
                } catch (InvalidArgumentException $e) {
                    return null;
                }
            },
            $array
        );

        return self::make($items);
    }
}
