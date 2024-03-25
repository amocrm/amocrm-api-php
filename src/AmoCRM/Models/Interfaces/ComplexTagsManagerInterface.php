<?php

declare(strict_types=1);

namespace AmoCRM\Models\Interfaces;

/**
 * Interface ComplexTagsManagerInterface
 *
 * @package AmoCRM\Models\Interfaces
 */
interface ComplexTagsManagerInterface
{
    /**
     * Функция для мутирования тегов нового формата tags_to_add и tags_to_delete.
     * Реализация находится в MutateTagsTraits
     *
     * @param array $entity
     *
     * @return array
     */
    public function mutateTags(array $entity): array;
}
