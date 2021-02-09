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
 * @method null|ContactModel current()
 * @method null|ContactModel last()
 * @method null|ContactModel first()
 * @method null|ContactModel offsetGet($offset)
 * @method ContactsCollection offsetSet($offset, ContactModel $value)
 * @method ContactsCollection prepend(ContactModel $value)
 * @method ContactsCollection add(ContactModel $value)
 * @method null|ContactModel getBy($key, $value)
 */
class ContactsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = ContactModel::class;

    /**
     * @return array
     */
    public function toLeadApi(): array
    {
        $result = [];
        /** @var ContactModel $item */
        foreach ($this->data as $item) {
            $leadContact = $item->toLeadApi();
            if (!empty($leadContact)) {
                $result[] = $leadContact;
            }
        }

        return $result;
    }
}
