<?php

declare(strict_types=1);

namespace AmoCRM\Collections;

use AmoCRM\Models\SubscriptionModel;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\RoleModel;

/**
 * @method null|SubscriptionModel current()
 * @method null|SubscriptionModel last()
 * @method null|SubscriptionModel first()
 * @method null|SubscriptionModel offsetGet($offset)
 * @method void offsetSet($offset, RoleModel $value)
 * @method self prepend(RoleModel $value)
 * @method self add(RoleModel $value)
 * @method null|SubscriptionModel getBy($key, $value)
 */
class SubscriptionsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = SubscriptionModel::class;
}
