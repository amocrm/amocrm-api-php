<?php

namespace AmoCRM\Filters\Interfaces;

use AmoCRM\Filters\Traits\PagesFilterTrait;

/**
 * Интерфейс для фильтров, которые поддерживают постраничную навигацию
 * @package AmoCRM\Filters\Interfaces
 */
interface HasPagesInterface
{
    public function setPage(int $page): PagesFilterTrait;

    public function getPage(): int;

    public function setLimit(int $limit): PagesFilterTrait;

    public function getLimit(): int;
}
