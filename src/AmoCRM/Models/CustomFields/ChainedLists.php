<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFields;

use Illuminate\Support\Collection;

use function array_filter;
use function array_values;
use function is_array;

/**
 * Коллекция моделей вложенных списков
 *
 * @since Release Spring 2022
 * @template ChainedList
 */
final class ChainedLists extends Collection
{
    /**
     * @param array $items
     *
     * @return ChainedLists<ChainedList>
     */
    public static function fromArray(array $items): ChainedLists
    {
        $collection = new self();

        $items = array_filter(array_values($items), static function ($item) {
            return ! empty($item) && is_array($item);
        });

        if (empty($items)) {
            return $collection;
        }

        /** @var array $item */
        foreach ($items as $item) {
            $catalogId = (int) ($item['catalog_id'] ?? 0);
            $parentCatalogId = (int) ($item['parent_catalog_id'] ?? 0) ?: null;
            $title = (string) ($item['title'] ?? '');

            $collection->put($catalogId, new ChainedList($catalogId, $parentCatalogId, $title));
        }

        return $collection;
    }
}
