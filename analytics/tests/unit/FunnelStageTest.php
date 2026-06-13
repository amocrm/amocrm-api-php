<?php

declare(strict_types=1);

namespace Analytics\Tests\Unit;

use Analytics\Models\FunnelStage;
use PHPUnit\Framework\TestCase;

class FunnelStageTest extends TestCase
{
    public function testCreateFromArray(): void
    {
        $data = [
            'id' => 1,
            'pipeline_id' => 1,
            'pipeline_name' => 'Sales Pipeline',
            'status_id' => 2,
            'status_name' => 'Qualified',
            'sort_order' => 2,
            'is_final' => false,
            'lead_count' => 50,
            'total_revenue' => 250000,
            'avg_price' => 5000,
            'conversion_rate' => 0.65,
        ];

        $stage = FunnelStage::fromArray($data);

        $this->assertEquals(1, $stage->getPipelineId());
        $this->assertEquals('Sales Pipeline', $stage->getPipelineName());
        $this->assertEquals(2, $stage->getStatusId());
        $this->assertEquals('Qualified', $stage->getStatusName());
        $this->assertEquals(50, $stage->getLeadCount());
        $this->assertEquals(250000, $stage->getTotalRevenue());
        $this->assertFalse($stage->isFinal());
    }

    public function testSettersAndGetters(): void
    {
        $stage = new FunnelStage();
        
        $stage->setPipelineId(1);
        $stage->setStatusId(5);
        $stage->setStatusName('Closed Won');
        $stage->setLeadCount(100);
        $stage->setTotalRevenue(500000);
        $stage->setConversionRate(0.75);
        $stage->setIsFinal(true);
        $stage->setFinalType('won');

        $this->assertEquals(1, $stage->getPipelineId());
        $this->assertEquals(5, $stage->getStatusId());
        $this->assertEquals('Closed Won', $stage->getStatusName());
        $this->assertEquals(100, $stage->getLeadCount());
        $this->assertEquals(500000, $stage->getTotalRevenue());
        $this->assertEquals(0.75, $stage->getConversionRate());
        $this->assertTrue($stage->isFinal());
        $this->assertEquals('won', $stage->getFinalType());
    }

    public function testAvgDealValueCalculation(): void
    {
        $stage = new FunnelStage();
        $stage->setLeadCount(10);
        $stage->setTotalRevenue(50000);

        $this->assertEquals(5000, $stage->getAvgDealValue());
    }

    public function testAvgDealValueWithZeroLeads(): void
    {
        $stage = new FunnelStage();
        $stage->setLeadCount(0);
        $stage->setTotalRevenue(0);

        $this->assertEquals(0, $stage->getAvgDealValue());
    }

    public function testAvgTimeDaysCalculation(): void
    {
        $stage = new FunnelStage();
        $stage->setAvgTimeSeconds(86400); // 1 day in seconds

        $this->assertEquals(1.0, $stage->getAvgTimeDays());
    }

    public function testToArray(): void
    {
        $stage = new FunnelStage();
        $stage->setPipelineId(1);
        $stage->setPipelineName('Sales');
        $stage->setStatusId(2);
        $stage->setStatusName('New');
        $stage->setLeadCount(100);
        $stage->setTotalRevenue(500000);
        $stage->setConversionRate(1.0);
        $stage->setAvgTimeSeconds(86400);
        $stage->setPreviousConversionRate(0.5);

        $array = $stage->toArray();

        $this->assertEquals(1, $array['pipeline_id']);
        $this->assertEquals('Sales', $array['pipeline_name']);
        $this->assertEquals(2, $array['status_id']);
        $this->assertEquals('New', $array['status_name']);
        $this->assertEquals(100, $array['metrics']['lead_count']);
        $this->assertEquals(500000, $array['metrics']['total_revenue']);
        $this->assertEquals(1.0, $array['metrics']['conversion_rate']);
        $this->assertEquals(1.0, $array['metrics']['avg_time_days']);
        $this->assertNotNull($array['next_stage_conversion']);
        $this->assertEquals(0.5, $array['next_stage_conversion']['conversion_rate']);
    }
}
