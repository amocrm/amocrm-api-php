<?php

namespace AmoCRM\Collections\Sources;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Sources\SourceServiceModel;

/**
 * Class SourceServicesCollection
 *
 * @package AmoCRM\Collections
 *
 * @method static SourceServicesCollection fromArray(array $items)
 */
class SourceServicesCollection extends BaseApiCollection
{

    public const ITEM_CLASS = SourceServiceModel::class;
}
