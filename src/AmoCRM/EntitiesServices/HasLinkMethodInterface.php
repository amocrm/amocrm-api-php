<?php

namespace AmoCRM\AmoCRM\EntitiesServices;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\BaseApiModel;

/**
 * Interface HasLinkMethodInterface
 * Нужен в тех сервисах, где есть методы link/unlink у сущностей
 * @package AmoCRM\AmoCRM\EntitiesServices
 */
interface HasLinkMethodInterface
{
    //TODO Отдельная коллекция для связей
    public function link(BaseApiModel $mainEntity, BaseApiCollection $linkedEntities);

    public function unlink(BaseApiModel $mainEntity, BaseApiCollection $linkedEntities);
}
