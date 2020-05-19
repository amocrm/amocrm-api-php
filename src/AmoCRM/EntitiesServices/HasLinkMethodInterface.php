<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Collections\LinksCollection;
use AmoCRM\Filters\LinksFilter;
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
     * @param BaseApiModel $model
     *
     * @param LinksFilter|null $filter
     *
     * @return LinksCollection
     */
    public function getLinks(BaseApiModel $model, LinksFilter $filter = null): LinksCollection;

    /**
     * @param BaseApiModel $mainEntity
     * @param $linkedEntities
     *
     * @return bool
     */
    public function unlink(BaseApiModel $mainEntity, $linkedEntities): bool;
}
