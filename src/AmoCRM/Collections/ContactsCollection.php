<?php

namespace AmoCRM\Collections;

use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\ContactModel;

/**
 * Class ContactsCollection
 *
 * @package AmoCRM\Collections
 *
 * @method ContactModel current() : ?BaseApiModel
 * @method ContactModel last() : ?BaseApiModel
 * @method ContactModel first() : ?BaseApiModel
 * @method ContactModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, ContactModel $value) : BaseApiCollection
 * @method self prepend(ContactModel $value) : BaseApiCollection
 * @method self add(ContactModel $value) : BaseApiCollection
 * @method ContactModel getBy($key, $value) : ?BaseApiModel
 */
class ContactsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = ContactModel::class;
}
