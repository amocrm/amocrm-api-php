<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFields;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

use function json_encode;

/**
 * Модель вложенного списка
 *
 * @since Release Spring 2022
 */
final class ChainedList implements Arrayable, Jsonable
{
    /** @var int */
    private $catalogId;
    /** @var null|int */
    private $parentCatalogId;
    /** @var string */
    private $title;

    /**
     * @param int $catalogId
     * @param null|int $parentCatalogId
     * @param string $title
     */
    public function __construct(int $catalogId, ?int $parentCatalogId, string $title)
    {
        $this->catalogId = $catalogId;
        $this->parentCatalogId = $parentCatalogId;
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getCatalogId(): int
    {
        return $this->catalogId;
    }

    /**
     * @return null|int
     */
    public function getParentCatalogId(): ?int
    {
        return $this->parentCatalogId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            'catalog_id' => $this->getCatalogId(),
            'parent_catalog_id' => $this->getParentCatalogId(),
            'title' => $this->getTitle(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
