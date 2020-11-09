<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\WebhookModel;

/**
 * Class WebhooksCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|WebhookModel current()
 * @method null|WebhookModel last()
 * @method null|WebhookModel first()
 * @method null|WebhookModel offsetGet($offset)
 * @method WebhooksCollection offsetSet($offset, WebhookModel $value)
 * @method WebhooksCollection prepend(WebhookModel $value)
 * @method WebhooksCollection add(WebhookModel $value)
 * @method null|WebhookModel getBy($key, $value)
 */
class WebhooksCollection extends BaseApiCollection
{
    public const ITEM_CLASS = WebhookModel::class;
}
