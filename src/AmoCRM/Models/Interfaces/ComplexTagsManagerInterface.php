<?php

declare(strict_types=1);

namespace AmoCRM\Models\Interfaces;

/**
 * Interface ComplexTagsManagerInterface
 *
 * Используется в моделях, которые поддерживают новый формат работы с тегами tags_to_add и tags_to_delete.
 * Реализация находится в MutateTagsTraits
 *
 * @package AmoCRM\Models\Interfaces
 */
interface ComplexTagsManagerInterface
{
    /**
     * @param array $entity
     *
     * @return array
     */
    public function mutateTags(array $entity): array;
}
