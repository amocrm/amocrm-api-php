<?php

namespace AmoCRM\AmoCRM\Models\Customers;

/**
 * Модель для начисления/списания баллов покупателю
 *
 * @package AmoCRM\AmoCRM\Models\Customers
 */
class BonusPointsActionModel
{
    /** @var int */
    protected $customerId;

    /** @var int */
    protected $points;

    /**
     * BonusPointsActionModel constructor.
     * @param int $customerId
     * @param int $points
     */
    public function __construct(int $customerId, int $points)
    {
        $this->customerId = $customerId;
        $this->points = $points;
    }

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }
}
