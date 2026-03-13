<?php

declare(strict_types=1);

namespace Cases\Price;

use AmoCRM\Models\Customers\Transactions\TransactionModel;
use PHPUnit\Framework\TestCase;

class TransactionPriceTest extends TestCase
{
    public function testSetPriceWithIntegerPreservesLegacyBehaviour(): void
    {
        $transaction = (new TransactionModel())->setPrice(100);

        $this->assertPriceState($transaction, 100, 100.0);
    }

    public function testSetPriceWithFloatExposesMinorUnitsAndTruncatesLegacyGetter(): void
    {
        $transaction = (new TransactionModel())->setPrice(100.5);

        $this->assertPriceState($transaction, 100, 100.5);
    }

    public function testSetNextPriceWithIntegerPreservesLegacyBehaviour(): void
    {
        $transaction = (new TransactionModel())->setNextPrice(100);

        $this->assertNextPriceState($transaction, 100, 100.0);
    }

    public function testSetNextPriceWithFloatExposesMinorUnitsAndTruncatesLegacyGetter(): void
    {
        $transaction = (new TransactionModel())->setNextPrice(100.5);

        $this->assertNextPriceState($transaction, 100, 100.5);
    }

    public function testSetPriceWithNullLeavesBothRepresentationsNull(): void
    {
        $transaction = (new TransactionModel())->setPrice(null);

        $this->assertPriceState($transaction, null, null);
    }

    public function testFromArrayUsesLegacyPriceWhenOnlyPriceIsProvided(): void
    {
        $this->assertPriceState(
            TransactionModel::fromArray(['id' => 1, 'price' => 200]),
            200,
            200.0
        );
    }

    public function testFromArrayUsesMinorUnitsPriceWhenProvided(): void
    {
        $this->assertPriceState(
            TransactionModel::fromArray(['id' => 1, 'price_with_minor_units' => 200.75]),
            200,
            200.75
        );
    }

    public function testFromArrayUsesLegacyNextPriceWhenOnlyPriceIsProvided(): void
    {
        $this->assertNextPriceState(
            TransactionModel::fromArray(['id' => 1, 'next_price' => 200]),
            200,
            200.0
        );
    }

    public function testFromArrayUsesMinorUnitsNextPriceWhenProvided(): void
    {
        $this->assertNextPriceState(
            TransactionModel::fromArray(['id' => 1, 'next_price_with_minor_units' => 200.75]),
            200,
            200.75
        );
    }

    public function testToApiUsesMinorUnitsInPriceFieldForFloatInput(): void
    {
        $this->assertSame(
            100.5,
            (new TransactionModel())->setPrice(100.5)->toApi()['price']
        );
    }

    public function testToApiUsesMinorUnitsInNextPriceFieldForFloatInput(): void
    {
        $this->assertSame(
            100.5,
            (new TransactionModel())->setNextPrice(100.5)->toApi()['next_price']
        );
    }

    public function testSetPriceWithStringThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Transaction price must be an integer, float or null.');

        (new TransactionModel())->setPrice('100.0');
    }

    public function testSetNextPriceWithStringThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Transaction next price must be an integer, float or null.');

        (new TransactionModel())->setNextPrice('100.0');
    }

    private function assertPriceState(TransactionModel $transaction, ?int $legacyPrice, ?float $priceWithMinorUnits): void
    {
        $this->assertSame($legacyPrice, $transaction->getPrice());

        is_null($priceWithMinorUnits)
            ? $this->assertNull($transaction->getPriceWithMinorUnits())
            : $this->assertSame($priceWithMinorUnits, $transaction->getPriceWithMinorUnits());
    }

    private function assertNextPriceState(TransactionModel $transaction, ?int $legacyPrice, ?float $priceWithMinorUnits): void
    {
        $this->assertSame($legacyPrice, $transaction->getNextPrice());

        is_null($priceWithMinorUnits)
            ? $this->assertNull($transaction->getNextPriceWithMinorUnits())
            : $this->assertSame($priceWithMinorUnits, $transaction->getNextPriceWithMinorUnits());
    }
}
