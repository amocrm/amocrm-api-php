<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\WebhookModel;

/**
 * Class WebhooksCollection
 *
 * @package AmoCRM\Collections
 *
 * @method WebhookModel current() : ?BaseApiModel
 * @method WebhookModel last() : ?BaseApiModel
 * @method WebhookModel first() : ?BaseApiModel
 * @method WebhookModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, WebhookModel $value) : BaseApiCollection
 * @method self prepend(WebhookModel $value) : BaseApiCollection
 * @method self add(WebhookModel $value) : BaseApiCollection
 * @method WebhookModel getBy($key, $value) : ?BaseApiModel
 */
class WebhooksCollection extends BaseApiCollection
{
    public const ITEM_CLASS = WebhookModel::class;
}
