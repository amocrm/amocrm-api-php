<?php

declare(strict_types=1);

namespace Analytics\Tests\Unit;

use Analytics\Models\CustomerLTV;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class CustomerLTVTest extends TestCase
{
    public function testCreateFromArray(): void
    {
        $data = [
            'id' => 1,
            'customer_id' => 123,
            'total_revenue' => 15000,
            'transaction_count' => 5,
            'ltv_90_days' => 8000,
            'ltv_180_days' => 12000,
            'ltv_365_days' => 15000,
        ];

        $ltv = CustomerLTV::fromArray($data);

        $this->assertEquals(123, $ltv->getCustomerId());
        $this->assertEquals(15000, $ltv->getTotalRevenue());
        $this->assertEquals(5, $ltv->getTransactionCount());
        $this->assertEquals(8000, $ltv->getLtv90Days());
    }

    public function testSettersAndGetters(): void
    {
        $ltv = new CustomerLTV();
        
        $ltv->setCustomerId(456);
        $ltv->setTotalRevenue(25000);
        $ltv->setTransactionCount(10);
        $ltv->setFirstPurchaseAt(Carbon::parse('2024-01-15'));
        $ltv->setLastPurchaseAt(Carbon::parse('2024-06-01'));

        $this->assertEquals(456, $ltv->getCustomerId());
        $this->assertEquals(25000, $ltv->getTotalRevenue());
        $this->assertEquals(10, $ltv->getTransactionCount());
        $this->assertEquals('2024-01-15', $ltv->getFirstPurchaseAt()->format('Y-m-d'));
        $this->assertEquals('2024-06-01', $ltv->getLastPurchaseAt()->format('Y-m-d'));
    }

    public function testAvgTransactionValue(): void
    {
        $ltv = new CustomerLTV();
        $ltv->setTotalRevenue(10000);
        $ltv->setTransactionCount(4);

        $this->assertEquals(2500, $ltv->getAvgTransactionValue());
    }

    public function testAvgTransactionValueWithZeroTransactions(): void
    {
        $ltv = new CustomerLTV();
        $ltv->setTotalRevenue(0);
        $ltv->setTransactionCount(0);

        $this->assertEquals(0.0, $ltv->getAvgTransactionValue());
    }

    public function testGetLTV(): void
    {
        $ltv = new CustomerLTV();
        $ltv->setTotalRevenue(50000);

        $this->assertEquals(50000, $ltv->getLTV());
    }

    public function testGetDaysSinceFirstPurchase(): void
    {
        $ltv = new CustomerLTV();
        $ltv->setFirstPurchaseAt(Carbon::now()->subDays(30));

        $days = $ltv->getDaysSinceFirstPurchase();
        $this->assertGreaterThanOrEqual(30, $days);
        $this->assertLessThanOrEqual(31, $days);
    }

    public function testGetDaysSinceFirstPurchaseWhenNull(): void
    {
        $ltv = new CustomerLTV();
        
        $this->assertNull($ltv->getDaysSinceFirstPurchase());
    }

    public function testToArray(): void
    {
        $ltv = new CustomerLTV();
        $ltv->setCustomerId(789);
        $ltv->setTotalRevenue(20000);
        $ltv->setTransactionCount(8);
        $ltv->setFirstPurchaseAt(Carbon::parse('2024-03-01'));
        $ltv->setLastPurchaseAt(Carbon::parse('2024-06-15'));

        $array = $ltv->toArray();

        $this->assertEquals(789, $array['customer_id']);
        $this->assertEquals(20000, $array['total_revenue']);
        $this->assertEquals(8, $array['transaction_count']);
        $this->assertEquals(2500, $array['avg_transaction_value']); // 20000/8
        $this->assertEquals(20000, $array['ltv']);
        $this->assertEquals('2024-03-01', $array['first_purchase_at']);
        $this->assertEquals('2024-06-15', $array['last_purchase_at']);
        $this->assertNotNull($array['days_since_first_purchase']);
    }

    public function testContactIdsHandling(): void
    {
        $data = [
            'customer_id' => 100,
            'contact_ids' => [1, 2, 3],
            'total_revenue' => 5000,
            'transaction_count' => 2,
        ];

        $ltv = CustomerLTV::fromArray($data);

        $this->assertEquals([1, 2, 3], $ltv->getContactIds());
    }
}
