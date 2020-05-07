<?php

namespace AmoCRM\Collections\Unsorted;

use AmoCRM\Collections\UnsortedCollection;
use AmoCRM\Models\Unsorted\BaseUnsortedModel;
use AmoCRM\Models\Unsorted\FormUnsortedModel;

class FormsUnsortedCollection extends UnsortedCollection
{
    protected $itemClass = FormUnsortedModel::class;

    /**
     * @return null|string
     */
    public function getCategory(): ?string
    {
        return BaseUnsortedModel::CATEGORY_CODE_FORMS;
    }
}
