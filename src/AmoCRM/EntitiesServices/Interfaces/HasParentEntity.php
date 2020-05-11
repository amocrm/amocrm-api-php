<?php

namespace AmoCRM\EntitiesServices\Interfaces;

/**
 * Интерфейс для сервисов, у которых сущность имеет родительскую сущность
 * @package AmoCRM\EntitiesServices\Interfaces
 */
interface HasParentEntity
{
    public const ID_KEY = 'id';
    public const PARENT_ID_KEY = 'parent_id';
}
