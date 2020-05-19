<?php

namespace AmoCRM\Collections;

/**
 * Class NullTagsCollection
 *
 * @package AmoCRM\Collections
 */
class NullTagsCollection extends TagsCollection
{
    public function toApi(): ?array
    {
        return null;
    }
}
