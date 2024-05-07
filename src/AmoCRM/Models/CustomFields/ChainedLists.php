<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFields;

use function array_filter;
use function array_values;
use function is_array;

/**
 * Коллекция моделей вложенных списков
 *
 * @since Release Spring 2022
 */
final class ChainedLists
{
    /** @var array<int, ChainedList> */
    protected $items = [];

    public function addItem(int $catalogId, ChainedList $data): self
    {
        $this->items[$catalogId] = $data;

        return $this;
    }

    /**
     * @param array $items
     *
     * @return ChainedLists
     */
    public static function fromArray(array $items): self
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

            $collection->addItem($catalogId, new ChainedList($catalogId, $parentCatalogId, $title));
        }

        return $collection;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = [];
        foreach ($this->items as $key => $item) {
            $result[$key] = $item->toArray();
        }

        return $result;
    }
}
