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

    public function testSetLtvWithIntegerPreservesLegacyBehaviour(): void
    {
        $customer = (new CustomerModel())->setLtv(100);

        $this->assertLtvState($customer, 100, 100.0);
    }

    public function testSetLtvWithFloatExposesMinorUnitsAndTruncatesLegacyGetter(): void
    {
        $customer = (new CustomerModel())->setLtv(100.5);

        $this->assertLtvState($customer, 100, 100.5);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFromArrayUsesLegacyLtvWhenOnlyPriceIsProvided(): void
    {
        $this->assertLtvState(
            CustomerModel::fromArray(['id' => 1, 'ltv' => 200]),
            200,
            200.0
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFromArrayUsesMinorUnitsLtvWhenProvided(): void
    {
        $this->assertLtvState(
            CustomerModel::fromArray(['id' => 1, 'ltv_with_minor_units' => 200.75]),
            200,
            200.75
        );
    }

    public function testToApiUsesMinorUnitsInLtvFieldForFloatInput(): void
    {
        $this->assertSame(
            100.5,
            (new CustomerModel())->setLtv(100.5)->toApi()['ltv']
        );
    }

    public function testSetLtvWithStringThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Customer ltv must be an integer, float or null.');

        (new CustomerModel())->setLtv('100.0');
    }

    private function assertLtvState(CustomerModel $customer, ?int $legacyPrice, ?float $priceWithMinorUnits): void
    {
        $this->assertSame($legacyPrice, $customer->getLtv());

        is_null($priceWithMinorUnits)
            ? $this->assertNull($customer->getLtvWithMinorUnits())
            : $this->assertSame($priceWithMinorUnits, $customer->getLtvWithMinorUnits());
    }

    public function testSetAverageCheckWithIntegerPreservesLegacyBehaviour(): void
    {
        $customer = (new CustomerModel())->setAverageCheck(100);

        $this->assertAverageCheckState($customer, 100, 100.0);
    }

    public function testSetAverageCheckWithFloatExposesMinorUnitsAndTruncatesLegacyGetter(): void
    {
        $customer = (new CustomerModel())->setAverageCheck(100.5);

        $this->assertAverageCheckState($customer, 100, 100.5);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFromArrayUsesLegacyAverageCheckWhenOnlyPriceIsProvided(): void
    {
        $this->assertAverageCheckState(
            CustomerModel::fromArray(['id' => 1, 'average_check' => 200]),
            200,
            200.0
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFromArrayUsesMinorUnitsAverageCheckWhenProvided(): void
    {
        $this->assertAverageCheckState(
            CustomerModel::fromArray(['id' => 1, 'average_check_with_minor_units' => 200.75]),
            200,
            200.75
        );
    }

    public function testToApiUsesMinorUnitsInAverageCheckFieldForFloatInput(): void
    {
        $this->assertSame(
            100.5,
            (new CustomerModel())->setAverageCheck(100.5)->toApi()['average_check']
        );
    }

    public function testSetAverageCheckWithStringThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Customer average check must be an integer, float or null.');

        (new CustomerModel())->setAverageCheck('100.0');
    }

    private function assertAverageCheckState(CustomerModel $customer, ?int $legacyPrice, ?float $priceWithMinorUnits): void
    {
        $this->assertSame($legacyPrice, $customer->getAverageCheck());

        is_null($priceWithMinorUnits)
            ? $this->assertNull($customer->getAverageCheckWithMinorUnits())
            : $this->assertSame($priceWithMinorUnits, $customer->getAverageCheckWithMinorUnits());
    }
}
