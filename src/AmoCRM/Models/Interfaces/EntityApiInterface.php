<?php

namespace AmoCRM\Models\Interfaces;

interface EntityApiInterface
{
    /**
     * Возвращает представление сущности для связанной сущности
     * @return array|null
     */
    public function toEntityApi(): ?array;
}
