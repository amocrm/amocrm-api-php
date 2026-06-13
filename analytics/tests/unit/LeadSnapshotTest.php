<?php

declare(strict_types=1);

namespace Analytics\Tests\Unit;

use Analytics\Models\LeadSnapshot;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class LeadSnapshotTest extends TestCase
{
    public function testCreateFromArray(): void
    {
        $data = [
            'id' => 1,
            'lead_id' => 123,
            'pipeline_id' => 1,
            'status_id' => 2,
            'status_name' => 'Qualified',
            'price' => 5000.00,
            'source_id' => 10,
            'responsible_user_id' => 5,
            'contact_ids' => [1, 2],
            'company_id' => 100,
            'tags' => ['vip', 'enterprise'],
            'created_at' => '2024-01-15T10:00:00+00:00',
            'updated_at' => '2024-06-01T14:30:00+00:00',
        ];

        $snapshot = LeadSnapshot::fromArray($data);

        $this->assertEquals(1, $snapshot->getId());
        $this->assertEquals(123, $snapshot->getLeadId());
        $this->assertEquals(1, $snapshot->getPipelineId());
        $this->assertEquals(2, $snapshot->getStatusId());
        $this->assertEquals('Qualified', $snapshot->getStatusName());
        $this->assertEquals(5000.00, $snapshot->getPrice());
        $this->assertEquals(10, $snapshot->getSourceId());
        $this->assertEquals([1, 2], $snapshot->getContactIds());
        $this->assertEquals(100, $snapshot->getCompanyId());
        $this->assertEquals(['vip', 'enterprise'], $snapshot->getTags());
    }

    public function testToArray(): void
    {
        $snapshot = new LeadSnapshot();
        $snapshot->setLeadId(456);
        $snapshot->setPipelineId(2);
        $snapshot->setStatusId(5);
        $snapshot->setPrice(7500.50);
        $snapshot->setContactIds([10, 20]);
        $snapshot->setCreatedAt(Carbon::parse('2024-03-01'));

        $array = $snapshot->toArray();

        $this->assertEquals(456, $array['lead_id']);
        $this->assertEquals(2, $array['pipeline_id']);
        $this->assertEquals(5, $array['status_id']);
        $this->assertEquals(7500.50, $array['price']);
        $this->assertEquals([10, 20], $array['contact_ids']);
        $this->assertEquals('2024-03-01', $array['created_at']);
    }

    public function testSetAndGet(): void
    {
        $snapshot = new LeadSnapshot();
        
        $snapshot->setLeadId(789);
        $this->assertEquals(789, $snapshot->getLeadId());

        $snapshot->setPipelineId(3);
        $this->assertEquals(3, $snapshot->getPipelineId());

        $snapshot->setStatusId(10);
        $this->assertEquals(10, $snapshot->getStatusId());

        $snapshot->setPrice(10000.00);
        $this->assertEquals(10000.00, $snapshot->getPrice());

        $snapshot->setIsDeleted(true);
        $this->assertTrue($snapshot->isDeleted());
    }

    public function testGetIdReturnsNullWhenNotSet(): void
    {
        $snapshot = new LeadSnapshot();
        $this->assertNull($snapshot->getId());
    }

    public function testCustomFieldsHandling(): void
    {
        $customFields = [
            'utm_source' => 'google',
            'utm_campaign' => 'spring_sale',
            'client_segment' => 'enterprise',
        ];

        $snapshot = new LeadSnapshot();
        $snapshot->setLeadId(100);
        $snapshot->setPipelineId(1);
        $snapshot->setStatusId(1);
        $snapshot->setCustomFields($customFields);

        $array = $snapshot->toArray();
        $this->assertEquals($customFields, $array['custom_fields']);

        // Test hydration
        $data = array_merge($array, ['lead_id' => 100, 'pipeline_id' => 1, 'status_id' => 1]);
        $hydrated = LeadSnapshot::fromArray($data);
        $this->assertEquals($customFields, $hydrated->getCustomFields());
    }

    public function testClosedAtHandling(): void
    {
        $snapshot = new LeadSnapshot();
        $snapshot->setLeadId(1);
        $snapshot->setPipelineId(1);
        $snapshot->setStatusId(142); // Won status
        $snapshot->setClosedAt(Carbon::parse('2024-06-15'));

        $array = $snapshot->toArray();
        $this->assertEquals('2024-06-15', $array['closed_at']);

        // Test hydration with null closed_at
        $data = $array;
        $data['lead_id'] = 1;
        $data['pipeline_id'] = 1;
        $data['status_id'] = 142;
        $data['closed_at'] = null;

        $hydrated = LeadSnapshot::fromArray($data);
        $this->assertNull($hydrated->getClosedAt());
    }
}