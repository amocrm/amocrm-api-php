<?php

namespace AmoCRM\Collections\ShortLinks;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\ShortLinks\ShortLinkModel;

/**
 * Class ShortLinksCollection
 *
 * @package AmoCRM\Collections\ShortLinks
 *
 * @method null|ShortLinkModel current()
 * @method null|ShortLinkModel last()
 * @method null|ShortLinkModel first()
 * @method null|ShortLinkModel offsetGet($offset)
 * @method ShortLinksCollection offsetSet($offset, ShortLinkModel $value)
 * @method ShortLinksCollection prepend(ShortLinkModel $value)
 * @method ShortLinksCollection add(ShortLinkModel $value)
 * @method null|ShortLinkModel getBy($key, $value)
 */
class ShortLinksCollection extends BaseApiCollection
{
    public const ITEM_CLASS = ShortLinkModel::class;
}
