<?php

namespace AmoCRM\AmoCRM\EntitiesServices\Customers;

/**
 * Класс для списания бонусных баллов, логика в базовом классе
 *
 * @package AmoCRM\AmoCRM\EntitiesServices\Customers
 */
class RedeemBonusPoints extends BaseCustomersBonusPoints
{
    /** @var string */
    protected $pointsChangingMode = 'redeem';
}
