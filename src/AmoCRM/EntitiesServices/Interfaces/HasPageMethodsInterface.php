<?php

namespace AmoCRM\EntitiesServices\Interfaces;

use AmoCRM\Collections\Interfaces\HasPagesInterface;

/**
 * Интерфейс для сервисов, которые поддерживают постраничную навигацию
 * @package AmoCRM\EntitiesServices\Interfaces
 */
interface HasPageMethodsInterface
{
    public function nextPage(HasPagesInterface $collection): HasPagesInterface;

    public function prevPage(HasPagesInterface $collection): HasPagesInterface;
}
