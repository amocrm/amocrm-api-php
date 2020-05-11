<?php

namespace AmoCRM\EntitiesServices\Interfaces;

use AmoCRM\Collections\Interfaces\HasPagesInterface;

/**
 * Интерфейс для сервисов, которые поддерживают постраничную навигацию
 * @package AmoCRM\EntitiesServices\Interfaces
 */
interface HasPageMethodsInterface
{
    /**
     * @param HasPagesInterface $collection
     *
     * @return HasPagesInterface
     */
    public function nextPage(HasPagesInterface $collection): HasPagesInterface;

    /**
     * @param HasPagesInterface $collection
     *
     * @return HasPagesInterface
     */
    public function prevPage(HasPagesInterface $collection): HasPagesInterface;
}
