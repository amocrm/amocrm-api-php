<?php

namespace AmoCRM\Filters\Interfaces;

use AmoCRM\Filters\Traits\PagesFilterTrait;

/**
 * Интерфейс для фильтров, которые поддерживают постраничную навигацию
 * @package AmoCRM\Filters\Interfaces
 */
interface HasOrderInterface
{
    const SORT_ASC = 'asc';
    const SORT_DESC = 'desc';

    /**
     * @param string $field
     * @param string $direction
     *
     * @return $this
     */
    public function setOrder(string $field, string $direction = self::SORT_ASC): self;

    /**
     * @return array
     */
    public function getOrder(): array;
}
