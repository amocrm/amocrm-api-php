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

    public function toEntityApi(): ?array
    {
        return null;
    }
}
