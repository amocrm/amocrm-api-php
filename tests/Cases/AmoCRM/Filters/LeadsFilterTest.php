<?php

use AmoCRM\Filters\BaseRangeFilter;
use AmoCRM\Filters\LeadsFilter;
use PHPUnit\Framework\TestCase;

class LeadsFilterTest extends TestCase
{
    /**
     * @var LeadsFilter
     */
    private $leadsFilter;

    public function setUp()
    {
        $this->leadsFilter = new LeadsFilter();
    }

    /**
     * @dataProvider getValidArrayOrNumericFilter
     *
     * @param $createdBy
     * @param $expected
     */
    public function testValidCreatedBy($createdBy, $expected)
    {
        $this->leadsFilter->setCreatedBy($createdBy);
        $this->assertEquals($expected, array_values($this->leadsFilter->getCreatedBy()));
    }

    /**
     * @dataProvider getInvalidArrayOrNumericFilter
     *
     * @param $createdBy
     */
    public function testInvalidCreatedBy($createdBy)
    {
        $this->leadsFilter->setCreatedBy($createdBy);
        $this->assertNull($this->leadsFilter->getCreatedBy());
    }

    public function testValidQuery()
    {
        $string = "hello";
        $this->leadsFilter->setQuery($string);
        $this->assertEquals($string, $this->leadsFilter->getQuery());

        $int = 123;
        $this->leadsFilter->setQuery($int);
        $this->assertEquals("123", $this->leadsFilter->getQuery());
    }

    public function testInvalidQuery()
    {
        $this->expectException(TypeError::class);

        $array = [123];
        $this->leadsFilter->setQuery($array);

        $obj = new stdClass();
        $this->leadsFilter->setQuery($obj);
    }

    /**
     * @dataProvider getValidArrayOrNumericFilter
     *
     * @param $updatedBy
     * @param $expected
     */
    public function testValidUpdatedBy($updatedBy, $expected)
    {
        $this->leadsFilter->setUpdatedBy($updatedBy);
        $this->assertEquals($expected, array_values($this->leadsFilter->getUpdatedBy()));
    }

    /**
     * @dataProvider getInvalidArrayOrNumericFilter
     *
     * @param $updatedBy
     */
    public function testInvalidUpdatedBy($updatedBy)
    {
        $this->leadsFilter->setUpdatedBy($updatedBy);
        $this->assertNull($this->leadsFilter->getUpdatedBy());
    }

    /**
     * @dataProvider getValidIntOrIntRangeFilter
     *
     * @param $closestTaskAt
     * @param $expected
     */
    public function testValidClosestTaskAt($closestTaskAt, $expected)
    {
        $this->leadsFilter->setClosestTaskAt($closestTaskAt);
        $this->assertEquals($expected, $this->leadsFilter->getClosestTaskAt());
    }

    /**
     * @dataProvider getInvalidIntOrIntRangeFilter
     *
     * @param $closestTaskAt
     */
    public function testInvalidClosestTaskAt($closestTaskAt)
    {
        $this->leadsFilter->setClosestTaskAt($closestTaskAt);
        $this->assertNull($this->leadsFilter->getClosestTaskAt());
    }

    public function testBuildFilter()
    {
        $finalFilter = $this->leadsFilter
            ->setIds([123, 234])
            ->setResponsibleUserId(456)
            ->setCreatedAt(123)
            ->setUpdatedAt((new BaseRangeFilter())->setFrom(123)->setTo(987))
            ->buildFilter();

        $this->assertSame([
            'filter' => [
                'id' => [
                    123,
                    234,
                ],
                'responsible_user_id' => [
                    456,
                ],
                'created_at' => 123,
                'updated_at' => [
                    'from' => 123,
                    'to' => 987,
                ],
            ],
            'limit' => 50,
            'page' => 1,
        ], $finalFilter);
    }

    /**
     * @dataProvider getValidArrayOrNumericFilter
     *
     * @param $ids
     * @param $expected
     */
    public function testValidIds($ids, $expected)
    {
        $this->leadsFilter->setIds($ids);
        $this->assertSame($expected, array_values($this->leadsFilter->getIds()));
    }

    /**
     * @dataProvider getInvalidArrayOrNumericFilter
     *
     * @param $ids
     */
    public function testInvalidIds($ids)
    {
        $this->leadsFilter->setIds($ids);
        $this->assertNull($this->leadsFilter->getIds());
    }

    /**
     * @dataProvider getValidArrayOrStringFilter
     *
     * @param $names
     * @param $expected
     */
    public function testValidNames($names, $expected)
    {
        $this->leadsFilter->setNames($names);
        $this->assertSame($expected, array_values($this->leadsFilter->getNames()));
    }

    /**
     * @dataProvider getInvalidArrayOrStringFilter
     *
     * @param $names
     */
    public function testInvalidNames($names)
    {
        $this->leadsFilter->setNames($names);
        $this->assertNull($this->leadsFilter->getNames());
    }

    /**
     * @dataProvider getValidIntOrIntRangeFilter
     *
     * @param $updatedAt
     * @param $expected
     */
    public function testValidUpdatedAt($updatedAt, $expected)
    {
        $this->leadsFilter->setUpdatedAt($updatedAt);
        $this->assertEquals($expected, $this->leadsFilter->getUpdatedAt());
    }

    /**
     * @dataProvider getInvalidIntOrIntRangeFilter
     *
     * @param $updatedAt
     */
    public function testInvalidUpdatedAt($updatedAt)
    {
        $this->leadsFilter->setUpdatedAt($updatedAt);
        $this->assertNull($this->leadsFilter->getUpdatedAt());
    }

    /**
     * @dataProvider getValidIntOrIntRangeFilter
     *
     * @param $createdAt
     * @param $expected
     */
    public function testValidCreatedAt($createdAt, $expected)
    {
        $this->leadsFilter->setCreatedAt($createdAt);
        $this->assertEquals($expected, $this->leadsFilter->getCreatedAt());
    }

    /**
     * @dataProvider getInvalidIntOrIntRangeFilter
     *
     * @param $createdAt
     */
    public function testInvalidCreatedAt($createdAt)
    {
        $this->leadsFilter->setCreatedAt($createdAt);
        $this->assertNull($this->leadsFilter->getCreatedAt());
    }

    /**
     * @dataProvider getValidIntOrIntRangeFilter
     *
     * @param $price
     * @param $expected
     */
    public function testValidPrice($price, $expected)
    {
        $this->leadsFilter->setPrice($price);
        $this->assertEquals($expected, $this->leadsFilter->getPrice());
    }

    /**
     * @dataProvider getInvalidIntOrIntRangeFilter
     *
     * @param $price
     */
    public function testInvalidPrice($price)
    {
        $this->leadsFilter->setPrice($price);
        $this->assertNull($this->leadsFilter->getPrice());
    }


    /**
     * @dataProvider getValidIntOrIntRangeFilter
     *
     * @param $closedAt
     * @param $expected
     */
    public function testValidClosedAt($closedAt, $expected)
    {
        $this->leadsFilter->setClosedAt($closedAt);
        $this->assertEquals($expected, $this->leadsFilter->getClosedAt());
    }

    /**
     * @dataProvider getInvalidIntOrIntRangeFilter
     *
     * @param $closedAt
     */
    public function testInvalidClosedAt($closedAt)
    {
        $this->leadsFilter->setClosedAt($closedAt);
        $this->assertNull($this->leadsFilter->getClosedAt());
    }

    /**
     * @dataProvider getValidArrayOrNumericFilter
     *
     * @param $responsibleUserId
     * @param $expected
     */
    public function testValidResponsibleUserId($responsibleUserId, $expected)
    {
        $this->leadsFilter->setResponsibleUserId($responsibleUserId);
        $this->assertEquals($expected, array_values($this->leadsFilter->getResponsibleUserId()));
    }

    /**
     * @dataProvider getInvalidArrayOrNumericFilter
     *
     * @param $responsibleUserId
     */
    public function testInvalidResponsibleUserId($responsibleUserId)
    {
        $this->leadsFilter->setResponsibleUserId($responsibleUserId);
        $this->assertNull($this->leadsFilter->getResponsibleUserId());
    }

    public function testValidStatuses()
    {
        $statuses = [
            [
                'status_id' => 142,
                'pipeline_id' => 2222,
            ],
        ];
        $this->leadsFilter->setStatuses($statuses);
        $this->assertEquals($statuses, $this->leadsFilter->getStatuses());
    }

    public function testInvalidStatuses()
    {
        $statuses = [
            '123'
        ];
        $this->leadsFilter->setStatuses($statuses);
        $this->assertNull($this->leadsFilter->getStatuses());

        $statuses = [
            [
                'pipeline_id' => 2222,
            ],
            [
                'status_id' => 2222,
            ],
        ];
        $this->leadsFilter->setStatuses($statuses);
        $this->assertNull($this->leadsFilter->getStatuses());

        $this->expectException(TypeError::class);
        $this->leadsFilter->setStatuses("123123");

        $this->leadsFilter->setStatuses(new stdClass());
    }

    public function testValidPipelineIds()
    {
        $pipelineIds = [33, "32343", "21123"];
        $this->leadsFilter->setPipelineIds($pipelineIds);
        $this->assertEquals($pipelineIds, $this->leadsFilter->getPipelineIds());

        $pipelineIds = 123;
        $filterValue = [$pipelineIds];
        $this->leadsFilter->setPipelineIds($pipelineIds);
        $this->assertEquals($filterValue, $this->leadsFilter->getPipelineIds());
    }

    public function testInvalidPipelineIds()
    {
        $pipelineIds = [0, null, false, []];
        $this->leadsFilter->setPipelineIds($pipelineIds);
        $this->assertNull($this->leadsFilter->getPipelineIds());
    }

    public function testSetLimit()
    {
        $filter = $this->leadsFilter->setLimit(1);
        $this->assertInstanceOf(LeadsFilter::class, $filter);
    }

    public function testSetPage()
    {
        $filter = $this->leadsFilter->setPage(1);
        $this->assertInstanceOf(LeadsFilter::class, $filter);
    }

    /**
     * @return array
     */
    public function getValidArrayOrStringFilter() {
        return [
            [
                "hello", ["hello"],
            ],
            [
                "100", ["100"],
            ],
            [
                ["hello", "world"], ["hello", "world"],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getValidArrayOrNumericFilter() {
        return [
            [
                0, [0],
            ],
            [
                100, [100],
            ],
            [
                "100", [100],
            ],
            [
                [1, 2, 3, 4], [1, 2, 3, 4],
            ],
            [
                [-1, 1, 2], [1 , 2],
            ]
        ];
    }

    /**
     * @return array
     */
    public function getInvalidArrayOrStringFilter() {
        return [
            [
                -1,
            ],
            [
                [
                    -1,
                    -100
                ],
            ],
            [
                [
                    -100,
                ],
            ],
            [
                100
            ],
            [
                [
                    true,
                    false
                ]
            ],
            [
                false
            ],
            [
                null
            ],
        ];
    }

    /**
     * @return array
     */
    public function getInvalidArrayOrNumericFilter() {
        return [
            [
                -1,
            ],
            [
                [
                    -1,
                    -100
                ],
            ],
            [
                [
                    -100,
                ],
            ],
            [
                "hello"
            ],
            [
                [
                    true,
                    false
                ]
            ],
            [
                false
            ],
            [
                null
            ],
        ];
    }

    /**
     * @return array
     */
    public function getValidIntOrIntRangeFilter() {
        return [
            [
                (new BaseRangeFilter())
                    ->setTo(123)
                    ->setFrom(321),
                ['from' => 321, 'to' => 123],
            ],
            [
                100, 100,
            ],
        ];
    }

    /**
     * @return array
     */
    public function getInvalidIntOrIntRangeFilter() {
        return [
            [
                -1,
            ],
            [
                [
                    -1,
                    -100
                ],
            ],
            [
                [
                    -100,
                ],
            ],
            [
                "hello"
            ],
            [
                [
                    true,
                    false
                ]
            ],
            [
                false
            ],
            [
                null
            ],
            [
                "1", 1,
            ],
        ];
    }
}
