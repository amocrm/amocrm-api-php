<?php

namespace AmoCRM\Collections\Chats\Templates;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Chats\Templates\ReviewModel;

class ReviewsCollection extends BaseApiCollection
{
    const ITEM_CLASS = ReviewModel::class;
}
