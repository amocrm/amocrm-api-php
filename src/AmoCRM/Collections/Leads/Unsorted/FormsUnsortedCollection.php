<?php

namespace AmoCRM\Collections\Leads\Unsorted;

use AmoCRM\Models\Unsorted\BaseUnsortedModel;
use AmoCRM\Models\Unsorted\FormUnsortedModel;

/**
 * Class FormsUnsortedCollection
 *
 * @package AmoCRM\Collections\Leads\Unsorted
 *
 * @method null|FormUnsortedModel current()
 * @method null|FormUnsortedModel last()
 * @method null|FormUnsortedModel first()
 * @method null|FormUnsortedModel offsetGet($offset)
 * @method void offsetSet($offset, FormUnsortedModel $value)
 * @method FormsUnsortedCollection prepend(FormUnsortedModel $value)
 * @method FormsUnsortedCollection add(FormUnsortedModel $value)
 * @method null|FormUnsortedModel getBy($key, $value)
 */
class FormsUnsortedCollection extends UnsortedCollection
{
    public const ITEM_CLASS = FormUnsortedModel::class;

    /**
     * @return null|string
     */
    public function getCategory(): ?string
    {
        return BaseUnsortedModel::CATEGORY_CODE_FORMS;
    }
}
