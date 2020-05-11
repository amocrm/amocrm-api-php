<?php

namespace AmoCRM\Models\Interfaces;

use AmoCRM\Models\LinkModel;

/**
 * Interface CanBeLinkedInterface
 * Необходим сущностям, которые можно привязывать
 *
 * @package AmoCRM\Models\Interfaces
 */
interface CanBeLinkedInterface
{
    /**
     * @return LinkModel
     */
    public function getLink(): LinkModel;
}
