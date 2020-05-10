<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\BaseApiModel;

/**
 * Interface HasDeleteMethodInterface
 * Нужен в тех сервисах, где есть поддержка DELETE запросов у сущностей
 * @package AmoCRM\EntitiesServices
 */
interface HasDeleteMethodInterface
{
    /**
     * @param BaseApiModel $model
     *
     * @return bool
     */
    public function deleteOne(BaseApiModel $model): bool;

    /**
     * @param BaseApiCollection $collection
     *
     * @return bool
     */
    public function delete(BaseApiCollection $collection): bool;
}
