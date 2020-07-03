<?php

namespace AmoCRM\Filters\Interfaces;

/**
 * Интерфейс для фильтров, которые поддерживают постраничную навигацию
 * @package AmoCRM\Filters\Interfaces
 */
interface HasOrderInterface
{
    public const SORT_ASC = 'asc';
    public const SORT_DESC = 'desc';

    /**
     * @param string $field
     * @param string $direction
     *
     * @return $this
     */
    public function setOrder(string $field, string $direction = self::SORT_ASC);

    /**
     * @return null|array
     */
    public function getOrder(): ?array;
}
