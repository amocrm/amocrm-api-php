<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Collections\LinksCollection;
use AmoCRM\Models\BaseApiModel;

/**
 * Interface HasLinkMethodInterface
 * Нужен в тех сервисах, где есть методы link/unlink у сущностей
 * @package AmoCRM\EntitiesServices
 */
interface HasLinkMethodInterface
{
    /**
     * @param BaseApiModel $mainEntity
     * @param $linkedEntities
     *
     * @return LinksCollection
     */
    public function link(BaseApiModel $mainEntity, $linkedEntities): LinksCollection;

    /**
     * @param BaseApiModel $mainEntity
     * @param $linkedEntities
     *
     * @return bool
     */
    public function unlink(BaseApiModel $mainEntity, $linkedEntities): bool;
}
