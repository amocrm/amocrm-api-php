<?php

namespace AmoCRM\Filters;

class BaseRangeFilter
{
    /**
     * @var int
     */
    private $to;

    /**
     * @var int
     */
    private $from;

    /**
     * @return int
     */
    public function getTo(): int
    {
        return $this->to;
    }

    /**
     * @param int $to
     *
     * @return BaseRangeFilter
     */
    public function setTo(int $to): self
    {
        if ($to >= 0) {
            $this->to = $to;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getFrom(): int
    {
        return $this->from;
    }

    /**
     * @param int $from
     *
     * @return BaseRangeFilter
     */
    public function setFrom(int $from): self
    {
        if ($from >= 0) {
            $this->from = $from;
        }

        return $this;
    }

    /**
     * @return array|int[]
     */
    public function toFilter()
    {
        return [
            'from' => $this->getFrom(),
            'to' => $this->getTo(),
        ];
    }
}
