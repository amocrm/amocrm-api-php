<?php

namespace AmoCRM\Collections\Leads\Unsorted;

use AmoCRM\Models\Unsorted\BaseUnsortedModel;
use AmoCRM\Models\Unsorted\SipUnsortedModel;

/**
 * Class SipUnsortedCollection
 *
 * @package AmoCRM\Collections\Leads\Unsorted
 *
 * @method null|SipUnsortedModel current()
 * @method null|SipUnsortedModel last()
 * @method null|SipUnsortedModel first()
 * @method null|SipUnsortedModel offsetGet($offset)
 * @method SipUnsortedCollection offsetSet($offset, SipUnsortedModel $value)
 * @method SipUnsortedCollection prepend(SipUnsortedModel $value)
 * @method SipUnsortedCollection add(SipUnsortedModel $value)
 * @method null|SipUnsortedModel getBy($key, $value)
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
