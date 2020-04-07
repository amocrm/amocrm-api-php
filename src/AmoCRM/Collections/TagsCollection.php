<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\Tag;

class TagsCollection extends BaseApiCollection
{
    protected $itemClass = Tag::class;
}
