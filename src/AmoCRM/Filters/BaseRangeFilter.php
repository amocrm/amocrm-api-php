<?php

namespace AmoCRM\Filters;

class BaseRangeFilter
{
    /**
     * @var string|int
     */
    private $to;

    /**
     * @var string|int
     */
    private $from;

    /**
     * @return int|string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param int|string $to
     *
     * @return BaseRangeFilter
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return int|string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param int|string $from
     *
     * @return BaseRangeFilter
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return array
     */
    public function toFilter()
    {
        return [
            'to' => $this->getTo(),
            'from' => $this->getFrom(),
        ];
    }
}
