<?php

declare(strict_types=1);

namespace AmoCRM\AmoCRM\Collections;

use AmoCRM\AmoCRM\Models\ChatLinkModel;
use AmoCRM\Collections\BaseApiCollection;

class ChatLinksCollection extends BaseApiCollection
{
    public const ITEM_CLASS = ChatLinkModel::class;
}
