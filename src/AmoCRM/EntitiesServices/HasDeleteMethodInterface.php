<?php

namespace AmoCRM\AmoCRM\EntitiesServices;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\BaseApiModel;

/**
 * Interface HasDeleteMethodInterface
 * Нужен в тех сервисах, где есть поддержка DELETE запросов у сущностей
 * @package AmoCRM\AmoCRM\EntitiesServices
 */
interface HasDeleteMethodInterface
{
    public function deleteOne(BaseApiModel $model): bool;

    public function delete(BaseApiCollection $collection): bool;
}
