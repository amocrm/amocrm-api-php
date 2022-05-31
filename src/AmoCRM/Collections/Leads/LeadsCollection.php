<?php

namespace AmoCRM\Collections\Leads;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\LeadModel;

/**
 * Class LeadsCollection
 *
 * @package AmoCRM\Collections\Leads
 *
 * @method null|LeadModel current()
 * @method null|LeadModel last()
 * @method null|LeadModel first()
 * @method null|LeadModel offsetGet($offset)
 * @method void offsetSet($offset, LeadModel $value)
 * @method LeadsCollection prepend(LeadModel $value)
 * @method LeadsCollection add(LeadModel $value)
 * @method null|LeadModel getBy($key, $value)
 */
class LeadsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = LeadModel::class;

    /**
     * @return null|array
     */
    public function toComplexApi(): ?array
    {
        $result = [];
        /** @var LeadModel $item */
        foreach ($this->data as $key => $item) {
            $result[$key] = $item->toComplexApi($key);
        }

        return $result;
    }
}
