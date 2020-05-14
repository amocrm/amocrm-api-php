<?php

namespace AmoCRM\Collections\Leads\Unsorted;

use AmoCRM\Models\Unsorted\BaseUnsortedModel;
use AmoCRM\Models\Unsorted\SipUnsortedModel;

/**
 * Class SipUnsortedCollection
 *
 * @package AmoCRM\Collections\Leads\Unsorted
 *
 * @method SipUnsortedModel current() : ?BaseApiModel
 * @method SipUnsortedModel last() : ?BaseApiModel
 * @method SipUnsortedModel first() : ?BaseApiModel
 * @method SipUnsortedModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, SipUnsortedModel $value) : BaseApiCollection
 * @method self prepend(SipUnsortedModel $value) : BaseApiCollection
 * @method self add(SipUnsortedModel $value) : BaseApiCollection
 * @method SipUnsortedModel getBy($key, $value) : ?BaseApiModel
 */
class SipUnsortedCollection extends UnsortedCollection
{
    public const ITEM_CLASS = SipUnsortedModel::class;

    /**
     * @return null|string
     */
    public function getCategory(): ?string
    {
        return BaseUnsortedModel::CATEGORY_CODE_SIP;
    }
}
