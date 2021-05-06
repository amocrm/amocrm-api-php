<?php

namespace AmoCRM\Models\Interfaces;

/**
 * Interface CanReturnDeletedInterface
 * Необходим сущностям, которые могут быть удаленными
 *
 * @package AmoCRM\Models\Interfaces
 */
interface CanReturnDeletedInterface
{
    public const ONLY_DELETED = 'only_deleted';
}
