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
 * @method LeadModel current() : ?BaseApiModel
 * @method LeadModel last() : ?BaseApiModel
 * @method LeadModel first() : ?BaseApiModel
 * @method LeadModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, LeadModel $value) : BaseApiCollection
 * @method self prepend(LeadModel $value) : BaseApiCollection
 * @method self add(LeadModel $value) : BaseApiCollection
 * @method LeadModel getBy($key, $value) : ?BaseApiModel
 */
class LeadsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = LeadModel::class;
}
