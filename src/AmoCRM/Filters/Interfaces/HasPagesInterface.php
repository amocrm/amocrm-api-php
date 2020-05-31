<?php

namespace AmoCRM\Filters\Interfaces;

/**
 * Интерфейс для фильтров, которые поддерживают постраничную навигацию
 * @package AmoCRM\Filters\Interfaces
 */
interface HasPagesInterface
{
    public function setPage(int $page);

    public function getPage(): int;

    public function setLimit(int $limit);

    public function getLimit(): int;
}
