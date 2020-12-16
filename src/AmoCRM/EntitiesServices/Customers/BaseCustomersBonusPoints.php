<?php

namespace AmoCRM\AmoCRM\EntitiesServices\Customers;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Helpers\EntityTypesInterface;

/**
 * Класс для моделей накопления/списания бонусных баллов покупателю
 *
 * @package AmoCRM\AmoCRM\EntitiesServices\Customers
 */
abstract class BaseCustomersBonusPoints
{
    public const BONUS_POINTS = 'bonus_points';

    /** @var string */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::CUSTOMERS;

    /**
     * Определяет в наследниках, копит метод или тратит баллы
     * @var string
     */
    protected $pointsChangingMode = '';

    /**
     * @var AmoCRMApiRequest
     */
    protected $request;

    /**
     * BaseEntity constructor.
     *
     * @param AmoCRMApiRequest $request
     */
    public function __construct(AmoCRMApiRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @param int $customerId
     * @param int $bonusPointsCount
     *
     * @return int|null
     */
    public function updateCustomerBonusPoints(int $customerId, int $bonusPointsCount): ?int
    {
        $response = $this->request->post($this->getMethod($customerId), [$this->pointsChangingMode => $bonusPointsCount]);

        return $response['bonus_points'] ?? null;
    }

    /**
     * @param int $customerId
     *
     * @return string
     */
    protected function getMethod(int $customerId): string
    {
        return $this->method . '/' . $customerId . '/' . self::BONUS_POINTS;
    }
}
