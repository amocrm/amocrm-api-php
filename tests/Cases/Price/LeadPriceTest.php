<?php

declare(strict_types=1);

namespace Cases\Price;

use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\LeadModel;
use PHPUnit\Framework\TestCase;

class LeadPriceTest extends TestCase
{
    public function testSetPriceWithIntegerPreservesLegacyBehaviour(): void
    {
        $lead = (new LeadModel())->setPrice(100);

        $this->assertPriceState($lead, 100, 100.0);
    }

    public function testSetPriceWithFloatExposesMinorUnitsAndTruncatesLegacyGetter(): void
    {
        $lead = (new LeadModel())->setPrice(100.5);

        $this->assertPriceState($lead, 100, 100.5);
    }

    public function testSetPriceWithSmallFractionKeepsLegacyPriceAtZero(): void
    {
        $lead = (new LeadModel())->setPrice(0.1);

        $this->assertPriceState($lead, 0, 0.1);
        $this->assertSame(0, $lead->toArray()['price']);
        $this->assertSame(0.1, $lead->toArray()['price_with_minor_units']);
        $this->assertSame(0.1, $lead->toApi()['price']);
    }

    public function testSetPriceWithLargeFractionDoesNotRoundLegacyPriceUp(): void
    {
        $lead = (new LeadModel())->setPrice(99999999.999);

        $this->assertPriceState($lead, 99999999, 99999999.999);
        $this->assertNotSame(100000000, $lead->getPrice());
        $this->assertSame(99999999, $lead->toArray()['price']);
        $this->assertSame(99999999.999, $lead->toArray()['price_with_minor_units']);
        $this->assertSame(99999999.999, $lead->toApi()['price']);
    }

    public function testSetPriceWithNullLeavesBothRepresentationsNull(): void
    {
        $lead = (new LeadModel())->setPrice(null);

        $this->assertPriceState($lead, null, null);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFromArrayUsesLegacyPriceWhenOnlyPriceIsProvided(): void
    {
        $this->assertPriceState(
            LeadModel::fromArray(['id' => 1, 'price' => 200]),
            200,
            200.0
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFromArrayUsesMinorUnitsPriceWhenProvided(): void
    {
        $this->assertPriceState(
            LeadModel::fromArray(['id' => 1, 'price_with_minor_units' => 200.75]),
            200,
            200.75
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFromArrayWithSmallFractionKeepsLegacyPriceAtZero(): void
    {
        $this->assertPriceState(
            LeadModel::fromArray(['id' => 1, 'price_with_minor_units' => 0.1]),
            0,
            0.1
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFromArrayWithLargeFractionDoesNotRoundLegacyPriceUp(): void
    {
        $lead = LeadModel::fromArray([
            'id' => 1,
            'price_with_minor_units' => 99999999.999,
        ]);

        $this->assertPriceState($lead, 99999999, 99999999.999);
        $this->assertNotSame(100000000, $lead->getPrice());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFromArrayPrefersMinorUnitsOverLegacyPrice(): void
    {
        $lead = LeadModel::fromArray([
            'id' => 1,
            'price' => 300,
            'price_with_minor_units' => 300.99,
        ]);

        $this->assertPriceState($lead, 300, 300.99);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFromArrayFallsBackToLegacyPriceWhenMinorUnitsIsNull(): void
    {
        $lead = LeadModel::fromArray([
            'id' => 1,
            'price' => 400,
            'price_with_minor_units' => null,
        ]);

        $this->assertPriceState($lead, 400, 400.0);
    }

    public function testToArrayIncludesBothPriceRepresentations(): void
    {
        $this->assertSame(
            [
                'name' => null,
                'price' => 123,
                'price_with_minor_units' => 123.45,
                'responsible_user_id' => null,
                'group_id' => null,
                'status_id' => null,
                'pipeline_id' => null,
                'loss_reason_id' => null,
                'source_id' => null,
                'created_by' => null,
                'updated_by' => null,
                'created_at' => null,
                'updated_at' => null,
                'closed_at' => null,
                'closest_task_at' => null,
                'is_deleted' => null,
                'custom_fields_values' => null,
                'score' => null,
                'account_id' => null,
                'id' => 1,
            ],
            (new LeadModel())
                ->setId(1)
                ->setPrice(123.45)
                ->toArray()
        );
    }

    public function testToApiUsesMinorUnitsInPriceFieldForFloatInput(): void
    {
        $this->assertSame(
            ['price' => 100.5, 'request_id' => '0'],
            (new LeadModel())->setPrice(100.5)->toApi()
        );
    }

    public function testToApiUsesNumericPriceFieldForIntegerInput(): void
    {
        $this->assertSame(
            ['price' => 100.0, 'request_id' => '0'],
            (new LeadModel())->setPrice(100)->toApi()
        );
    }

    public function testToApiOmitsPriceWhenItWasNotSet(): void
    {
        $this->assertSame(['request_id' => '0'], (new LeadModel())->toApi());
    }

    private function assertPriceState(LeadModel $lead, ?int $legacyPrice, ?float $priceWithMinorUnits): void
    {
        $this->assertSame($legacyPrice, $lead->getPrice());

        is_null($priceWithMinorUnits)
            ? $this->assertNull($lead->getPriceWithMinorUnits())
            : $this->assertSame($priceWithMinorUnits, $lead->getPriceWithMinorUnits());
    }
}
