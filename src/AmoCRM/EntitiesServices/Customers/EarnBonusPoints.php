<?php

namespace AmoCRM\AmoCRM\EntitiesServices\Customers;

/**
 * Класс для накопления бонусных баллов, логика в базовом классе
 *
 * @package AmoCRM\AmoCRM\EntitiesServices\Customers
 */
class EarnBonusPoints extends BaseCustomersBonusPoints
{
    /** @var string */
    protected $pointsChangingMode = 'earn';
}
