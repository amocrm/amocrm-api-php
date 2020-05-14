<?php

namespace AmoCRM\Collections\Leads\Unsorted;

use AmoCRM\Models\Unsorted\BaseUnsortedModel;
use AmoCRM\Models\Unsorted\FormUnsortedModel;

/**
 * Class FormsUnsortedCollection
 *
 * @package AmoCRM\Collections\Leads\Unsorted
 *
 * @method FormUnsortedModel current() : ?BaseApiModel
 * @method FormUnsortedModel last() : ?BaseApiModel
 * @method FormUnsortedModel first() : ?BaseApiModel
 * @method FormUnsortedModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, FormUnsortedModel $value) : BaseApiCollection
 * @method self prepend(FormUnsortedModel $value) : BaseApiCollection
 * @method self add(FormUnsortedModel $value) : BaseApiCollection
 * @method FormUnsortedModel getBy($key, $value) : ?BaseApiModel
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
