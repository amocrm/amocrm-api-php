<?php

namespace AmoCRM\Collections\Unsorted;

use AmoCRM\Collections\UnsortedCollection;
use AmoCRM\Models\Unsorted\BaseUnsortedModel;
use AmoCRM\Models\Unsorted\SipUnsortedModel;

class SipUnsortedCollection extends UnsortedCollection
{
    protected $itemClass = SipUnsortedModel::class;

    /**
     * @return null|string
     */
    public function getCategory(): ?string
    {
        return BaseUnsortedModel::CATEGORY_CODE_SIP;
    }
}
