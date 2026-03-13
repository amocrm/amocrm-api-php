<?php

declare(strict_types=1);

namespace Cases\Price;

use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\Customers\CustomerModel;
use PHPUnit\Framework\TestCase;

class CustomerPriceTest extends TestCase
{
    public function testSetNextPriceWithIntegerPreservesLegacyBehaviour(): void
    {
        $customer = (new CustomerModel())->setNextPrice(100);

        $this->assertNextPriceState($customer, 100, 100.0);
    }

    public function testSetNextPriceWithFloatExposesMinorUnitsAndTruncatesLegacyGetter(): void
    {
        $customer = (new CustomerModel())->setNextPrice(100.5);

        $this->assertNextPriceState($customer, 100, 100.5);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFromArrayUsesLegacyNextPriceWhenOnlyPriceIsProvided(): void
    {
        $this->assertNextPriceState(
            CustomerModel::fromArray(['id' => 1, 'next_price' => 200]),
            200,
            200.0
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFromArrayUsesMinorUnitsNextPriceWhenProvided(): void
    {
        $this->assertNextPriceState(
            CustomerModel::fromArray(['id' => 1, 'next_price_with_minor_units' => 200.75]),
            200,
            200.75
        );
    }

    public function testToApiUsesMinorUnitsInNextPriceFieldForFloatInput(): void
    {
        $this->assertSame(
            100.5,
            (new CustomerModel())->setNextPrice(100.5)->toApi()['next_price']
        );
    }

    public function testSetNextPriceWithStringThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Customer next price must be an integer, float or null.');

        (new CustomerModel())->setNextPrice('100.0');
    }

    private function assertNextPriceState(CustomerModel $customer, ?int $legacyPrice, ?float $priceWithMinorUnits): void
    {
        $this->assertSame($legacyPrice, $customer->getNextPrice());

        is_null($priceWithMinorUnits)
            ? $this->assertNull($customer->getNextPriceWithMinorUnits())
            : $this->assertSame($priceWithMinorUnits, $customer->getNextPriceWithMinorUnits());
    }
}
