<?php

namespace AmoCRM\AmoCRM\Models;

/**
 * Interface TypeAwareInterface
 * @package AmoCRM\AmoCRM\Models
 */
interface TypeAwareInterface
{
    public function getType(): string;
}
