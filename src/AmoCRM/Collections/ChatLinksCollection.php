<?php

declare(strict_types=1);

namespace AmoCRM\Collections;

use AmoCRM\Models\ChatLinkModel;

class ChatLinksCollection extends BaseApiCollection
{
    public const ITEM_CLASS = ChatLinkModel::class;
}
