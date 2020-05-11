<?php

namespace AmoCRM\Models\Interfaces;

/**
 * Interface TypeAwareInterface
 * @package AmoCRM\Models
 */
interface TypeAwareInterface
{
    public function getType(): string;
}
