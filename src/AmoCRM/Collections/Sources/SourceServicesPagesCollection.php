<?php

namespace AmoCRM\Collections\Sources;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Sources\SourceServicePageModel;

/**
 * Class SourceServicesPageCollection
 *
 * @package AmoCRM\Collections
 * @method static SourceServicesPagesCollection fromArray(array $items)
 */
class SourceServicesPagesCollection extends BaseApiCollection
{

    public const ITEM_CLASS = SourceServicePageModel::class;
}
