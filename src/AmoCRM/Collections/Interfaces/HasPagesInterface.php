<?php

namespace AmoCRM\Collections\Interfaces;

use AmoCRM\Collections\BaseApiCollection;

/**
 * Интерфейс для фильтров, которые поддерживают постраничную навигацию
 * @package AmoCRM\Filters\Interfaces
 */
interface HasPagesInterface
{
    /**
     * @param string $url
     * @return BaseApiCollection
     */
    public function setNextPageLink(string $url);

    /**
     * @return string
     */
    public function getNextPageLink(): ?string;

    /**
     * @param string $url
     * @return BaseApiCollection
     */
    public function setPrevPageLink(string $url);

    /**
     * @return string
     */
    public function getPrevPageLink(): ?string;
}
