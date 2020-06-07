<?php

namespace AmoCRM\Collections\Traits;

use AmoCRM\Models\Interfaces\EntityApiInterface;

trait EntityApiTrait
{
    protected $data = [];

    /**
     * @return null|array
     */
    public function toEntityApi(): ?array
    {
        $result = [];
        /** @var EntityApiInterface $item */
        foreach ($this->data as $key => $item) {
            $result[$key] = $item->toEntityApi();
        }

        return $result;
    }
}
