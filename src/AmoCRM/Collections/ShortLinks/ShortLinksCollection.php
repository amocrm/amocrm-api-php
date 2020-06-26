<?php

namespace AmoCRM\Collections\ShortLinks;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\ShortLinks\ShortLinkModel;

/**
 * Class ShortLinksCollection
 *
 * @package AmoCRM\Collections\ShortLinks
 *
 * @method ShortLinkModel current() : ?BaseApiModel
 * @method ShortLinkModel last() : ?BaseApiModel
 * @method ShortLinkModel first() : ?BaseApiModel
 * @method ShortLinkModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, ShortLinkModel $value) : BaseApiCollection
 * @method self prepend(ShortLinkModel $value) : BaseApiCollection
 * @method self add(ShortLinkModel $value) : BaseApiCollection
 * @method ShortLinkModel getBy($key, $value) : ?BaseApiModel
 */
class ShortLinksCollection extends BaseApiCollection
{
    public const ITEM_CLASS = ShortLinkModel::class;
}
